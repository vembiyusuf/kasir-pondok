<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Minuman'],
            ['name' => 'Makanan'],
            ['name' => 'Gorengan'],
            ['name' => 'Snack Kecil'],
            ['name' => 'Snack Besar'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
