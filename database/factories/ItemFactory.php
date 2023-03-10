<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        $name = Str::random(25);
        return [
            'external_id' => fake()->uuid(),
            'name' => $name,
            'name_normalized' => Str::slug($name),
            'short_name' => Str::random(10),
            'description' => fake()->text(),
            'icon_link' => 'https://assets.tarkov.dev/5448be9a4bdc2dfd2f8b456a-icon.webp',
            'img_link' => 'https://static.wikia.nocookie.net/escapefromtarkov_gamepedia/images/e/e3/Rgd5.png/revision/latest?cb=20201001185254',
            'source' => 'Tarkov.dev', // @TODO add SourceTypeEnum,
            'height' => 1,
            'width' => 1,
            'can_be_sold_on_flea_market' => true,
        ];
    }
}
