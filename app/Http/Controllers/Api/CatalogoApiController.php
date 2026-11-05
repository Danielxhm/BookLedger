<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Autor;
use App\Models\Categoria;
use App\Models\Editorial;
use App\Models\Ejemplar;
use App\Models\Libro;
use App\Models\Socio;
use Illuminate\Http\Request;

class CatalogoApiController extends Controller
{
    public function libros(Request $request)
    {
        $libros = Libro::with(['categoria', 'editorial', 'autores'])
            ->withCount([
                'ejemplares',
                'ejemplares as ejemplares_disponibles_count' => fn ($q) => $q->where('Estado_Ejemplar', 'Disponible'),
            ])
            ->when($request->buscar, fn ($q, $b) => $q->where(fn ($w) => $w
                ->where('Titulo', 'like', "%{$b}%")
                ->orWhere('Codigo_ISBN', 'like', "%{$b}%")))
            ->when($request->categoria, fn ($q, $c) => $q->where('Codigo_Categoria', $c))
            ->when($request->editorial, fn ($q, $e) => $q->where('Codigo_Editorial', $e))
            ->when($request->autor, fn ($q, $a) => $q->whereHas('autores', fn ($w) => $w->where('autors.Codigo_Autor', $a)))
            ->orderBy('Titulo')
            ->paginate($this->porPagina($request))
            ->withQueryString();

        return response()->json($libros);
    }

    public function libro(Libro $libro)
    {
        return response()->json([
            'libro' => $libro->load(['categoria', 'editorial', 'autores', 'ejemplares']),
        ]);
    }

    public function categorias()
    {
        return response()->json([
            'categorias' => Categoria::withCount('libros')
                ->with('subcategorias')
                ->orderBy('NombreCategoria')
                ->get(),
        ]);
    }

    public function autores()
    {
        return response()->json([
            'autores' => Autor::withCount('libros')
                ->orderBy('Apellidos')
                ->orderBy('Nombres')
                ->get(),
        ]);
    }

    public function editoriales()
    {
        return response()->json([
            'editoriales' => Editorial::withCount('libros')
                ->orderBy('NombreEditorial')
                ->get(),
        ]);
    }

    public function ejemplares(Request $request)
    {
        $ejemplares = Ejemplar::with('libro')
            ->when($request->estado, fn ($q, $e) => $q->where('Estado_Ejemplar', $e))
            ->when($request->isbn, fn ($q, $i) => $q->where('Codigo_ISBN', $i))
            ->orderBy('Codigo_Ejemplar')
            ->paginate($this->porPagina($request))
            ->withQueryString();

        return response()->json($ejemplares);
    }

    public function socios(Request $request)
    {
        $socios = Socio::query()
            ->when($request->estado, fn ($q, $e) => $q->where('EstadoSocio', $e))
            ->orderBy('Apellidos')
            ->orderBy('Nombres')
            ->paginate($this->porPagina($request))
            ->withQueryString();

        return response()->json($socios);
    }

    private function porPagina(Request $request): int
    {
        return min(max((int) $request->input('per_page', 15), 1), 100);
    }
}
