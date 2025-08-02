<nav class="bg-black fixed top-0 left-0 w-full z-50" x-data="{ open: false }">
    @auth

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo and Main Menu -->
                <div class="flex items-center">
                    <a href="#" class="text-green-500 font-bold text-2xl tracking-wider mr-6">
                        SiPengaduan
                    </a>

                    <!-- Desktop Main Menu -->
                    <div class="hidden md:flex space-x-4">
                        @if (Auth::user()->role == 'GUEST')
                            <a href="{{ route('report.article.index') }}"
                                class="text-orange-500 hover:text-white hover:bg-green-600 px-3 py-2 rounded-md text-sm font-medium flex items-center transition duration-300">
                                <i class="fas fa-file-alt mr-2"></i> Pengaduan
                            </a>
                            <a href="{{ route('report.article.me') }}"
                                class="text-orange-500 hover:text-white hover:bg-green-600 px-3 py-2 rounded-md text-sm font-medium flex items-center transition duration-300">
                                <i class="fas fa-clipboard mr-2"></i> Pengaduan Saya
                            </a>
                        @elseif (Auth::user()->role == 'STAFF')
                            <a href="{{ route('report.article.index') }}"
                                class="text-orange-500 hover:text-white hover:bg-green-600 px-3 py-2 rounded-md text-sm font-medium flex items-center transition duration-300">
                                <i class="fas fa-file-alt mr-2"></i> Pengaduan
                            </a>
                            <a href="{{ route('response.index') }}"
                                class="text-orange-500 hover:text-white hover:bg-green-600 px-3 py-2 rounded-md text-sm font-medium flex items-center transition duration-300">
                                <i class="fas fa-chart-pie mr-2"></i> Daftar Pengaduan
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}"
                                class="text-orange-500 hover:text-white hover:bg-green-600 px-3 py-2 rounded-md text-sm font-medium flex items-center transition duration-300">
                                <i class="fas fa-chart-line mr-2"></i> Dashboard
                            </a>
                            <a href="{{ route('user.index') }}"
                                class="text-orange-500 hover:text-white hover:bg-green-600 px-3 py-2 rounded-md text-sm font-medium flex items-center transition duration-300">
                                <i class="fas fa-user mr-2"></i> User
                            </a>
                        @endif

                    </div>
                </div>

                <!-- Desktop Profile and Actions -->
                <div class="hidden md:flex items-center space-x-4">
                    <!-- Profile Dropdown -->
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen"
                            class="flex items-center text-white hover:text-orange-500 focus:outline-none transition duration-300">
                            <img src="https://via.placeholder.com/40" alt="Profil" class="w-10 h-10 rounded-full mr-2">
                            <span class="font-medium">{{ Auth::user()->name }}</span>
                            <i class="fas fa-caret-down ml-2"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition
                            class="absolute right-0 mt-2 w-56 bg-white text-black rounded-lg shadow-xl z-50">
                            <div class="py-1">
                                <div class="border-t border-gray-200"></div>
                                <a href="{{ route('logout') }}"
                                    class="px-4 py-2 text-sm hover:bg-red-100 text-red-600 flex items-center">
                                    <i class="fas fa-sign-out-alt mr-3"></i> Keluar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button type="button" @click="open = !open"
                        class="text-white hover:text-orange-500 focus:outline-none">
                        <i x-show="!open" class="fas fa-bars text-2xl"></i>
                        <i x-show="open" class="fas fa-times text-2xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="md:hidden bg-black absolute left-0 right-0 mt-2 z-50 shadow-xl">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    @if (Auth::user()->role == 'GUEST')
                        <a href="{{ route('report.article.index') }}"
                            class="text-white hover:text-orange-500 hover:bg-green-600 px-3 py-3 rounded-md text-base font-medium flex items-center">
                            <i class="fas fa-home mr-3"></i> Pengaduan
                        </a>
                        <a href="{{ route('report.article.me') }}"
                            class="text-white hover:text-orange-500 hover:bg-green-600 px-3 py-3 rounded-md text-base font-medium flex items-center">
                            <i class="fas fa-box mr-3"></i> Pengaduan Saya
                        </a>
                    @elseif (Auth::user()->role == 'STAFF')
                        <a href="{{ route('report.article.index') }}"
                            class="text-white hover:text-orange-500 hover:bg-green-600 px-3 py-3 rounded-md text-base font-medium flex items-center">
                            <i class="fas fa-box mr-3"></i> Pengaduan
                        </a>
                        <a href="{{ route('response.index') }}"
                            class="text-white hover:text-orange-500 hover:bg-green-600 px-3 py-3 rounded-md text-base font-medium flex items-center">
                            <i class="fas fa-info-circle mr-3"></i> Daftar Pengaduan
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}"
                            class="text-white hover:text-orange-500 hover:bg-green-600 px-3 py-3 rounded-md text-base font-medium flex items-center">
                            <i class="fas fa-home mr-3"></i> Dashboard
                        </a>
                        <a href="{{ route('user.index') }}"
                            class="text-white hover:text-orange-500 hover:bg-green-600 px-3 py-3 rounded-md text-base font-medium flex items-center">
                            <i class="fas fa-box mr-3"></i> User
                        </a>
                    @endif


                    <div class="border-t border-gray-800 pt-4">
                        <div class="flex items-center px-3 py-3">
                            <img src="https://via.placeholder.com/40" alt="Profil" class="w-10 h-10 rounded-full mr-3">
                            <div>
                                <div class="text-base font-medium text-white">{{ Auth::user()->name }}</div>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <a href="{{ route('logout') }}"
                                class="px-3 py-3 text-white hover:text-orange-500 hover:bg-green-600 flex items-center">
                                <i class="fas fa-sign-out-alt mr-3"></i> Keluar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endauth

</nav>
