<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        Vendor::factory()->prapor()->create();
        Vendor::factory()->therapist()->create();
        Vendor::factory()->fence()->create();
        Vendor::factory()->skier()->create();
        Vendor::factory()->peacekeeper()->create();
        Vendor::factory()->mechanic()->create();
        Vendor::factory()->ragman()->create();
        Vendor::factory()->fleaMarket()->create();
    }
}
