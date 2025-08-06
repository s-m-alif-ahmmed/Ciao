<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Shop;
use App\Models\SubCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcategories = [

            //Sarah mega shop
            [
                'category_id' => Category::where('name', 'Grocery')->value('id'),
                'name' => 'Fruits',
                'image' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'category_id' => Category::where('name', 'Fuel')->value('id'),
                'name' => 'Diesel',
                'image' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],

            //Bashundhara city
            [
                'category_id' => Category::where('name', 'Grocery')->value('id'),
                'name' => 'Vegetables',
                'image' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'category_id' => Category::where('name', 'Fuel')->value('id'),
                'name' => 'Petrol',
                'image' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            // Add more subcategories as needed
        ];

        foreach ($subcategories as $subcategory) {
            if (!SubCategory::where('name', $subcategory['name'])->exists()) {
                SubCategory::create($subcategory);
            }
        }
    }
}

