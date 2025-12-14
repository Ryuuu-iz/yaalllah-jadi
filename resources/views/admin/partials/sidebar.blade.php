{{-- Admin Sidebar Component --}}
<aside id="sidebar" class="sidebar w-64 bg-gradient-to-b from-blue-600 to-blue-800 text-white flex-shrink-0 relative will-change-[width] overflow-hidden">
    {{-- Toggle Button (when expanded) --}}
    <button onclick="toggleSidebar()" id="toggle-btn-expanded" class="absolute right-4 top-6 bg-white/10 backdrop-blur-sm text-white rounded-lg p-2 hover:bg-white/20 z-50 transition-all duration-300">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
        </svg>
    </button>

    {{-- Logo Section --}}
    <div class="logo-section border-b border-blue-500">
        <a href="{{ route('admin.dashboard') }}" class="logo-link block p-6 hover:bg-blue-700 transition-colors">
            <div class="flex items-center justify-center">
                <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center flex-shrink-0 cursor-pointer" onclick="event.preventDefault(); event.stopPropagation(); toggleSidebar()" id="logo-toggle">
                    <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo" class="w-10 h-10 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <svg class="w-8 h-8 text-blue-600 hidden" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
                </div>
                <div class="sidebar-text ml-3">
                    <h1 class="text-lg font-bold">SMAN 4 MAROS</h1>
                    <p class="text-xs text-blue-200">Learning Management System</p>
                </div>
            </div>
        </a>
    </div>

    {{-- Navigation Menu --}}
    <nav class="p-4 space-y-2" style="max-height: calc(100vh - 200px); overflow-y: auto; overflow-x: hidden;" x-data="{ academicOpen: {{ request()->routeIs('admin.courses.*') || request()->routeIs('admin.subjects.*') || request()->routeIs('admin.classes.*') || request()->routeIs('admin.academic-years.*') ? 'true' : 'false' }} }">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} transform transition-all duration-200 hover:scale-[1.02]">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="sidebar-text whitespace-nowrap">Dashboard</span>
        </a>

        <a href="{{ route('admin.users.index') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.users.*') ? 'active' : '' }} transform transition-all duration-200 hover:scale-[1.02]">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <span class="sidebar-text whitespace-nowrap">User Management</span>
        </a>

        {{-- Academic Management Dropdown --}}
        <div>
            <button @click="academicOpen = !academicOpen" class="sidebar-item flex items-center justify-between w-full px-4 py-3 rounded-lg {{ request()->routeIs('admin.courses.*') || request()->routeIs('admin.subjects.*') || request()->routeIs('admin.classes.*') || request()->routeIs('admin.academic-years.*') ? 'active' : '' }} transform transition-all duration-200 hover:scale-[1.02]">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span class="sidebar-text whitespace-nowrap">Academic</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-300 sidebar-text" :class="{'rotate-180': academicOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            
            <div x-show="academicOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="ml-4 mt-2 space-y-2 sidebar-text">
                <a href="{{ route('admin.courses.index') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span>Courses</span>
                </a>
                
                <a href="{{ route('admin.subjects.index') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span>Subjects</span>
                </a>
                
                <a href="{{ route('admin.classes.index') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span>Classes</span>
                </a>
                
                <a href="{{ route('admin.academic-years.index') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('admin.academic-years.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>Academic Years</span>
                </a>
            </div>
        </div>

        <a href="{{ route('admin.attendance.index') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }} transform transition-all duration-200 hover:scale-[1.02]">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
            <span class="sidebar-text whitespace-nowrap">Attendance</span>
        </a>

        <a href="{{ route('admin.tasks.index') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.tasks.*') ? 'active' : '' }} transform transition-all duration-200 hover:scale-[1.02]">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <span class="sidebar-text whitespace-nowrap">Tasks</span>
        </a>

        <a href="{{ route('admin.materials.index') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.materials.*') ? 'active' : '' }} transform transition-all duration-200 hover:scale-[1.02]">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span class="sidebar-text whitespace-nowrap">Materials</span>
        </a>

        <a href="{{ route('admin.profile') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.profile') ? 'active' : '' }} transform transition-all duration-200 hover:scale-[1.02]">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span class="sidebar-text whitespace-nowrap">Profile</span>
        </a>
    </nav>

    {{-- Logout Button --}}
    <div class="absolute bottom-4 left-0 right-0 px-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg w-full hover:bg-red-600 transform transition-all duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span class="sidebar-text whitespace-nowrap">Logout</span>
            </button>
        </form>
    </div>
</aside>

<style>
    /* Sidebar collapsed styles */
    .sidebar.collapsed {
        width: 80px !important;
    }

    .sidebar.collapsed .logo-section {
        padding: 1.5rem 0;
    }

    .sidebar.collapsed .logo-link {
        padding: 0 !important;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .sidebar.collapsed .sidebar-text {
        display: none;
    }

    .sidebar.collapsed .sidebar-item {
        justify-content: center;
        padding: 0.75rem !important;
    }

    .sidebar.collapsed nav {
        padding: 1rem 0.5rem;
    }

    .sidebar.collapsed .sidebar-item span {
        display: none;
    }

    .sidebar.collapsed .sidebar-item svg {
        margin: 0;
    }
</style>

@push('scripts')
<script>
// Restore sidebar state
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtnExpanded = document.getElementById('toggle-btn-expanded');
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    
    if (isCollapsed && sidebar) {
        sidebar.classList.add('collapsed');
        if (toggleBtnExpanded) toggleBtnExpanded.style.display = 'none';
    }
});

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtnExpanded = document.getElementById('toggle-btn-expanded');
    
    if (!sidebar) return;
    
    sidebar.classList.toggle('collapsed');
    const isCollapsed = sidebar.classList.contains('collapsed');
    localStorage.setItem('sidebarCollapsed', isCollapsed);
    
    // Show/hide toggle button based on state
    if (toggleBtnExpanded) {
        toggleBtnExpanded.style.display = isCollapsed ? 'none' : 'block';
    }
}
</script>
@endpush