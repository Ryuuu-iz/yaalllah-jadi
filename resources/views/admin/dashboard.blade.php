@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div>
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Dashboard</h1>
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Total Teachers -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-sm font-medium opacity-90 mb-2">Total Teachers</p>
                <p class="text-5xl font-bold mb-2">{{ $totalGuru }}</p>
            </div>
            <div class="absolute right-6 top-1/2 transform -translate-y-1/2 opacity-20">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
            </div>
        </div>

        <!-- Total Subject -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-sm font-medium opacity-90 mb-2">Total Subject</p>
                <p class="text-5xl font-bold mb-2">{{ $totalSubjects }}</p>
            </div>
            <div class="absolute right-6 top-1/2 transform -translate-y-1/2 opacity-20">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                </svg>
            </div>
        </div>

        <!-- Total Student -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-sm font-medium opacity-90 mb-2">Total Student</p>
                <p class="text-5xl font-bold mb-2">{{ $totalSiswa }}</p>
            </div>
            <div class="absolute right-6 top-1/2 transform -translate-y-1/2 opacity-20">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                </svg>
            </div>
        </div>

        <!-- Total Courses -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-sm font-medium opacity-90 mb-2">Total Courses</p>
                <p class="text-5xl font-bold mb-2">{{ $totalCourses }}</p>
            </div>
            <div class="absolute right-6 top-1/2 transform -translate-y-1/2 opacity-20">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>

        <!-- Academic Year -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-sm font-medium opacity-90 mb-2">Academic Year</p>
                <p class="text-4xl font-bold mb-2">{{ $academicYearText }}</p>
            </div>
            <div class="absolute right-6 top-1/2 transform -translate-y-1/2 opacity-20">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>

        <!-- Total Classes -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-sm font-medium opacity-90 mb-2">Total Classes</p>
                <p class="text-5xl font-bold mb-2">{{ $totalClasses }}</p>
            </div>
            <div class="absolute right-6 top-1/2 transform -translate-y-1/2 opacity-20">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <a href="{{ route('admin.users.create') }}" class="bg-blue-50 hover:bg-blue-100 rounded-xl p-6 text-center transition-all duration-200 transform hover:scale-105">
            <div class="flex justify-center mb-3">
                <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            <p class="text-blue-700 font-semibold">Add User</p>
        </a>

        <a href="{{ route('admin.classes.create') }}" class="bg-green-50 hover:bg-green-100 rounded-xl p-6 text-center transition-all duration-200 transform hover:scale-105">
            <div class="flex justify-center mb-3">
                <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <p class="text-green-700 font-semibold">Create Class</p>
        </a>

        <a href="{{ route('admin.attendance.index') }}" class="bg-purple-50 hover:bg-purple-100 rounded-xl p-6 text-center transition-all duration-200 transform hover:scale-105">
            <div class="flex justify-center mb-3">
                <svg class="w-12 h-12 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <p class="text-purple-700 font-semibold">Attendance Recap</p>
        </a>

        <a href="{{ route('admin.subjects.create') }}" class="bg-yellow-50 hover:bg-yellow-100 rounded-xl p-6 text-center transition-all duration-200 transform hover:scale-105">
            <div class="flex justify-center mb-3">
                <svg class="w-12 h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <p class="text-yellow-700 font-semibold">Add Subject</p>
        </a>

        <a href="{{ route('admin.users.create') }}" class="bg-red-50 hover:bg-red-100 rounded-xl p-6 text-center transition-all duration-200 transform hover:scale-105">
            <div class="flex justify-center mb-3">
                <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <p class="text-red-700 font-semibold">Set Academic-Year</p>
        </a>
    </div>
</div>
@endsection