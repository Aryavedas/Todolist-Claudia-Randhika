<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FunTodo</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>

<body class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 min-h-screen flex items-center justify-center p-4 selection:bg-purple-200 selection:text-purple-900">

    <div class="w-full max-w-md bg-white rounded-[2.5rem] shadow-2xl shadow-purple-200/50 border border-white relative overflow-hidden">
        
        <div class="h-2 w-full bg-gradient-to-r from-indigo-400 via-purple-500 to-pink-500"></div>

        <div class="p-8 sm:p-10">
            
            <div class="text-center mb-8">
                <div class="w-12 h-12 bg-gradient-to-tr from-purple-600 to-pink-500 rounded-2xl flex items-center justify-center text-white font-bold shadow-lg shadow-purple-200 mx-auto mb-4">
                    <i class="fa-solid fa-check text-xl"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight">Welcome Back!</h2>
                <p class="text-gray-400 text-sm mt-1">Siap melanjutkan produktivitasmu?</p>
            </div>

            @if (session('status'))
                <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 p-3 rounded-xl text-center">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div class="space-y-2">
                    <label for="email" class="text-sm font-bold text-gray-600 ml-1">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                            class="w-full bg-gray-50 text-gray-800 font-medium rounded-2xl pl-11 pr-5 py-4 border-2 border-transparent focus:border-purple-400 focus:bg-white focus:outline-none transition-all placeholder-gray-300 shadow-sm"
                            placeholder="name@example.com">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs ml-1 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center ml-1">
                        <label for="password" class="text-sm font-bold text-gray-600">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-semibold text-purple-500 hover:text-purple-700 transition-colors">
                                Lupa Password?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="w-full bg-gray-50 text-gray-800 font-medium rounded-2xl pl-11 pr-5 py-4 border-2 border-transparent focus:border-purple-400 focus:bg-white focus:outline-none transition-all placeholder-gray-300 shadow-sm"
                            placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs ml-1 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center ml-1">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                        <div class="relative flex items-center">
                            <input id="remember_me" type="checkbox" name="remember" 
                                class="peer h-5 w-5 rounded-md border-2 border-gray-300 text-purple-600 focus:ring-purple-500 focus:ring-offset-0 cursor-pointer transition-all checked:border-purple-500 checked:bg-purple-500">
                            <i class="fa-solid fa-check text-white text-[10px] absolute left-1 top-1 opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"></i>
                        </div>
                        <span class="ml-2 text-sm text-gray-500 font-medium group-hover:text-gray-700 transition-colors">Ingat saya</span>
                    </label>
                </div>

                <button type="submit" 
                    class="w-full bg-gradient-to-r from-purple-600 to-pink-500 hover:from-purple-700 hover:to-pink-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-purple-200 transform transition-all active:scale-95 flex items-center justify-center gap-2">
                    <span>Masuk Sekarang</span>
                    <i class="fa-solid fa-arrow-right-long"></i>
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500 font-medium">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-purple-600 font-bold hover:text-purple-800 transition-colors ml-1 hover:underline">
                        Daftar disini
                    </a>
                </p>
            </div>

        </div>

        <div class="bg-gray-50 py-3 text-center border-t border-gray-100">
            <p class="text-[10px] text-gray-400 font-medium">FunTodo &bull; Secure Login</p>
        </div>
    </div>

</body>
</html>