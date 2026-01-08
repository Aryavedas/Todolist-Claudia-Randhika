<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Fun Todo</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Hide scrollbar */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .check-anim {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
        }

        /* --- ANIMASI TOAST BARU --- */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        @keyframes timer {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }

        .toast-enter {
            animation: slideInRight 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        .toast-exit {
            animation: slideOutRight 0.4s ease-in forwards;
        }

        .toast-timer {
            animation: timer 3s linear forwards;
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
                    <button id="user-menu-button"
                        class="flex items-center gap-3 focus:outline-none transition-opacity hover:opacity-80">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-gray-700 leading-none">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-gray-400 font-medium">Explorer</p>
                        </div>

                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-400 to-purple-400 p-[2px]">
                            <div
                                class="w-full h-full rounded-full bg-white flex items-center justify-center overflow-hidden">
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
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <a href="{{ route('profile.edit') }}"
                            class="block px-4 py-2 text-sm text-gray-600 hover:bg-purple-50 hover:text-purple-600 transition-colors">
                            <i class="fa-regular fa-user mr-2 w-4 text-center"></i> Profile
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

    @if (session('success'))
        <div id="toast-box" class="fixed bottom-6 right-6 z-50 w-full max-w-xs transform translate-x-full toast-enter">
            <div class="bg-white rounded-2xl shadow-2xl shadow-purple-200 border border-purple-50 overflow-hidden">
                <div class="p-4 flex items-start gap-3">
                    <div
                        class="flex-shrink-0 w-8 h-8 bg-green-100 text-green-500 rounded-full flex items-center justify-center mt-0.5">
                        <i class="fa-solid fa-check text-sm"></i>
                    </div>

                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-gray-800">Sukses!</h4>
                        <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ session('success') }}</p>
                    </div>

                    <button onclick="closeToast()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <div class="h-1 bg-green-50 w-full">
                    <div class="h-full bg-green-400 toast-timer"></div>
                </div>
            </div>
        </div>
    @endif

    <div class="pt-24 pb-10 px-4 sm:px-6">
        <div class="max-w-2xl mx-auto">

            <div
                class="bg-white rounded-[2rem] shadow-xl shadow-purple-100/80 p-1 mb-8 border border-purple-50 transform hover:scale-[1.01] transition-transform duration-300">
                <form action="{{ route('todos.store') }}" method="POST" class="p-5">
                    @csrf
                    <div class="flex flex-col gap-4">
                        <div>
                            <label class="sr-only">New Task</label>
                            <input type="text" name="title" required placeholder="Apa target kamu hari ini?"
                                class="w-full text-lg font-bold text-gray-700 placeholder-gray-300 bg-transparent border-none focus:ring-0 px-0 py-2">
                            <div class="h-0.5 w-full bg-gradient-to-r from-gray-100 via-gray-100 to-transparent"></div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                            <input type="text" name="description" placeholder="Catatan tambahan..."
                                class="flex-1 bg-gray-50 text-sm text-gray-600 rounded-xl px-4 py-3 border-none focus:ring-2 focus:ring-purple-100 transition-all">

                            <button type="submit"
                                class="bg-gray-900 hover:bg-gray-800 text-white font-semibold rounded-xl px-6 py-3 shadow-lg shadow-gray-200 active:scale-95 transition-all flex items-center justify-center gap-2 group">
                                <span>Add</span>
                                <i class="fa-solid fa-plus text-xs group-hover:rotate-90 transition-transform"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="flex items-center justify-between mb-4 px-2">
                <h2 class="text-xl font-bold text-gray-800">Your Tasks</h2>
                <span class="text-xs font-semibold bg-purple-100 text-purple-600 px-3 py-1 rounded-full">
                    {{ $todos->where('is_completed', false)->count() }} Pending
                </span>
            </div>

            <div class="space-y-4 pb-20">
                @forelse ($todos as $todo)
                    <div
                        class="group relative flex items-start gap-4 p-5 rounded-3xl bg-white border border-gray-100 transition-all duration-300
                        {{ $todo->is_completed
                            ? 'opacity-60 bg-gray-50'
                            : 'shadow-md shadow-indigo-50 hover:shadow-lg hover:shadow-indigo-100 hover:-translate-y-1' }}">

                        <form action="{{ route('home.todos.update', $todo->id) }}" method="POST" class="pt-1"> @csrf
                            @method('PUT') <input type="hidden" name="is_completed"
                                value="{{ $todo->is_completed ? 0 : 1 }}"> <button type="submit"
                                class="w-6 h-6 rounded-full border-2 flex items-center justify-center check-anim {{ $todo->is_completed ? 'bg-green-400 border-green-400 text-white' : 'border-gray-300 text-transparent hover:border-purple-400' }}">
                                <i class="fa-solid fa-check text-[10px]"></i> </button> </form>

                        <div class="flex-1 min-w-0">
                            <h3
                                class="font-bold text-gray-800 text-base sm:text-lg leading-tight truncate pr-16 sm:pr-0
                                {{ $todo->is_completed ? 'line-through text-gray-400 decoration-2 decoration-purple-300' : '' }}">
                                {{ $todo->title }}
                            </h3>
                            @if ($todo->description)
                                <p
                                    class="text-xs sm:text-sm mt-1 truncate 
                                    {{ $todo->is_completed ? 'text-gray-300' : 'text-gray-500' }}">
                                    {{ $todo->description }}
                                </p>
                            @endif
                            <p class="text-[10px] text-gray-300 mt-2 font-medium">
                                {{ $todo->created_at->diffForHumans() }}
                            </p>
                        </div>

                        <div class="absolute top-4 right-4 sm:relative sm:top-0 sm:right-0 flex items-center gap-2">
                            <a href="{{ route('todos.edit', $todo->id) }}"
                                class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-300 hover:bg-blue-50 hover:text-blue-500 transition-colors">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>

                            <form action="{{ route('todos.destroy', $todo->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-300 hover:bg-red-50 hover:text-red-500 transition-colors">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                @empty
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="relative w-24 h-24 mb-4">
                            <div class="absolute inset-0 bg-purple-100 rounded-full animate-ping opacity-20"></div>
                            <div
                                class="relative bg-white p-6 rounded-full shadow-sm flex items-center justify-center w-full h-full border border-purple-50">
                                <span class="text-4xl">🌤️</span>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-gray-700">Hari yang cerah!</h3>
                        <p class="text-gray-400 text-sm mt-1 max-w-xs mx-auto">
                            Belum ada tugas. Nikmati waktu santaimu atau mulai produktif sekarang!
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="fixed bottom-0 w-full py-3 bg-white/60 backdrop-blur-sm border-t border-white/50 text-center z-40">
        <p class="text-[10px] text-gray-400 font-medium tracking-wide uppercase">
            Made with <i class="fa-solid fa-heart text-red-400 mx-0.5"></i> using Laravel Blade
        </p>
    </div>

    <script>
        function closeToast() {
            const toast = document.getElementById('toast-box');
            if (toast) {
                toast.classList.remove('toast-enter'); // Hapus class masuk
                toast.classList.add('toast-exit'); // Tambah class keluar
                setTimeout(() => {
                    toast.remove();
                }, 400); // Sesuaikan dengan durasi animasi CSS (0.4s)
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const toast = document.getElementById('toast-box');
            if (toast) {
                // Auto close setelah 3 detik (sama dengan durasi bar progress)
                setTimeout(() => {
                    closeToast();
                }, 3000);
            }
        });
    </script>

</body>

</html>
