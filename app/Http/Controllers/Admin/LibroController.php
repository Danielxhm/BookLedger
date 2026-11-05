<?php


namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Editorial;
use App\Models\Libro;
use App\Models\Autor;
use App\Models\LibroAutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

//Controlador de administrador de Libros
class LibroController extends Controller
{
    /**
     * Reglas de validación comunes a store() y update().
     * En update() el ISBN no se valida como "required|digits:13"
     * porque no se puede modificar (es la clave del libro).
     */
    private function reglas(bool $esCreacion = true): array
    {
        return [
            'Codigo_ISBN'       => $esCreacion ? ['required', 'digits:13'] : ['sometimes'],
            'Titulo'            => 'required|string|max:150',
            'Subtitulo'         => 'nullable|string|max:200',
            'Anio_Publicacion'  => 'required|integer|digits:4',
            'N_paginas'         => 'required|integer|min:1|max:10000',
            'Idioma'            => 'required|string|max:50',
            'Codigo_Categoria'  => 'required|exists:categorias,Codigo_Categoria',
            'Codigo_Editorial'  => 'required|exists:editorials,Codigo_Editorial',
            'portada'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'autores'           => 'required|array|min:1',
            'autores.*'         => 'exists:autors,Codigo_Autor',
            'tipo_autoria'      => 'nullable|array',
            'tipo_autoria.*'    => 'in:Principal,Coautor,Traductor',
        ];
    }

    protected function mensajes(): array
    {
        return [
            'Codigo_ISBN.digits' => 'El código ISBN debe tener exactamente 13 números.',
        ];
    }

    public function index()
    {
        $libros = Libro::orderBy('Titulo')->paginate(15);
        $categorias = Categoria::orderBy('NombreCategoria')->get();
        $editoriales = Editorial::orderBy('NombreEditorial')->get();
        $librosAutor = LibroAutor::orderBy('Codigo_ISBN')->get();
        $autores = Autor::orderBy('Nombres')->get();

        return view('libros.index', compact('libros', 'categorias', 'editoriales', 'autores', 'librosAutor'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('NombreCategoria')->get();
        $editoriales = Editorial::orderBy('NombreEditorial')->get();
        $autores = Autor::orderBy('Nombres')->get();
        $librosAutor = collect();

        return view('libros.create', compact('categorias', 'editoriales', 'autores', 'librosAutor'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->reglas(true), $this->mensajes());

        // Manejo de la subida de imagen
        if ($request->hasFile('portada')) {
            $data['portada'] = $request->file('portada')->store('portadas', 'public');
        }

        DB::transaction(function () use ($data) {
            $libro = Libro::create([
                'Codigo_ISBN'      => $data['Codigo_ISBN'],
                'Titulo'           => $data['Titulo'],
                'Subtitulo'        => $data['Subtitulo'] ?? null,
                'Anio_Publicacion' => $data['Anio_Publicacion'],
                'N_paginas'        => $data['N_paginas'],
                'Idioma'           => $data['Idioma'],
                'Codigo_Categoria' => $data['Codigo_Categoria'],
                'Codigo_Editorial' => $data['Codigo_Editorial'],
                'portada'          => $data['portada'] ?? null,
            ]);

            $this->sincronizarAutores($libro, $data);
        });

        return redirect()->route('libros.index')->with('status', 'Libro creado correctamente.');
    }

    public function show(Libro $libro)
    {
        $libro = Libro::where('Codigo_ISBN', $libro)->firstOrFail();

        return view('libros.showbooks', compact('libro'));
    }

    public function edit(Libro $libro)
    {
        $categorias = Categoria::orderBy('NombreCategoria')->get();
        $editoriales = Editorial::orderBy('NombreEditorial')->get();
        $autores = Autor::orderBy('Nombres')->get();
        $librosAutor = LibroAutor::where('Codigo_ISBN', $libro->Codigo_ISBN)->get();

        return view('libros.edit', compact('categorias', 'editoriales', 'libro', 'autores', 'librosAutor'));
    }

    public function update(Request $request, Libro $libro)
    {
        $data = $request->validate($this->reglas(false), $this->mensajes());

        // Si se sube una nueva imagen, eliminar la anterior
        if ($request->hasFile('portada')) {
            if ($libro->portada && Storage::disk('public')->exists($libro->portada)) {
                Storage::disk('public')->delete($libro->portada);
            }
            $data['portada'] = $request->file('portada')->store('portadas', 'public');
        } else {
            $data['portada'] = $libro->portada;
        }

        DB::transaction(function () use ($data, $libro) {
            // Nota: Codigo_ISBN NO se actualiza aquí a propósito.
            // Es la clave del libro y cambiarla rompería la FK con libro_autors.
            $libro->update([
                'Titulo'           => $data['Titulo'],
                'Subtitulo'        => $data['Subtitulo'] ?? null,
                'Anio_Publicacion' => $data['Anio_Publicacion'],
                'N_paginas'        => $data['N_paginas'],
                'Idioma'           => $data['Idioma'],
                'Codigo_Categoria' => $data['Codigo_Categoria'],
                'Codigo_Editorial' => $data['Codigo_Editorial'],
                'portada'          => $data['portada'],
            ]);
        });

        return redirect()->route('libros.index')->with('status', 'Libro actualizado correctamente.');
    }

    public function destroy(Libro $libro)
    {
        // Limpiar el archivo del disco antes o después de eliminar el registro
        if ($libro->portada && Storage::disk('public')->exists($libro->portada)) {
            Storage::disk('public')->delete($libro->portada);
        }

        $libro->delete();
        return redirect()->route('libros.index')->with('status', 'Libro eliminado.');
    }

    private function sincronizarAutores(Libro $libro, array $data): void
    {
        $tiposPorAutor = $data['tipo_autoria'] ?? [];

        foreach ($data['autores'] as $codigoAutor) {
            LibroAutor::create([
                'Codigo_ISBN'  => $libro->Codigo_ISBN,
                'Codigo_Autor' => $codigoAutor,
                'Tipo_Autoria' => $tiposPorAutor[$codigoAutor] ?? 'Principal',
            ]);
        }
    }
}