@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6 text-green-700">Tambah Produk Baru</h1>

        <form action="{{ route('products.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block font-semibold mb-1">Nama Produk</label>
                <input type="text" name="name" id="name" class="w-full border rounded px-3 py-2"
                    value="{{ old('name') }}" required>
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="category_id" class="block font-semibold mb-1">Kategori</label>
                <select name="category_id" id="category_id" class="w-full border rounded px-3 py-2" required>
                    <option value="" disabled selected>Pilih kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="price" class="block font-semibold mb-1">Harga Utama (opsional jika ada penyajian)</label>
                <input type="number" name="price" id="price" min="0" class="w-full border rounded px-3 py-2"
                    value="{{ old('price') }}">
                @error('price')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="stock" class="block font-semibold mb-1">Stok</label>
                <input type="number" name="stock" id="stock" min="0" class="w-full border rounded px-3 py-2"
                    value="{{ old('stock', 0) }}" required>
                @error('stock')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block font-semibold mb-1">Penyajian</label>
                <div id="servings-container">
                    @if (old('servings'))
                        @foreach (old('servings') as $index => $serving)
                            <div class="serving-item mb-3 p-3 border rounded">
                                <div class="flex gap-3 mb-2">
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium mb-1">Nama Penyajian</label>
                                        <input type="text" name="servings[{{ $index }}][name]"
                                            class="w-full border rounded px-3 py-2" value="{{ $serving['name'] }}"
                                            required>
                                        @error('servings.' . $index . '.name')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium mb-1">Harga</label>
                                        <input type="number" name="servings[{{ $index }}][price]"
                                            class="w-full border rounded px-3 py-2" value="{{ $serving['price'] }}"
                                            min="0" required>
                                        @error('servings.' . $index . '.price')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <button type="button" class="remove-serving text-red-600 text-sm hover:text-red-800">
                                    Hapus Penyajian
                                </button>
                            </div>
                        @endforeach
                    @endif
                </div>
                <button type="button" id="add-serving" class="mt-2 text-green-600 hover:text-green-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Tambah Penyajian
                </button>
                @error('servings')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                    Simpan
                </button>
                <a href="{{ route('products.index') }}" class="px-6 py-2 border border-gray-300 rounded hover:bg-gray-100">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('servings-container');
            const addButton = document.getElementById('add-serving');
            let servingCount = container.children.length;

            // Add new serving
            addButton.addEventListener('click', function() {
                const div = document.createElement('div');
                div.className = 'serving-item mb-3 p-3 border rounded';
                div.innerHTML = `
                    <div class="flex gap-3 mb-2">
                        <div class="flex-1">
                            <label class="block text-sm font-medium mb-1">Nama Penyajian</label>
                            <input type="text" name="servings[${servingCount}][name]"
                                   class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium mb-1">Harga</label>
                            <input type="number" name="servings[${servingCount}][price]"
                                   class="w-full border rounded px-3 py-2" min="0" required>
                        </div>
                    </div>
                    <button type="button" class="remove-serving text-red-600 text-sm hover:text-red-800">
                        Hapus Penyajian
                    </button>
                `;

                container.appendChild(div);
                servingCount++;
            });

            // Remove serving
            container.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-serving')) {
                    e.target.closest('.serving-item').remove();
                    // Reindex remaining servings
                    const items = container.querySelectorAll('.serving-item');
                    items.forEach((item, index) => {
                        const nameInput = item.querySelector('input[name*="[name]"]');
                        const priceInput = item.querySelector('input[name*="[price]"]');
                        nameInput.name = `servings[${index}][name]`;
                        priceInput.name = `servings[${index}][price]`;
                    });
                    servingCount = items.length;
                }
            });
        });
    </script>

    <style>
        .serving-item {
            background-color: #f9fafb;
            transition: all 0.2s;
        }

        .serving-item:hover {
            background-color: #f3f4f6;
        }
    </style>
@endsection
