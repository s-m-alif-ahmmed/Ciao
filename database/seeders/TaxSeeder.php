<?php

namespace Database\Seeders;

use App\Models\Tax;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Tax::where('id', 1)->exists()) {
            Tax::create([
                'id' => 1,
                'tax' => 18,
                'status' => 'active',
            ]);
        }
    }
}
