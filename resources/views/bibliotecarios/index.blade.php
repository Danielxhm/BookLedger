<x-app-layout>
    <div class="max-w-[1680px] w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        {{-- Breadcrumbs & Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                <nav class="flex items-center text-xs font-medium text-gray-500 gap-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-900 transition-colors">Panel</a>
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-gray-900 font-semibold">Bibliotecarios</span>
                </nav>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Personal bibliotecario</h1>
                <p class="text-xs text-gray-500">Gestión de empleados, cuentas de acceso al sistema, asignación de roles y jerarquías.</p>
            </div>

            <div class="flex items-center gap-3">
                @can('bibliotecarios.crear')
                    <a href="{{ route('bibliotecarios.create') }}" class="inline-flex items-center justify-center min-h-[44px] px-4 py-2.5 text-xs font-semibold text-white bg-gray-900 rounded-xl hover:bg-black transition-colors shadow-xs gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Nuevo bibliotecario
                    </a>
                @endcan
            </div>
        </div>

        @if (session('status'))
            <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 text-xs font-medium text-gray-800 flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            </div>
        @endif

        {{-- Table Card --}}
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
            <div class="p-4 sm:px-6 sm:py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Listado de personal</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 text-gray-700">
                        {{ $bibliotecarios->total() ?? count($bibliotecarios) }} miembros
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto" role="region" aria-label="Tabla de personal bibliotecario" tabindex="0">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200/80 bg-gray-50/75 text-[11px] font-semibold uppercase tracking-wider text-gray-500">
                            <th scope="col" class="py-3 px-4">Bibliotecario</th>
                            <th scope="col" class="py-3 px-4">Documento</th>
                            <th scope="col" class="py-3 px-4">Cuenta de correo</th>
                            <th scope="col" class="py-3 px-4">Rol en sistema</th>
                            <th scope="col" class="py-3 px-4">Supervisor</th>
                            <th scope="col" class="py-3 px-4">Estado</th>
                            <th scope="col" class="py-3 px-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($bibliotecarios as $b)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gray-900 text-white flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($b->Nombres, 0, 1) . substr($b->Apellidos, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 text-xs">{{ $b->Nombres }} {{ $b->Apellidos }}</p>
                                            <p class="text-[11px] text-gray-500 font-mono">@<span>{{ $b->NombreUsuario }}</span></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap font-mono text-gray-700">
                                    {{ $b->Num_documento ?? '—' }}
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    @if($b->user?->email)
                                        <span class="text-gray-800 font-medium">{{ $b->user->email }}</span>
                                    @else
                                        <span class="text-gray-400 italic">Sin cuenta vinculada</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    @php
                                        $roles = $b->user?->roles->pluck('name')->join(', ');
                                    @endphp
                                    @if($roles)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold {{ str_contains($roles, 'Administrador') ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-800 border border-gray-200' }}">
                                            {{ $roles }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 italic text-[11px]">Sin rol</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap text-gray-600">
                                    {{ $b->supervisor ? $b->supervisor->Nombres . ' ' . $b->supervisor->Apellidos : '—' }}
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    @if($b->EstadoEmpleado === 'Activo')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                            Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @can('bibliotecarios.editar')
                                            <a href="{{ route('bibliotecarios.edit', $b) }}" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors" title="Editar bibliotecario">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            </a>
                                        @endcan
                                        @can('bibliotecarios.eliminar')
                                            <form action="{{ route('bibliotecarios.destroy', $b) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas eliminar este bibliotecario? Se desvinculará también su cuenta de usuario si existe.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Eliminar bibliotecario">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 px-4 text-center">
                                    <div class="max-w-xs mx-auto text-center space-y-2">
                                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mx-auto text-gray-400">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        </div>
                                        <p class="text-xs font-medium text-gray-900">No hay bibliotecarios registrados</p>
                                        <p class="text-xs text-gray-500">Agrega miembros al equipo para asignar préstamos y gestionar devoluciones.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($bibliotecarios, 'links') && $bibliotecarios->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $bibliotecarios->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
