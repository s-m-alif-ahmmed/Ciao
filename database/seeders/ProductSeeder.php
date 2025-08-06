<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'category_id' => 1,
                'sub_category_id' => 1,
                'shop_id' => 1,
                'name' => 'Product 1',
                'description' => 'Hello Product 1',
                'thumbnail' => null,
                'price' => '100.24',
                'stock' => 100,
                'quantity' => '1 ltr',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 1,
                'sub_category_id' => 1,
                'shop_id' => 1,
                'name' => 'Product 2',
                'description' => 'Hello Product 2',
                'thumbnail' => null,
                'price' => '50.50',
                'stock' => 50,
                'quantity' => '500 gm',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'sub_category_id' => 3,
                'shop_id' => 1,
                'name' => 'Product 3',
                'description' => 'Hello Product 3',
                'thumbnail' => null,
                'price' => '50.50',
                'stock' => 50,
                'quantity' => '4 pieces',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 2,
                'sub_category_id' => 2,
                'shop_id' => 3,
                'name' => 'Diesel',
                'description' => 'Hello Diesel',
                'thumbnail' => null,
                'price' => '50.50',
                'stock' => 50,
                'quantity' => '1 Litter',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
