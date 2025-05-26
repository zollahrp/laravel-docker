<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WebProfile;

class WebProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (WebProfile::count() === 0) {
            WebProfile::create([
                'logo' => 'default-logo.png',
                'site_name' => 'My Awesome Site',
                'email' => 'admin@example.com',
                'phone' => '+6281234567890',
                'address' => 'Jl. Contoh No. 123, Jakarta, Indonesia',
                'facebook' => 'https://facebook.com/example',
                'instagram' => 'https://instagram.com/example',
                'twitter' => 'https://twitter.com/example',
            ]);
        }
    }
}