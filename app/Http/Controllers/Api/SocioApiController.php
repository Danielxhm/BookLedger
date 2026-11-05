<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Libro;
use App\Models\Reserva;
use App\Models\Socio;
use App\Models\User;
use App\Notifications\NuevaReservaNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class SocioApiController extends Controller
{
    /**
     * Autenticación del socio mediante API (genera token Sanctum).
     */
    public function login(Request $request)
    {
        $request->validate([
            'Email' => 'required|email',
            'password' => 'required|string',
        ]);

        $socio = Socio::where('Email', $request->Email)->first();

        if (! $socio || ! Hash::check($request->password, $socio->password)) {
            return response()->json([
                'message' => 'Credenciales incorrectas.',
            ], 401);
        }

        if ($socio->EstadoSocio === 'Inactivo') {
            return response()->json([
                'message' => 'Tu cuenta de socio se encuentra dada de baja o inactiva. Contacta a la administración de la biblioteca.',
            ], 403);
        }

        $token = $socio->createToken('socio-api-token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso.',
            'token' => $token,
            'socio' => [
                'Codigo_Socio' => $socio->Codigo_Socio,
                'Num_Documento' => $socio->Num_Documento,
                'Nombres' => $socio->Nombres,
                'Apellidos' => $socio->Apellidos,
                'Email' => $socio->Email,
                'Telefono' => $socio->Telefono,
                'EstadoSocio' => $socio->EstadoSocio,
            ],
        ]);
    }

    /**
     * Cierra la sesión revocando el token actual.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente.',
        ]);
    }

    /**
     * Obtiene el dashboard / perfil del socio autenticado (préstamos, reservas, multas desglosadas).
     */
    public function dashboard(Request $request)
    {
        /** @var Socio $socio */
        $socio = $request->user();

        $prestamos = $socio->prestamos()
            ->with([
                'detalles.ejemplar.libro.autores',
                'detalles.ejemplar.libro.categoria',
                'detalles.ejemplar.libro.editorial',
                'detalles.multas',
            ])
            ->orderByDesc('FechaPrestamo')
            ->get();

        $reservas = $socio->reservas()
            ->with([
                'libro.autores',
                'libro.categoria',
                'libro.editorial',
            ])
            ->orderByDesc('FechaReserva')
            ->get();

        $multas = $socio->multas()
            ->with([
                'detalle.ejemplar.libro.autores',
                'detalle.ejemplar.libro.categoria',
                'detalle.ejemplar.libro.editorial',
                'detalle.prestamo',
            ])
            ->orderByDesc('Fecha_Emision')
            ->orderByDesc('Codigo_Multa')
            ->get();

        $multasPendientes = (float) $socio->multasPendientes()->sum('Monto');

        return response()->json([
            'socio' => [
                'Codigo_Socio' => $socio->Codigo_Socio,
                'Num_Documento' => $socio->Num_Documento,
                'Nombres' => $socio->Nombres,
                'Apellidos' => $socio->Apellidos,
                'Email' => $socio->Email,
                'Telefono' => $socio->Telefono,
                'EstadoSocio' => $socio->EstadoSocio,
            ],
            'prestamos' => $prestamos,
            'reservas' => $reservas,
            'multas' => $multas,
            'multas_pendientes' => $multasPendientes,
        ]);
    }

    /**
     * Listado detallado de multas asociadas al socio autenticado.
     */
    public function multas(Request $request)
    {
        /** @var Socio $socio */
        $socio = $request->user();

        $multas = $socio->multas()
            ->with([
                'detalle.ejemplar.libro.autores',
                'detalle.ejemplar.libro.categoria',
                'detalle.ejemplar.libro.editorial',
                'detalle.prestamo',
            ])
            ->when($request->estado, fn ($q, $e) => $q->where('Estado_Pago', $e))
            ->orderByDesc('Fecha_Emision')
            ->orderByDesc('Codigo_Multa')
            ->get();

        return response()->json([
            'multas' => $multas,
            'total_pendiente' => (float) $socio->multasPendientes()->sum('Monto'),
        ]);
    }

    /**
     * Catálogo de libros disponibles para consulta por API.
     */
    public function catalogo()
    {
        $libros = Libro::with(['categoria', 'editorial', 'autores'])
            ->orderBy('Titulo')
            ->get();

        return response()->json([
            'libros' => $libros,
        ]);
    }

    /**
     * Crear una reserva para el socio autenticado.
     */
    public function reservar(Request $request)
    {
        /** @var Socio $socio */
        $socio = $request->user();

        if ($socio->EstadoSocio === 'Sancionado' || $socio->multasPendientes()->exists()) {
            return response()->json([
                'message' => 'Tu cuenta se encuentra sancionada por multas pendientes. Debes saldar tus cargos en biblioteca antes de solicitar nuevas reservas.',
            ], 403);
        }

        $request->validate([
            'Codigo_ISBN' => 'required|exists:libros,Codigo_ISBN',
        ]);

        $yaTiene = Reserva::where('Codigo_Socio', $socio->Codigo_Socio)
            ->where('Codigo_ISBN', $request->Codigo_ISBN)
            ->where('EstadoReserva', 'Pendiente')
            ->exists();

        if ($yaTiene) {
            return response()->json([
                'message' => 'Ya tienes una reserva pendiente para este libro.',
            ], 422);
        }

        $reservaExistente = Reserva::where('Codigo_ISBN', $request->Codigo_ISBN)
            ->where('EstadoReserva', 'Pendiente')
            ->exists();

        $reserva = Reserva::create([
            'Codigo_Socio' => $socio->Codigo_Socio,
            'Codigo_ISBN' => $request->Codigo_ISBN,
            'EstadoReserva' => 'Pendiente',
        ])->load('socio', 'libro');

        $bibliotecarios = User::role(['Administrador', 'Bibliotecario'])->get();
        Notification::send($bibliotecarios, new NuevaReservaNotification($reserva));

        return response()->json([
            'message' => $reservaExistente
                ? 'Este libro cuenta con una reserva previa. Has quedado registrado en la lista de espera.'
                : 'Reserva registrada exitosamente.',
            'en_espera' => $reservaExistente,
            'reserva' => $reserva,
        ], 201);
    }

    /**
     * Cancelar una reserva del socio autenticado.
     */
    public function cancelarReserva(Request $request, Reserva $reserva)
    {
        /** @var Socio $socio */
        $socio = $request->user();

        if ((int) $reserva->Codigo_Socio !== (int) $socio->Codigo_Socio) {
            return response()->json([
                'message' => 'No tienes permiso para cancelar esta reserva.',
            ], 403);
        }

        $reserva->update(['EstadoReserva' => 'Cancelada']);

        return response()->json([
            'message' => 'Reserva cancelada correctamente.',
            'reserva' => $reserva,
        ]);
    }
}
