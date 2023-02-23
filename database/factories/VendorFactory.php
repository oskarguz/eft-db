<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition(): array
    {
        return [
            'external_id' => '',
            'name' => '',
            'normalized_name' => '',
            'image_link' => '',
            'description' => ''
        ];
    }

    public function prapor(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'external_id' => '54cb50c76803fa8b248b4571',
            'name' => 'Prapor',
            'normalized_name' => 'prapor',
            'image_link' => 'https://assets.tarkov.dev/54cb50c76803fa8b248b4571.webp',
            'description' => 'The Warrant officer in charge of supply warehouses on the sustaining base enforcing the Norvinsk region blockade. Secretly supplied the BEAR PMC operators with weapons, ammunition, and various other provisions he had at his disposal during the Contract Wars.'
        ]);
    }

    public function therapist(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'external_id' => '54cb57776803fa99248b456e',
            'name' => 'Therapist',
            'normalized_name' => 'therapist',
            'image_link' => 'https://assets.tarkov.dev/54cb57776803fa99248b456e.webp',
            'description' => 'Head of the Trauma Care Department of the Tarkov Central City Hospital.'
        ]);
    }

    public function fence(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'external_id' => '579dc571d53a0658a154fbec',
            'name' => 'Fence',
            'normalized_name' => 'fence',
            'image_link' => 'https://assets.tarkov.dev/54cb57776803fa99248b456e.webp',
            'description' => 'The conflict had barely started when Fence began setting up anonymous outlets for buying and selling goods. Keeping incognito, he nevertheless managed to put together a well-organised smuggler network, operating all over the Norvinsk region.'
        ]);
    }

    public function skier(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'external_id' => '58330581ace78e27b8b10cee',
            'name' => 'Skier',
            'normalized_name' => 'skier',
            'image_link' => 'https://assets.tarkov.dev/58330581ace78e27b8b10cee.webp',
            'description' => 'Previously a port zone customs terminal employee, who initially oversaw dealings of the terminal\'s goods. Over the course of the conflict, he put together a gang of thugs in order to grab everything of value that he could lay his hands on in the vicinity of the terminal.'
        ]);
    }

    public function peacekeeper(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'external_id' => '5935c25fb3acc3127c3d8cd9',
            'name' => 'Peacekeeper',
            'normalized_name' => 'peacekeeper',
            'image_link' => 'https://assets.tarkov.dev/5935c25fb3acc3127c3d8cd9.webp',
            'description' => 'UN peacekeeping Force supply officer, based in one of the central checkpoints leading to the Tarkov port zone. The blue helmets have been seen poking their heads into small deals from the very beginning of the conflict, buying everything of value in exchange for western weapons, ammo, and all kinds of military equipment.'
        ]);
    }

    public function mechanic(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'external_id' => '5a7c2eca46aef81a7ca2145d',
            'name' => 'Mechanic',
            'normalized_name' => 'mechanic',
            'image_link' => 'https://assets.tarkov.dev/5a7c2eca46aef81a7ca2145d.webp',
            'description' => 'A former chemical plant foreman, who from the very beginning of the conflict took to weapon modification, repairs, and maintenance of complex equipment and technology. He prefers clandestine solo living and operates discreetly, while placing complicated and challenging tasks above all else.',
        ]);
    }

    public function ragman(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'external_id' => '5ac3b934156ae10c4430e83c',
            'name' => 'Ragman',
            'normalized_name' => 'ragman',
            'image_link' => 'https://assets.tarkov.dev/5ac3b934156ae10c4430e83c.webp',
            'description' => 'Previously, he worked as a director in a shopping center located in the suburbs of Tarkov. Now dealing in mostly clothing- and gear-related items, anywhere from sunglasses to body armor.',
        ]);
    }

    public function fleaMarket(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'external_id' => '',
            'name' => 'FleaMarket',
            'normalized_name' => 'flea-market',
            'image_link' => '',
            'description' => '',
        ]);
    }
}
