<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // === Minuman (Hot/Cold Drinks) ===
            ['name' => 'Kopi Kapal Api', 'category_id' => 1, 'price' => 2000, 'stock' => 50, 'servings' => [
                ['name' => 'Panas', 'price' => 2500],
                ['name' => 'Dingin', 'price' => 2500]
            ]],
            ['name' => 'Kopi ABC Susu', 'category_id' => 1, 'price' => 2500, 'stock' => 50, 'servings' => [
                ['name' => 'Panas', 'price' => 2500],
                ['name' => 'Dingin', 'price' => 2000]
            ]],
            ['name' => 'White Coffee', 'category_id' => 1, 'price' => 3000, 'stock' => 50, 'servings' => [
                ['name' => 'Panas', 'price' => 2500],
                ['name' => 'Dingin', 'price' => 2000]
            ]],
            ['name' => 'Energen Coklat', 'category_id' => 1, 'price' => 2500, 'stock' => 50, 'servings' => [
                ['name' => 'Panas', 'price' => 2500]
            ]],
            ['name' => 'Energen Kacang Hijau', 'category_id' => 1, 'price' => 2500, 'stock' => 50, 'servings' => [
                ['name' => 'Panas', 'price' => 2500]
            ]],
            ['name' => 'Teh Pucuk Harum', 'category_id' => 1, 'price' => 3000, 'stock' => 50, 'servings' => [
                ['name' => 'Dingin', 'price' => 3500]
            ]],
            ['name' => 'Teh Botol Sosro', 'category_id' => 1, 'price' => 4000, 'stock' => 50, 'servings' => [
                ['name' => 'Dingin', 'price' => 5000]
            ]],
            ['name' => 'Aqua Botol 600ml', 'category_id' => 1, 'price' => 4000, 'stock' => 50, 'servings' => [
                ['name' => 'Tidak Dimodifikasi', 'price' => 5000]
            ]],
            ['name' => 'Le Minerale 600ml', 'category_id' => 1, 'price' => 4000, 'stock' => 50, 'servings' => [
                ['name' => 'Tidak Dimodifikasi', 'price' => 5000]
            ]],
            ['name' => 'Susu Ultra Putih', 'category_id' => 1, 'price' => 6000, 'stock' => 50, 'servings' => [
                ['name' => 'Dingin', 'price' => 2500]
            ]],

            // === Makanan ===
            ['name' => 'Indomie Goreng', 'category_id' => 2, 'price' => 7000, 'stock' => 50, 'servings' => [
                ['name' => 'Dimasak', 'price' => 2000]
            ]],
            ['name' => 'Indomie Kari Ayam', 'category_id' => 2, 'price' => 7000, 'stock' => 50, 'servings' => [
                ['name' => 'Dimasak', 'price' => 2000]
            ]],
            ['name' => 'Pop Mie Ayam', 'category_id' => 2, 'price' => 8000, 'stock' => 50, 'servings' => [
                ['name' => 'Hangat', 'price' => 0]
            ]],
            ['name' => 'Nasi Uduk Bungkus', 'category_id' => 2, 'price' => 10000, 'stock' => 50, 'servings' => [
                ['name' => 'Siap Santap', 'price' => 0]
            ]],

            // === Gorengan ===
            ['name' => 'Tahu Isi', 'category_id' => 3, 'price' => 2500, 'stock' => 100, 'servings' => [
                ['name' => 'Hangat', 'price' => 0]
            ]],
            ['name' => 'Tempe Goreng', 'category_id' => 3, 'price' => 2000, 'stock' => 100, 'servings' => [
                ['name' => 'Hangat', 'price' => 0]
            ]],
            ['name' => 'Pisang Goreng', 'category_id' => 3, 'price' => 3000, 'stock' => 100, 'servings' => [
                ['name' => 'Hangat', 'price' => 0]
            ]],

            // === Snack Kecil ===
            ['name' => 'Chitato Sapi Panggang', 'category_id' => 4, 'price' => 8000, 'stock' => 50, 'servings' => [
                ['name' => 'Tidak Dimodifikasi', 'price' => 0]
            ]],
            ['name' => 'Qtela Singkong Balado', 'category_id' => 4, 'price' => 7000, 'stock' => 50, 'servings' => [
                ['name' => 'Tidak Dimodifikasi', 'price' => 0]
            ]],
            ['name' => 'Taro Net Seaweed', 'category_id' => 4, 'price' => 7000, 'stock' => 50, 'servings' => [
                ['name' => 'Tidak Dimodifikasi', 'price' => 0]
            ]],

            // === Snack Besar ===
            ['name' => 'SilverQueen Chunky Bar', 'category_id' => 5, 'price' => 12000, 'stock' => 50, 'servings' => [
                ['name' => 'Tidak Dimodifikasi', 'price' => 0]
            ]],
            ['name' => 'KitKat 2 Fingers', 'category_id' => 5, 'price' => 6000, 'stock' => 50, 'servings' => [
                ['name' => 'Tidak Dimodifikasi', 'price' => 0]
            ]],
            ['name' => 'Tango Wafer Vanilla', 'category_id' => 5, 'price' => 5000, 'stock' => 50, 'servings' => [
                ['name' => 'Tidak Dimodifikasi', 'price' => 0]
            ]]
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
