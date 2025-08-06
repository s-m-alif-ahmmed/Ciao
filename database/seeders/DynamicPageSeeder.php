<?php

namespace Database\Seeders;

use App\Models\DynamicPage;
use Illuminate\Database\Seeder;

class DynamicPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'id' => 1,
                'page_title' => 'About Us',
                'page_content' => 'This is the about us page',
                'slug' => 'about-us',
                'status' => 'active'
            ],
            [
                'id' => 2,
                'page_title' => 'Terms and Conditions',
                'page_content' => 'This is the Terms & Conditions page',
                'slug' => 'terms-and-conditions',
                'status' => 'active'
            ],
            [
                'id' => 3,
                'page_title' => 'Privacy Policy',
                'page_content' => 'This is the privacy policy page',
                'slug' => 'privacy-policy',
                'status' => 'active'
            ],
        ];

        foreach ($pages as $page) {
            if (!DynamicPage::where('id', $page['id'])->exists()) {
                DynamicPage::create($page);
            }
        }
    }
}
