<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key checks to prevent issues with deletions
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data
        DB::table('users')->truncate(); // Clear users last
        DB::table('system_settings')->truncate(); // Clear users last

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Call seeders
        $this->call([
            UserSeeder::class,
            SystemSettingSeeder::class,
            CategorySeeder::class,
            ShopSeeder::class,
            SubCategorySeeder::class,
            TaxSeeder::class,
            CmsSeeder::class,
            DynamicPageSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
