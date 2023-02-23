<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrencyFactory extends Factory
{
    protected $model = Currency::class;

    public function definition(): array
    {
        return [
            'external_id' => '',
            'name' => '',
            'short_name' => '',
            'symbol' => '',
            'exchange_rate_to_rub' => 1.00,
            'image_link' => '',
        ];
    }

    public function usd(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'external_id' => '5696686a4bdc2da3298b456a',
            'name' => 'Dollars',
            'short_name' => 'USD',
            'symbol' => '$',
            'exchange_rate_to_rub' => 110.00,
            'image_link' => 'https://assets.tarkov.dev/5696686a4bdc2da3298b456a-image.webp'
        ]);
    }

    public function eur(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'external_id' => '569668774bdc2da2298b4568',
            'name' => 'Euros',
            'short_name' => 'EUR',
            'symbol' => '€',
            'exchange_rate_to_rub' => 130.00,
            'image_link' => 'https://assets.tarkov.dev/569668774bdc2da2298b4568-image.webp'
        ]);
    }

    public function rub(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'external_id' => '5449016a4bdc2d6f028b456f',
            'name' => 'Roubles',
            'short_name' => 'RUB',
            'symbol' => '₽',
            'exchange_rate_to_rub' => 1.00,
            'image_link' => 'https://assets.tarkov.dev/5449016a4bdc2d6f028b456f-image.webp'
        ]);
    }
}
