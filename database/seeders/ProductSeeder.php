<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // === Minuman ===
            ['name' => 'Kopi Kapal Api', 'category_id' => 1, 'price' => 5000, 'stock' => 50, 'servings' => [['name' => 'Panas', 'price' => 2500], ['name' => 'Dingin', 'price' => 2000]]],
            ['name' => 'Energen Coklat', 'category_id' => 1, 'price' => 4000, 'stock' => 50, 'servings' => [['name' => 'Panas', 'price' => 2500]]],
            ['name' => 'Energen Kacang Hijau', 'category_id' => 1, 'price' => 4000, 'stock' => 50, 'servings' => [['name' => 'Panas', 'price' => 2500]]],
            ['name' => 'Teh Pucuk Harum', 'category_id' => 1, 'price' => 6000, 'stock' => 50, 'servings' => [['name' => 'Dingin', 'price' => 0]]],
            ['name' => 'Aqua Botol 600ml', 'category_id' => 1, 'price' => 5000, 'stock' => 50, 'servings' => [['name' => 'Tidak Dimodifikasi', 'price' => 0]]],
            ['name' => 'Le Minerale 600ml', 'category_id' => 1, 'price' => 5000, 'stock' => 50, 'servings' => [['name' => 'Tidak Dimodifikasi', 'price' => 0]]],
            ['name' => 'Susu Ultra Putih', 'category_id' => 1, 'price' => 7000, 'stock' => 50, 'servings' => [['name' => 'Dingin', 'price' => 2000]]],
            ['name' => 'Susu Ultra Coklat', 'category_id' => 1, 'price' => 7000, 'stock' => 50, 'servings' => [['name' => 'Dingin', 'price' => 2000]]],
            ['name' => 'Good Day Cappuccino', 'category_id' => 1, 'price' => 8000, 'stock' => 50, 'servings' => [['name' => 'Panas', 'price' => 2500]]],
            ['name' => 'Good Day Freeze', 'category_id' => 1, 'price' => 9000, 'stock' => 50, 'servings' => [['name' => 'Dingin', 'price' => 2000]]],

            // === Makanan ===
            ['name' => 'Indomie Goreng', 'category_id' => 2, 'price' => 7000, 'stock' => 50, 'servings' => [['name' => 'Dimasak', 'price' => 0]]],
            ['name' => 'Indomie Kari Ayam', 'category_id' => 2, 'price' => 7000, 'stock' => 50, 'servings' => [['name' => 'Dimasak', 'price' => 0]]],
            ['name' => 'Pop Mie Ayam', 'category_id' => 2, 'price' => 8000, 'stock' => 50, 'servings' => [['name' => 'Hangat', 'price' => 0]]],
            ['name' => 'Pop Mie Baso', 'category_id' => 2, 'price' => 8000, 'stock' => 50, 'servings' => [['name' => 'Hangat', 'price' => 0]]],
            ['name' => 'Nasi Uduk Bungkus', 'category_id' => 2, 'price' => 10000, 'stock' => 50, 'servings' => [['name' => 'Siap Santap', 'price' => 0]]],
            ['name' => 'Nasi Kuning Bungkus', 'category_id' => 2, 'price' => 10000, 'stock' => 50, 'servings' => [['name' => 'Siap Santap', 'price' => 0]]],

            // === Gorengan ===
            ['name' => 'Tahu Isi', 'category_id' => 3, 'price' => 2000, 'stock' => 100, 'servings' => [['name' => 'Hangat', 'price' => 0]]],
            ['name' => 'Tempe Goreng', 'category_id' => 3, 'price' => 2000, 'stock' => 100, 'servings' => [['name' => 'Hangat', 'price' => 0]]],
            ['name' => 'Pisang Goreng', 'category_id' => 3, 'price' => 3000, 'stock' => 100, 'servings' => [['name' => 'Hangat', 'price' => 0]]],
            ['name' => 'Bakwan Sayur', 'category_id' => 3, 'price' => 2000, 'stock' => 100, 'servings' => [['name' => 'Hangat', 'price' => 0]]],
            ['name' => 'Cireng', 'category_id' => 3, 'price' => 2000, 'stock' => 100, 'servings' => [['name' => 'Hangat', 'price' => 0]]],
            ['name' => 'Gehu Pedas', 'category_id' => 3, 'price' => 2000, 'stock' => 100, 'servings' => [['name' => 'Hangat', 'price' => 0]]],

            // === Snack Kecil ===
            ['name' => 'Chitato Sapi Panggang', 'category_id' => 4, 'price' => 8000, 'stock' => 50, 'servings' => [['name' => 'Tidak Dimodifikasi', 'price' => 0]]],
            ['name' => 'Qtela Singkong Balado', 'category_id' => 4, 'price' => 7000, 'stock' => 50, 'servings' => [['name' => 'Tidak Dimodifikasi', 'price' => 0]]],
            ['name' => 'Taro Net Seaweed', 'category_id' => 4, 'price' => 7000, 'stock' => 50, 'servings' => [['name' => 'Tidak Dimodifikasi', 'price' => 0]]],
            ['name' => 'Lays Rumput Laut', 'category_id' => 4, 'price' => 8000, 'stock' => 50, 'servings' => [['name' => 'Tidak Dimodifikasi', 'price' => 0]]],
            ['name' => 'Tic Tac Rasa Keju', 'category_id' => 4, 'price' => 5000, 'stock' => 50, 'servings' => [['name' => 'Tidak Dimodifikasi', 'price' => 0]]],
            ['name' => 'Oreo Vanilla', 'category_id' => 4, 'price' => 5000, 'stock' => 50, 'servings' => [['name' => 'Tidak Dimodifikasi', 'price' => 0]]],

            // === Snack Besar ===
            ['name' => 'Good Time Cookies', 'category_id' => 5, 'price' => 5000, 'stock' => 50, 'servings' => [['name' => 'Tidak Dimodifikasi', 'price' => 0]]],
            ['name' => 'Beng Beng', 'category_id' => 5, 'price' => 2000, 'stock' => 50, 'servings' => [['name' => 'Tidak Dimodifikasi', 'price' => 0]]],
            ['name' => 'SilverQueen Chunky Bar', 'category_id' => 5, 'price' => 12000, 'stock' => 50, 'servings' => [['name' => 'Tidak Dimodifikasi', 'price' => 0]]],
            ['name' => 'KitKat 2 Fingers', 'category_id' => 5, 'price' => 6000, 'stock' => 50, 'servings' => [['name' => 'Tidak Dimodifikasi', 'price' => 0]]],
            ['name' => 'Tango Wafer Vanilla', 'category_id' => 5, 'price' => 5000, 'stock' => 50, 'servings' => [['name' => 'Tidak Dimodifikasi', 'price' => 0]]],
            ['name' => 'Delfi Chacha', 'category_id' => 5, 'price' => 4000, 'stock' => 50, 'servings' => [['name' => 'Tidak Dimodifikasi', 'price' => 0]]],

            // 4 tambahan untuk genap 40
            ['name' => 'Roma Kelapa', 'category_id' => 5, 'price' => 5000, 'stock' => 50, 'servings' => [['name' => 'Tidak Dimodifikasi', 'price' => 0]]],
            ['name' => 'Nabati Wafer Coklat', 'category_id' => 5, 'price' => 5000, 'stock' => 50, 'servings' => [['name' => 'Tidak Dimodifikasi', 'price' => 0]]],
            ['name' => 'Nextar Pineapple', 'category_id' => 5, 'price' => 4000, 'stock' => 50, 'servings' => [['name' => 'Tidak Dimodifikasi', 'price' => 0]]],
            ['name' => 'Astor Wafer Stick', 'category_id' => 5, 'price' => 5000, 'stock' => 50, 'servings' => [['name' => 'Tidak Dimodifikasi', 'price' => 0]]],
        ];

        foreach ($products as $product) {
            Product::create([
                'name' => $product['name'],
                'category_id' => $product['category_id'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'servings' => json_encode($product['servings']),
            ]);
        }
    }
}
