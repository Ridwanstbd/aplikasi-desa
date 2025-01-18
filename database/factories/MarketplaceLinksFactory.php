<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MarketplaceLinks>
 */
class MarketplaceLinksFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['Shopee', 'Tokopedia', 'Tiktok', 'Lazada'];

        return [
            'type' => $this->faker->randomElement($types),
            'name' => $this->faker->lexify(str_repeat('?', 20)),
            'marketplace_url' => $this->faker->url,
            'shop_id'=>1
        ];
    }
}
