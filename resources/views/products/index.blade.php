@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6 text-green-700">Manajemen Produk</h1>

        {{-- Alert pesan sukses --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Button Tambah Produk --}}
        <div class="mb-8">
            <a href="{{ route('products.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                + Tambah Produk
            </a>
        </div>

        {{-- Tabel Produk --}}
        <div class="bg-white shadow rounded p-6">
            <h2 class="text-lg font-semibold mb-4 text-green-700">Daftar Produk</h2>
            <table class="w-full table-auto border-collapse">
                <thead class="bg-green-100">
                    <tr>
                        <th class="border px-4 py-2">#</th>
                        <th class="border px-4 py-2">Nama</th>
                        <th class="border px-4 py-2">Kategori</th>
                        <th class="border px-4 py-2">Harga</th>
                        <th class="border px-4 py-2">Stok</th>
                        <th class="border px-4 py-2">Penyajian</th> {{-- ✅ Tambahan --}}
                        <th class="border px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="border px-4 py-2">{{ $product->name }}</td>
                            <td class="border px-4 py-2">{{ $product->category->name }}</td>
                            <td class="border px-4 py-2">
                                @if ($product->price)
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="border px-4 py-2">{{ $product->stock }}</td>
                            <td class="border px-4 py-2">
                                @if ($product->servings)
                                    <ul class="list-disc list-inside">
                                        @foreach (json_decode($product->servings, true) as $serving)
                                            <li>
                                                {{ $serving['name'] }}:
                                                @if (!empty($serving['price']))
                                                    Rp{{ number_format($serving['price'], 0, ',', '.') }}
                                                @else
                                                    -
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="border px-4 py-2 space-x-2">
                                <a href="{{ route('products.edit', $product) }}"
                                    class="text-green-600 hover:underline">Edit</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Yakin hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-gray-500">Belum ada produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Navigasi halaman --}}
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>



    </div>
    </div>
@endsection
