<?php

namespace App\Console\Commands;

use App\Models\Autor;
use App\Models\Categoria;
use App\Models\Editorial;
use App\Models\Ejemplar;
use App\Models\Libro;
use App\Models\LibroAutor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImportScrapedBooksCommand extends Command
{
    protected $signature = 'libros:import-scraped 
                            {--path= : Ruta absoluta al directorio de scraping} 
                            {--copies=3 : Cantidad de ejemplares iniciales a generar por libro} 
                            {--force : Forzar la sobrescritura de archivos de portadas existentes}';

    protected $description = 'Importa el catálogo de libros, autores, editoriales, categorías y portadas desde los datos de scraping';

    public function handle(): int
    {
        $defaultPath = 'C:\\Users\\c2600\\Downloads\\scraping';
        $basePath = $this->option('path') ?: $defaultPath;
        $copiesPerBook = max(0, (int) $this->option('copies'));
        $forceOverwrite = (bool) $this->option('force');

        $this->info("Iniciando importación desde: {$basePath}");

        if (!File::isDirectory($basePath)) {
            $this->error("El directorio especificado no existe: {$basePath}");
            return Command::FAILURE;
        }

        $jsonFile = $basePath . DIRECTORY_SEPARATOR . 'libros_scraping.json';
        if (!File::exists($jsonFile)) {
            $this->error("No se encontró el archivo de datos: {$jsonFile}");
            return Command::FAILURE;
        }

        $coversDir = $basePath . DIRECTORY_SEPARATOR . 'portadas';
        if (!File::isDirectory($coversDir)) {
            $this->warn("Aviso: No se encontró la carpeta de portadas en: {$coversDir}");
        }

        // 1. Asegurar directorio de portadas en el storage público de Laravel
        $targetStorageDir = storage_path('app/public/portadas');
        if (!File::isDirectory($targetStorageDir)) {
            File::makeDirectory($targetStorageDir, 0755, true);
            $this->info("Directorio de almacenamiento creado: storage/app/public/portadas");
        }

        // 2. Asegurar enlace simbólico public/storage -> storage/app/public
        $publicStorageLink = public_path('storage');
        if (!File::exists($publicStorageLink)) {
            $this->info("Generando enlace simbólico storage:link...");
            $this->call('storage:link');
        }

        // 3. Cargar y decodificar datos
        $jsonContent = File::get($jsonFile);
        $booksData = json_decode($jsonContent, true);

        if (!is_array($booksData) || empty($booksData)) {
            $this->error("El archivo JSON está vacío o tiene un formato inválido.");
            return Command::FAILURE;
        }

        $totalBooks = count($booksData);
        $this->info("Se detectaron {$totalBooks} libros en el archivo JSON.");

        $progressBar = $this->output->createProgressBar($totalBooks);
        $progressBar->start();

        $stats = [
            'libros_procesados' => 0,
            'libros_creados'    => 0,
            'libros_actualizados'=> 0,
            'portadas_copiadas' => 0,
            'autores_creados'   => 0,
            'categorias_creadas'=> 0,
            'editoriales_creadas'=> 0,
            'ejemplares_creados'=> 0,
        ];

        foreach ($booksData as $bookItem) {
            DB::transaction(function () use (
                $bookItem,
                $basePath,
                $targetStorageDir,
                $forceOverwrite,
                $copiesPerBook,
                &$stats
            ) {
                // A. Categoría
                $categoriaInfo = $bookItem['categoria'] ?? [];
                $catName = mb_substr(trim($categoriaInfo['categoria_principal'] ?? 'General'), 0, 50);
                $catDesc = null;
                if (!empty($categoriaInfo['todas_categorias']) && is_array($categoriaInfo['todas_categorias'])) {
                    $catDesc = mb_substr(implode(', ', $categoriaInfo['todas_categorias']), 0, 200);
                }

                $categoria = Categoria::firstOrCreate(
                    ['NombreCategoria' => $catName],
                    ['Descripcion' => $catDesc]
                );
                if ($categoria->wasRecentlyCreated) {
                    $stats['categorias_creadas']++;
                }

                // B. Editorial
                $editorialInfo = $bookItem['editorial'] ?? [];
                $edName = mb_substr(trim($editorialInfo['nombre'] ?? 'Editorial Desconocida'), 0, 100);
                $edPais = mb_substr(trim($editorialInfo['pais_origen'] ?? 'España'), 0, 50);

                $editorial = Editorial::firstOrCreate(
                    ['NombreEditorial' => $edName],
                    ['Pais' => $edPais]
                );
                if ($editorial->wasRecentlyCreated) {
                    $stats['editoriales_creadas']++;
                }

                // C. Autor
                $autorInfo = $bookItem['autor'] ?? [];
                $autorNombreCompleto = trim($autorInfo['nombre'] ?? 'Autor Desconocido');
                [$nombres, $apellidos] = $this->parseAuthorName($autorNombreCompleto);
                
                $nacionalidad = mb_substr(trim($autorInfo['nacionalidad'] ?? 'Desconocida'), 0, 50);
                $seudonimoRaw = trim($autorInfo['seudonimo'] ?? '');
                $seudonimo = ($seudonimoRaw === '' || str_contains(strtolower($seudonimoRaw), 'ninguno'))
                    ? null
                    : mb_substr($seudonimoRaw, 0, 50);

                $autor = Autor::firstOrCreate(
                    [
                        'Nombres' => $nombres,
                        'Apellidos' => $apellidos,
                    ],
                    [
                        'Nacionalidad' => $nacionalidad,
                        'Seudonimo'    => $seudonimo,
                        'Biografia'    => null,
                    ]
                );
                if ($autor->wasRecentlyCreated) {
                    $stats['autores_creados']++;
                }

                // D. Portada
                $portadaRelPath = null;
                $rawCoverRel = $bookItem['portada_local_path'] ?? null;
                if (!empty($rawCoverRel)) {
                    $srcCoverPath = $basePath . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $rawCoverRel);
                    if (File::exists($srcCoverPath)) {
                        $coverFileName = basename($srcCoverPath);
                        $destCoverPath = $targetStorageDir . DIRECTORY_SEPARATOR . $coverFileName;

                        if ($forceOverwrite || !File::exists($destCoverPath)) {
                            File::copy($srcCoverPath, $destCoverPath);
                            $stats['portadas_copiadas']++;
                        }
                        $portadaRelPath = 'portadas/' . $coverFileName;
                    }
                }

                // E. Libro
                $isbn = trim($bookItem['isbn'] ?? $bookItem['isbn_13'] ?? $bookItem['codigo_libro'] ?? '');
                $isbn = mb_substr($isbn, 0, 20);

                $titulo = mb_substr(trim($bookItem['titulo'] ?? 'Sin título'), 0, 150);
                $subtitulo = !empty($bookItem['subtitulo']) ? mb_substr(trim($bookItem['subtitulo']), 0, 150) : null;
                
                $ficha = $bookItem['ficha_tecnica'] ?? [];
                $anio = (int) ($ficha['ano_primera_publicacion'] ?? 2024);
                $paginas = max(1, (int) ($ficha['numero_paginas'] ?? 100));
                
                $idiomaRaw = 'Español';
                if (!empty($ficha['idiomas_disponibles']) && is_array($ficha['idiomas_disponibles'])) {
                    $idiomaRaw = $ficha['idiomas_disponibles'][0];
                }
                $idioma = mb_substr(trim($idiomaRaw), 0, 30);

                $libroExistente = Libro::where('Codigo_ISBN', $isbn)->first();

                $libroData = [
                    'Titulo'           => $titulo,
                    'Subtitulo'        => $subtitulo,
                    'Anio_Publicacion' => $anio,
                    'N_paginas'        => $paginas,
                    'Idioma'           => $idioma,
                    'Codigo_Categoria' => $categoria->Codigo_Categoria,
                    'Codigo_Editorial' => $editorial->Codigo_Editorial,
                ];

                if ($portadaRelPath) {
                    $libroData['portada'] = $portadaRelPath;
                }

                if ($libroExistente) {
                    $libroExistente->update($libroData);
                    $libro = $libroExistente;
                    $stats['libros_actualizados']++;
                } else {
                    $libroData['Codigo_ISBN'] = $isbn;
                    $libro = Libro::create($libroData);
                    $stats['libros_creados']++;
                }

                // F. Relación Libro - Autor (LibroAutor)
                LibroAutor::updateOrCreate(
                    [
                        'Codigo_ISBN'  => $libro->Codigo_ISBN,
                        'Codigo_Autor' => $autor->Codigo_Autor,
                    ],
                    [
                        'Tipo_Autoria' => 'Principal',
                    ]
                );

                // G. Ejemplares físicos de la biblioteca
                if ($copiesPerBook > 0) {
                    $ejemplaresCount = Ejemplar::where('Codigo_ISBN', $libro->Codigo_ISBN)->count();
                    if ($ejemplaresCount === 0) {
                        for ($c = 1; $c <= $copiesPerBook; $c++) {
                            $shelfLetter = chr(65 + ($categoria->Codigo_Categoria % 8)); // A - H
                            $shelfNumber = sprintf('%02d', ($stats['libros_procesados'] % 25) + 1);
                            
                            Ejemplar::create([
                                'Codigo_ISBN'       => $libro->Codigo_ISBN,
                                'Ubicacion_Estante' => "Estante {$shelfLetter}-{$shelfNumber}",
                                'Estado_Ejemplar'   => 'Disponible',
                                'Fecha_Adquisicion' => now()->subDays(($stats['libros_procesados'] % 90) + 1)->toDateString(),
                            ]);
                            $stats['ejemplares_creados']++;
                        }
                    }
                }

                $stats['libros_procesados']++;
            });

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->table(
            ['Métrica', 'Total'],
            [
                ['Libros Procesados', $stats['libros_procesados']],
                ['Libros Nuevos Creados', $stats['libros_creados']],
                ['Libros Actualizados', $stats['libros_actualizados']],
                ['Portadas Copiadas a Storage', $stats['portadas_copiadas']],
                ['Categorías Creadas', $stats['categorias_creadas']],
                ['Editoriales Creadas', $stats['editoriales_creadas']],
                ['Autores Registrados', $stats['autores_creados']],
                ['Ejemplares Físicos Creados', $stats['ejemplares_creados']],
            ]
        );

        $this->info("¡Importación de catálogo de libros completada con éxito!");

        return Command::SUCCESS;
    }

    /**
     * Separa de forma inteligente el nombre completo de un autor en Nombres y Apellidos.
     */
    private function parseAuthorName(string $fullName): array
    {
        $fullName = trim($fullName);

        // Mapeos explícitos para autores hispanos o con apellidos compuestos
        $customOverrides = [
            'Carlos Ruiz Zafón'         => ['Carlos', 'Ruiz Zafón'],
            'Juan Gómez-Jurado'         => ['Juan', 'Gómez-Jurado'],
            'Eva García Sáenz de Urturi'=> ['Eva', 'García Sáenz de Urturi'],
            'Taylor Jenkins Reid'        => ['Taylor', 'Jenkins Reid'],
            'Yuval Noah Harari'          => ['Yuval Noah', 'Harari'],
            'Flor M. Salvador'           => ['Flor M.', 'Salvador'],
            'Alana S. Portero'           => ['Alana S.', 'Portero'],
            'Sarah J. Maas'              => ['Sarah J.', 'Maas'],
            'R. F. Kuang'                => ['R. F.', 'Kuang'],
            'Cixin Liu'                  => ['Cixin', 'Liu'],
        ];

        if (isset($customOverrides[$fullName])) {
            return $customOverrides[$fullName];
        }

        $tokens = preg_split('/\s+/', $fullName);
        if (count($tokens) <= 1) {
            return [mb_substr($tokens[0] ?? 'Anónimo', 0, 50), '—'];
        }

        if (count($tokens) === 2) {
            return [mb_substr($tokens[0], 0, 50), mb_substr($tokens[1], 0, 50)];
        }

        // Si el segundo token es inicial (ej: "Sarah J. Maas")
        if (count($tokens) === 3 && preg_match('/^[A-Z]\.?$/', $tokens[1])) {
            return [
                mb_substr($tokens[0] . ' ' . $tokens[1], 0, 50),
                mb_substr($tokens[2], 0, 50)
            ];
        }

        // Caso general: primer token como nombre, el resto como apellidos
        $nombres = array_shift($tokens);
        $apellidos = implode(' ', $tokens);

        return [
            mb_substr($nombres, 0, 50),
            mb_substr($apellidos, 0, 50)
        ];
    }
}
