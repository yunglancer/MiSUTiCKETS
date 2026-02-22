<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Conciertos', 
            'Deportes', 
            'Teatro', 
            'Festivales', 
            'Stand-up Comedy'
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                'slug' => Str::slug($category),
                'icon' => 'fas fa-star', // Icono genérico de prueba
            ]);
        }
    }
}