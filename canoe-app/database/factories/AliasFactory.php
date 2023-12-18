<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Fund as Fund;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AliasFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => strtoupper(fake()->word()) . ' '. fake()->currencyCode(),
            'fund_id' => function ()
            {
                return Fund::inRandomOrder()->first()->id;
            },
        ];
    }
}
