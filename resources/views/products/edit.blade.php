@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6 text-green-700">Edit Produk</h1>

        <form action="{{ route('products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block font-semibold mb-1">Nama Produk</label>
                <input type="text" name="name" id="name" class="w-full border rounded px-3 py-2"
                    value="{{ old('name', $product->name) }}" required>
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="category_id" class="block font-semibold mb-1">Kategori</label>
                <select name="category_id" id="category_id" class="w-full border rounded px-3 py-2" required>
                    <option value="" disabled>Pilih kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="price" class="block font-semibold mb-1">Harga</label>
                <input type="number" name="price" id="price" min="0" class="w-full border rounded px-3 py-2"
                    value="{{ old('price', $product->price) }}" required>
                @error('price')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="stock" class="block font-semibold mb-1">Stok</label>
                <input type="number" name="stock" id="stock" min="0" class="w-full border rounded px-3 py-2"
                    value="{{ old('stock', $product->stock) }}" required>
                @error('stock')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="serving_type" class="block font-semibold mb-1">Tipe Penyajian (opsional)</label>
                <input type="text" name="serving_type" id="serving_type" class="w-full border rounded px-3 py-2"
                    value="{{ old('serving_type', $product->serving_type) }}">
                @error('serving_type')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                Update
            </button>
            <a href="{{ route('products.index') }}"
                class="ml-4 inline-block px-6 py-2 border border-gray-300 rounded hover:bg-gray-100">
                Batal
            </a>
        </form>
    </div>
@endsection
