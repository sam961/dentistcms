<nav x-data="{ open: false }" class="bg-gradient-to-r from-blue-600 via-purple-600 to-teal-600 shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-md">
                            <i class="fas fa-tooth text-blue-600 text-xl"></i>
                        </div>
                        <span class="text-xl font-bold text-white">Dentist CMS</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-10 sm:flex">
                    @if(auth()->user()->is_super_admin)
                        <!-- Super Admin Navigation - Empty for clean header -->
                    @else
                        <!-- Regular User Navigation -->
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="nav-link-modern">
                            <i class="fas fa-chart-pie mr-2"></i>
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('patients.index')" :active="request()->routeIs('patients.*')" class="nav-link-modern">
                            <i class="fas fa-users mr-2"></i>
                            {{ __('Patients') }}
                        </x-nav-link>
                        <x-nav-link :href="route('appointments.index')" :active="request()->routeIs('appointments.*')" class="nav-link-modern">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            {{ __('Appointments') }}
                        </x-nav-link>
                        <x-nav-link :href="route('treatments.index')" :active="request()->routeIs('treatments.*')" class="nav-link-modern">
                            <i class="fas fa-procedures mr-2"></i>
                            {{ __('Treatments') }}
                        </x-nav-link>
                        <x-nav-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')" class="nav-link-modern">
                            <i class="fas fa-file-invoice-dollar mr-2"></i>
                            {{ __('Invoices') }}
                        </x-nav-link>
                        <x-nav-link :href="route('dentists.index')" :active="request()->routeIs('dentists.*')" class="nav-link-modern">
                            <i class="fas fa-user-md mr-2"></i>
                            {{ __('Dentists') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-white bg-white/20 hover:bg-white/30 focus:outline-none transition ease-in-out duration-200 backdrop-blur-sm">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                                    <span class="text-sm font-bold text-blue-600">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                </div>
                                <span>{{ Auth::user()->name }}</span>
                            </div>

                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-white hover:bg-white/20 focus:outline-none focus:bg-white/20 transition duration-200 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gradient-to-b from-blue-600/95 to-purple-600/95 backdrop-blur-sm">
        <div class="pt-2 pb-3 space-y-1">
            @if(auth()->user()->is_super_admin)
                <!-- Super Admin Mobile Navigation - Empty for clean header -->
            @else
                <!-- Regular User Mobile Navigation -->
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:bg-white/20">
                    <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                    </svg>
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('patients.index')" :active="request()->routeIs('patients.*')" class="text-white hover:bg-white/20">
                    <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    {{ __('Patients') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('appointments.index')" :active="request()->routeIs('appointments.*')" class="text-white hover:bg-white/20">
                    <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V3m0 4v5m0-5h4m0 0V3m0 4v5M9 12h6"/>
                    </svg>
                    {{ __('Appointments') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('treatments.index')" :active="request()->routeIs('treatments.*')" class="text-white hover:bg-white/20">
                    <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547A8.014 8.014 0 004 21h16a8.014 8.014 0 00-.244-5.572zM12 10a4 4 0 100-8 4 4 0 000 8z"/>
                    </svg>
                    {{ __('Treatments') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')" class="text-white hover:bg-white/20">
                    <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    {{ __('Invoices') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('dentists.index')" :active="request()->routeIs('dentists.*')" class="text-white hover:bg-white/20">
                    <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ __('Dentists') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-white/20">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-white/70">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-white hover:bg-white/20">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" class="text-white hover:bg-white/20"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
