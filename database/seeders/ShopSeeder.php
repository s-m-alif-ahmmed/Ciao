<?php

namespace Database\Seeders;

use App\Models\Shop;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shops = [
            [
                'name' => 'Sarah mega shop',
                'stall_number' => '106',
                'location' => 'Gulshan',
                'latitude' => '23.8103',
                'longitude' => '23.8103',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'name' => 'Bashundhara city',
                'stall_number' => '2456',
                'location' => 'Bashundhara',
                'latitude' => '23.8103',
                'longitude' => '23.8103',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'name' => 'Jamuna Future Park',
                'stall_number' => '34',
                'location' => 'Khilkhet',
               'latitude' => '23.8103',
                'longitude' => '23.8103',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'name' => 'New Market',
                'stall_number' => '44564',
                'location' => 'Banasree',
                'latitude' => '23.8103',
                'longitude' => '23.8103',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'name' => 'Gulshan South Avenue',
                'stall_number' => '5564',
                'location' => 'Mohammadpur',
                'latitude' => '23.8103',
                'longitude' => '23.8103',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
        ];

        foreach ($shops as $shop) {
            if (!Shop::where('name', $shop['name'])->exists()) {
                Shop::create($shop);
            }
        }

        if (Shop::where('name', $shop['name'])->exists()) {
            $this->command->info("Shops already exist in database.");
        }
    }
}
