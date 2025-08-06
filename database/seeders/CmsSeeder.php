<?php

namespace Database\Seeders;

use App\Models\CMS;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (CMS::count() === 0) {
            CMS::insert([
                // Home Banner carousel
                ['id' => 1, 'shop_id' => 1, 'banner_image' => null, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
                ['id' => 2, 'shop_id' => 1, 'banner_image' => null, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
                ['id' => 3, 'shop_id' => 2, 'banner_image' => null, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
                ['id' => 4, 'shop_id' => 4, 'banner_image' => null, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }
}
