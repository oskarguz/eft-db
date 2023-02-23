<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        Currency::factory()->usd()->create();
        Currency::factory()->eur()->create();
        Currency::factory()->rub()->create();
    }
}
