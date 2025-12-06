<nav class="bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            <!-- Logo and Title -->
            <a href="{{ route('siswa.dashboard') }}" class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-7 h-7 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
                </div>
                <div class="hidden sm:block">
                    <h1 class="text-lg font-bold leading-tight">LMS</h1>
                    <p class="text-xs text-blue-100">SMAN 4 MAROS</p>
                </div>
            </a>

            <!-- Navigation Menu -->
            <div class="flex items-center space-x-4 sm:space-x-8">
                <a href="{{ route('siswa.dashboard') }}" class="text-sm sm:text-base hover:text-blue-200 transition-colors {{ request()->routeIs('siswa.dashboard') ? 'font-bold border-b-2 border-white pb-1' : '' }}">
                    My Classes
                </a>
                <a href="{{ route('siswa.teachers.index') }}" class="text-sm sm:text-base hover:text-blue-200 transition-colors {{ request()->routeIs('siswa.teachers.*') ? 'font-bold border-b-2 border-white pb-1' : '' }}">
                    Teachers
                </a>

                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 hover:text-blue-200 transition-colors focus:outline-none">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white rounded-full flex items-center justify-center text-blue-600 font-bold text-sm">
                            {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                        </div>
                        <svg class="w-4 h-4 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open" 
                         @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl py-2 z-50"
                         style="display: none;">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->dataSiswa->nama ?? auth()->user()->username }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ auth()->user()->username }}</p>
                        </div>
                        <a href="{{ route('siswa.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 transition-colors">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Profile
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="pb-4">
            <div class="relative max-w-2xl">
                <input type="text" placeholder="Search Class" class="w-full bg-white text-gray-800 rounded-lg pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-300 shadow-sm">
                <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</nav>