<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - FunTodo</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Glass Navbar Effect */
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
</head>

<body
    class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 min-h-screen text-gray-800 selection:bg-purple-200 selection:text-purple-900">

    <nav class="fixed top-0 w-full z-40 glass-nav transition-all duration-300">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0 flex items-center gap-2">
                    <a href="{{ route('todos.index') }}" class="flex items-center gap-2 group">
                        <div
                            class="w-8 h-8 bg-gradient-to-tr from-purple-600 to-pink-500 rounded-lg flex items-center justify-center text-white font-bold shadow-lg shadow-purple-200 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <span
                            class="font-extrabold text-xl tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-500">
                            FunTodo
                        </span>
                    </a>
                </div>

                <div class="relative group">
                    <button class="flex items-center gap-3 focus:outline-none transition-opacity hover:opacity-80">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-gray-700 leading-none">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-gray-400 font-medium">Explorer</p>
                        </div>
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-400 to-purple-400 p-[2px]">
                            <div class="w-full h-full rounded-full bg-white flex items-center justify-center">
                                <span class="font-bold text-indigo-500 text-sm">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </span>
                            </div>
                        </div>
                        <i
                            class="fa-solid fa-chevron-down text-gray-400 text-xs transition-transform duration-200 group-hover:rotate-180"></i>
                    </button>

                    <div
                        class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl shadow-purple-100 border border-gray-100 overflow-hidden transform scale-95 opacity-0 invisible group-hover:scale-100 group-hover:opacity-100 group-hover:visible transition-all duration-200 origin-top-right z-50">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 sm:hidden">
                            <p class="text-sm font-bold text-gray-700 truncate">{{ Auth::user()->name }}</p>
                        </div>

                        <a href="{{ route('todos.index') }}"
                            class="block px-4 py-2 text-sm text-gray-600 hover:bg-purple-50 hover:text-purple-600 transition-colors">
                            <i class="fa-solid fa-house mr-2 w-4 text-center"></i> ToDoList
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left block px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition-colors">
                                <i class="fa-solid fa-arrow-right-from-bracket mr-2 w-4 text-center"></i> Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="pt-24 pb-12 px-4 sm:px-6">
        <div class="max-w-4xl mx-auto space-y-8">

            <div class="mb-6">
                <h1 class="text-3xl font-extrabold text-gray-800">Pengaturan Akun ⚙️</h1>
                <p class="text-gray-500 text-sm mt-1">Kelola informasi profil dan keamanan akunmu.</p>
            </div>

            <div
                class="bg-white rounded-[2.5rem] shadow-xl shadow-purple-100/50 border border-purple-50 p-8 sm:p-10 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-400 to-indigo-500"></div>

                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Informasi Profil</h2>
                    <p class="text-sm text-gray-400">Update nama akun dan alamat email kamu.</p>
                </div>

                <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-600 ml-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full bg-gray-50 text-gray-800 font-medium rounded-2xl px-5 py-4 border-2 border-transparent focus:border-indigo-400 focus:bg-white focus:outline-none transition-all placeholder-gray-300">
                        @error('name')
                            <p class="text-red-500 text-xs ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-600 ml-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full bg-gray-50 text-gray-800 font-medium rounded-2xl px-5 py-4 border-2 border-transparent focus:border-indigo-400 focus:bg-white focus:outline-none transition-all placeholder-gray-300">
                        @error('email')
                            <p class="text-red-500 text-xs ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-indigo-200 transition-all active:scale-95">
                            Simpan Perubahan
                        </button>
                        @if (session('status') === 'profile-updated')
                            <p class="text-sm text-green-600 font-medium animate-pulse">Tersimpan!</p>
                        @endif
                    </div>
                </form>
            </div>

            <div
                class="bg-white rounded-[2.5rem] shadow-xl shadow-purple-100/50 border border-purple-50 p-8 sm:p-10 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-purple-400 to-pink-500"></div>

                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Update Password 🔒</h2>
                    <p class="text-sm text-gray-400">Pastikan password kamu panjang dan aman.</p>
                </div>

                <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    @method('put')

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-600 ml-1">Password Saat Ini</label>
                        <input type="password" name="current_password" autocomplete="current-password"
                            class="w-full bg-gray-50 text-gray-800 font-medium rounded-2xl px-5 py-4 border-2 border-transparent focus:border-purple-400 focus:bg-white focus:outline-none transition-all placeholder-gray-300">
                        @error('current_password')
                            <p class="text-red-500 text-xs ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-600 ml-1">Password Baru</label>
                        <input type="password" name="password" autocomplete="new-password"
                            class="w-full bg-gray-50 text-gray-800 font-medium rounded-2xl px-5 py-4 border-2 border-transparent focus:border-purple-400 focus:bg-white focus:outline-none transition-all placeholder-gray-300">
                        @error('password')
                            <p class="text-red-500 text-xs ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-600 ml-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" autocomplete="new-password"
                            class="w-full bg-gray-50 text-gray-800 font-medium rounded-2xl px-5 py-4 border-2 border-transparent focus:border-purple-400 focus:bg-white focus:outline-none transition-all placeholder-gray-300">
                        @error('password_confirmation')
                            <p class="text-red-500 text-xs ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit"
                            class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-purple-200 transition-all active:scale-95">
                            Update Password
                        </button>
                        @if (session('status') === 'password-updated')
                            <p class="text-sm text-green-600 font-medium animate-pulse">Berhasil diubah!</p>
                        @endif
                    </div>
                </form>
            </div>

            <div class="bg-red-50 rounded-[2.5rem] border border-red-100 p-8 sm:p-10 relative overflow-hidden">

                <div class="mb-6">
                    <h2 class="text-xl font-bold text-red-700">Hapus Akun ⚠️</h2>
                    <p class="text-sm text-red-400">Tindakan ini permanen. Semua data akan hilang selamanya.</p>
                </div>

                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <p class="text-sm text-gray-600 max-w-md">
                        Setelah akun dihapus, semua resource dan data (termasuk Todo List) akan dihapus secara permanen.
                        Silakan unduh data yang ingin Anda simpan sebelum menghapus.
                    </p>

                    <form method="POST" action="{{ route('profile.destroy') }}"
                        onsubmit="return confirm('Apakah Anda YAKIN ingin menghapus akun ini?');">
                        @csrf
                        @method('DELETE')

                        <div class="mb-4">
                            <input type="password" name="password" placeholder="Masukkan password Anda" required
                                class="border rounded-xl px-4 py-3 w-full">

                            @error('password', 'userDeletion')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl">
                            Hapus Akun
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>

    <div class="fixed bottom-0 w-full py-3 bg-white/60 backdrop-blur-sm border-t border-white/50 text-center z-40">
        <p class="text-[10px] text-gray-400 font-medium tracking-wide uppercase">
            FunTodo &bull; Profile Settings
        </p>
    </div>

</body>

</html>
