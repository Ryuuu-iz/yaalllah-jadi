@section('title', 'Login')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LMS SMAN 4 MAROS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-image: url('{{ asset('images/background-sekolah.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        
        .logo-sekolah {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
        
        .glass-morphism {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .input-field {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
        }
        
        .input-field::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .input-field:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.5);
            outline: none;
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.1);
        }

        .input-field.error {
            border-color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }

        .error-message {
            animation: shake 0.5s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <!-- Background Overlay -->
    <div class="absolute inset-0 bg-black/40"></div>

    <!-- Logo -->
    <div class="absolute top-6 left-6 z-20 flex items-center gap-3">
        <div>
            <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SMAN 4 Maros" class="logo-sekolah">
        </div>
        <div class="text-white">
            <h1 class="text-lg font-bold leading-tight">LMS</h1>
            <p class="text-sm font-medium leading-tight">SMAN 4 MAROS</p>
        </div>
    </div>

    <!-- Login Container -->
    <div class="relative z-10 w-full max-w-sm">

        <!-- Login Card -->
        <div class="glass-morphism rounded-2xl shadow-2xl p-8">
            <h2 class="text-2xl font-bold text-white text-center mb-8">Selamat Datang</h2>
            
            <!-- Error Messages from Laravel -->
            @if ($errors->any())
                <div class="error-message mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-3 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="font-medium">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Username Field -->
                <div class="mb-6">
                    <label for="username" class="block text-sm font-medium text-white mb-2">
                        Username
                    </label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        value="{{ old('username') }}"
                        required
                        autofocus
                        class="input-field w-full px-4 py-3 rounded-lg transition-all duration-200 @error('username') error @enderror"
                        placeholder="Masukkan username"
                    >
                    @error('username')
                        <p class="text-red-300 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-white mb-2">
                        Password
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        class="input-field w-full px-4 py-3 rounded-lg transition-all duration-200 @error('password') error @enderror"
                        placeholder="Masukkan password"
                    >
                    @error('password')
                        <p class="text-red-300 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Forgot Password Link -->
                <div class="text-right mb-6">
                    <a href="" class="text-sm text-white hover:text-gray-200 font-medium transition-colors">
                        Forgot password?
                    </a>
                </div>

                <!-- Login Button -->
                <button 
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg"
                >
                    Masuk
                </button>
            </form>

            <!-- Additional Info -->
            <div class="mt-6 pt-6 border-t border-gray-600">
                <p class="text-center text-xs text-gray-300">
                    Sistem Manajemen Pembelajaran<br>
                    <span class="font-semibold">SMA Negeri 4 Maros</span>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
