<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tugas - FunTodo</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Poppins', sans-serif; }
        
        /* Toggle Switch Animation */
        .toggle-checkbox:checked {
            right: 0;
            border-color: #a855f7;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #a855f7;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 min-h-screen flex items-center justify-center p-4 text-gray-800">

    <div class="w-full max-w-lg bg-white rounded-[2.5rem] shadow-2xl shadow-purple-200/50 border border-white relative overflow-hidden">
        
        <div class="h-2 w-full bg-gradient-to-r from-indigo-400 via-purple-500 to-pink-500"></div>

        <div class="p-8 sm:p-10">
            
            <div class="flex items-center justify-between mb-8">
                <a href="{{ url('/') }}" class="group flex items-center justify-center w-10 h-10 rounded-full bg-gray-50 text-gray-400 hover:bg-purple-100 hover:text-purple-600 transition-all duration-300">
                    <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <h1 class="text-xl font-bold text-gray-800 tracking-tight">Edit Tugas</h1>
                <div class="w-10"></div>
            </div>

            <form action="{{ route('todos.update', $todo->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-2">
                    <label for="title" class="text-sm font-bold text-gray-600 ml-1">Judul Tugas</label>
                    <input type="text" name="title" id="title" required 
                        value="{{ old('title', $todo->title) }}"
                        class="w-full bg-gray-50 text-gray-800 font-semibold rounded-2xl px-5 py-4 border-2 border-transparent focus:border-purple-400 focus:bg-white focus:outline-none transition-all placeholder-gray-300 shadow-sm"
                        placeholder="Apa tugasnya?">
                    @error('title')
                        <p class="text-red-500 text-xs ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="description" class="text-sm font-bold text-gray-600 ml-1">Catatan</label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full bg-gray-50 text-gray-600 text-sm rounded-2xl px-5 py-4 border-2 border-transparent focus:border-purple-400 focus:bg-white focus:outline-none transition-all resize-none shadow-sm"
                        placeholder="Detail tambahan...">{{ old('description', $todo->description) }}</textarea>
                </div>

                <div class="flex items-center justify-between bg-indigo-50/60 p-5 rounded-2xl border border-indigo-100/50">
                    <div class="flex flex-col">
                        <span class="text-sm font-bold text-indigo-900">Sudah Selesai?</span>
                        <span class="text-[10px] text-indigo-400">Geser tombol jika tugas rampung</span>
                    </div>
                    
                    <div class="relative inline-block w-12 align-middle select-none">
                        <input type="hidden" name="is_completed" value="0">
                        <input type="checkbox" name="is_completed" id="toggle" value="1" 
                            class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer transition-all duration-300 shadow-sm"
                            {{ $todo->is_completed ? 'checked' : '' }}
                            style="top: 2px; left: 2px; {{ $todo->is_completed ? 'left: auto; right: 2px; border-color: #a855f7;' : 'border-color: #e5e7eb;' }}"/>
                        <label for="toggle" class="toggle-label block overflow-hidden h-7 rounded-full bg-gray-200 cursor-pointer transition-colors duration-300 {{ $todo->is_completed ? '!bg-purple-500' : '' }}"></label>
                    </div>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="submit" 
                        class="flex-1 bg-gray-900 hover:bg-gray-800 text-white font-bold py-4 rounded-2xl shadow-lg shadow-gray-300/50 active:scale-95 transition-all">
                        Simpan
                    </button>
                </div>
            </form>
            </div>

        <div class="bg-gray-50 py-3 text-center border-t border-gray-100">
            <p class="text-[10px] text-gray-400 font-medium">FunTodo &bull; Edit Mode</p>
        </div>
    </div>

    <script>
        const toggle = document.getElementById('toggle');
        const label = document.querySelector('.toggle-label');

        toggle.addEventListener('change', function() {
            if(this.checked) {
                this.style.left = 'auto';
                this.style.right = '2px';
                this.style.borderColor = '#a855f7';
                label.classList.add('!bg-purple-500');
            } else {
                this.style.right = 'auto';
                this.style.left = '2px';
                this.style.borderColor = '#e5e7eb';
                label.classList.remove('!bg-purple-500');
            }
        });
    </script>
</body>
</html>