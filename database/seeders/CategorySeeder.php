<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Grocery',
                'image' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'name' => 'Fuel',
                'image' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
        ];

        foreach ($categories as $category) {
            if (!Category::where('name', $category['name'])->exists()) {
                Category::create($category);
            }
        }

        if (Category::where('name', $category['name'])->exists()) {
            $this->command->info("Category already exist in database.");
        }
    }
}

