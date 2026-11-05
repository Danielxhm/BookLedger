<nav x-data="{ open: false }" class="{{ request()->routeIs('dashboard') ? 'bg-transparent pt-4 sm:pt-5 px-4 sm:px-6 lg:px-8' : 'bg-white border-b border-gray-100 sticky top-0 z-40' }}">
    <!-- Primary Navigation Menu -->
    <div class="{{ request()->routeIs('dashboard') ? 'max-w-[1680px] w-full mx-auto bg-[#f3f4f6] rounded-t-[32px] border-t border-x border-gray-200/80 px-8 lg:px-10 pb-4 sm:pb-5' : 'max-w-[1680px] w-full mx-auto px-4 sm:px-6 lg:px-8' }}">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center">
                <!-- Logo con tamaño fijo reservado -->
                <div class="shrink-0 flex items-center w-[120px] h-10 sm:w-[132px] sm:h-11">
                    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center shrink-0 focus:outline-none">
                        <x-application-mark class="block h-10 sm:h-11 w-[120px] sm:w-[132px]" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:-my-px sm:ms-8 lg:ms-12 sm:flex sm:items-center sm:gap-1.5 lg:gap-3 xl:gap-4.5 h-full">
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Panel') }}
                    </x-nav-link>

                    @can('socios.ver')
                        <x-nav-link href="{{ route('socios.index') }}" :active="request()->routeIs('socios.*')">
                            {{ __('Socios') }}
                        </x-nav-link>
                    @endcan
                    @can('categorias.ver')
                        <x-nav-link href="{{ route('categorias.index') }}" :active="request()->routeIs('categorias.*')">
                            {{ __('Categorías') }}
                        </x-nav-link>
                    @endcan
                    @can('editoriales.ver')
                        <x-nav-link href="{{ route('editoriales.index') }}" :active="request()->routeIs('editoriales.*')">
                            {{ __('Editoriales') }}
                        </x-nav-link>
                    @endcan
                    @can('autores.ver')
                        <x-nav-link href="{{ route('autores.index') }}" :active="request()->routeIs('autores.*')">
                            {{ __('Autores') }}
                        </x-nav-link>
                    @endcan
                    @can('ejemplares.ver')
                        <x-nav-link href="{{ route('ejemplares.index') }}" :active="request()->routeIs('ejemplares.*')">
                            {{ __('Ejemplares') }}
                        </x-nav-link>
                    @endcan
                    @can('libros.ver')
                        <x-nav-link href="{{ route('libros.index') }}" :active="request()->routeIs('libros.*')">
                            {{ __('Libros') }}
                        </x-nav-link>
                    @endcan
                    @can('devoluciones.ver')
                        <x-nav-link href="{{ route('devoluciones.index') }}" :active="request()->routeIs('devoluciones.*')">
                            {{ __('Devoluciones') }}
                        </x-nav-link>
                    @endcan
                    @can('prestamos.ver')
                        <x-nav-link href="{{ route('prestamos.index') }}" :active="request()->routeIs('prestamos.*')">
                            {{ __('Préstamos') }}
                        </x-nav-link>
                    @endcan
                    @can('multas.ver')
                        <x-nav-link href="{{ route('multas.index') }}" :active="request()->routeIs('multas.*')">
                            {{ __('Multas') }}
                        </x-nav-link>
                    @endcan
                    @can('bibliotecarios.ver')
                        <x-nav-link href="{{ route('bibliotecarios.index') }}" :active="request()->routeIs('bibliotecarios.*')">
                            {{ __('Bibliotecarios') }}
                        </x-nav-link>
                    @endcan
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-1">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->currentTeam->name }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('Team Settings') }}
                                    </x-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <!-- Team Switcher -->
                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="border-t border-gray-200"></div>

                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Switch Teams') }}
                                        </div>

                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Notifications -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="72">
                        <x-slot name="trigger">
                            <button
                                class="relative inline-flex items-center justify-center w-10 h-10 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                                </svg>
                                @if (Auth::user()->unreadNotifications->count() > 0)
                                    <span
                                        class="absolute top-1.5 right-1.5 bg-red-500 text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">{{ Auth::user()->unreadNotifications->count() }}</span>
                                @endif
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="w-full max-h-80 overflow-y-auto">
                                <div
                                    class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wide border-b border-gray-100">
                                    Notificaciones
                                </div>
                                @forelse (Auth::user()->notifications->take(10) as $n)
                                    <div
                                        class="px-4 py-2.5 text-sm border-b border-gray-50 {{ $n->read_at ? 'text-gray-400' : 'text-gray-800 bg-gray-50' }}">
                                        {{ $n->data['mensaje'] }}
                                    </div>
                                @empty
                                    <div class="px-4 py-6 text-sm text-gray-400 text-center">Sin notificaciones.</div>
                                @endforelse
                                @if (Auth::user()->unreadNotifications->count() > 0)
                                    <form method="POST" action="{{ route('notificaciones.marcarLeidas') }}"
                                        class="px-4 py-2 border-t border-gray-100">
                                        @csrf
                                        <button class="text-xs font-semibold text-gray-900 hover:text-gray-600">Marcar
                                            todas como leídas</button>
                                    </form>
                                @endif
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Settings Dropdown -->
                <div class="ms-1 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button
                                    class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-indigo-300 transition">
                                    <img class="size-9 rounded-full object-cover ring-2 ring-gray-100"
                                        src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-full">
                                    <button type="button"
                                        class="inline-flex items-center gap-2 px-3 py-1.5 border border-gray-300/80 text-sm leading-4 font-semibold rounded-full text-gray-700 bg-[#e5e7eb] hover:bg-[#d8dbdf] hover:text-gray-900 focus:outline-none focus:bg-[#d8dbdf] active:bg-[#d8dbdf] transition ease-in-out duration-150 shadow-2xs">
                                        <span
                                            class="w-7 h-7 rounded-full bg-gray-900 text-white flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </span>
                                        {{ Auth::user()->name }}

                                        <svg class="ms-0.5 -me-0.5 size-4 text-gray-500"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Administrar Cuenta') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}" wire:navigate>
                                {{ __('Perfil') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('Tokens API') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                    {{ __('Cerrar Sesión') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                {{ __('Panel') }}
            </x-responsive-nav-link>

            @can('socios.ver')
                <x-responsive-nav-link href="{{ route('socios.index') }}" :active="request()->routeIs('socios.*')">
                    {{ __('Socios') }}
                </x-responsive-nav-link>
            @endcan
            @can('categorias.ver')
                <x-responsive-nav-link href="{{ route('categorias.index') }}" :active="request()->routeIs('categorias.*')">
                    {{ __('Categorías') }}
                </x-responsive-nav-link>
            @endcan
            @can('editoriales.ver')
                <x-responsive-nav-link href="{{ route('editoriales.index') }}" :active="request()->routeIs('editoriales.*')">
                    {{ __('Editoriales') }}
                </x-responsive-nav-link>
            @endcan
            @can('autores.ver')
                <x-responsive-nav-link href="{{ route('autores.index') }}" :active="request()->routeIs('autores.*')">
                    {{ __('Autores') }}
                </x-responsive-nav-link>
            @endcan
            @can('ejemplares.ver')
                <x-responsive-nav-link href="{{ route('ejemplares.index') }}" :active="request()->routeIs('ejemplares.*')">
                    {{ __('Ejemplares') }}
                </x-responsive-nav-link>
            @endcan
            @can('libros.ver')
                <x-responsive-nav-link href="{{ route('libros.index') }}" :active="request()->routeIs('libros.*')">
                    {{ __('Libros') }}
                </x-responsive-nav-link>
            @endcan
            @can('prestamos.ver')
                <x-responsive-nav-link href="{{ route('prestamos.index') }}"
                    :active="request()->routeIs('prestamos.*')">{{ __('Préstamos') }}</x-responsive-nav-link>
            @endcan
            @can('devoluciones.ver')
                <x-responsive-nav-link href="{{ route('devoluciones.index') }}"
                    :active="request()->routeIs('devoluciones.*')">{{ __('Devoluciones') }}</x-responsive-nav-link>
            @endcan
            @can('multas.ver')
                <x-responsive-nav-link href="{{ route('multas.index') }}"
                    :active="request()->routeIs('multas.*')">{{ __('Multas') }}</x-responsive-nav-link>
            @endcan
            @can('bibliotecarios.ver')
                <x-responsive-nav-link href="{{ route('bibliotecarios.index') }}"
                    :active="request()->routeIs('bibliotecarios.*')">{{ __('Bibliotecarios') }}</x-responsive-nav-link>
            @endcan
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                            alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}"
                        :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-responsive-nav-link>
                    @endcan

                    <!-- Team Switcher -->
                    @if (Auth::user()->allTeams()->count() > 1)
                        <div class="border-t border-gray-200"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Switch Teams') }}
                        </div>

                        @foreach (Auth::user()->allTeams() as $team)
                            <x-switchable-team :team="$team" component="responsive-nav-link" />
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
</nav>
