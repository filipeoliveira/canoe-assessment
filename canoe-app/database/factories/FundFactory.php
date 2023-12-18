<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\FundManager as FundManager;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FundFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->currencyCode . fake()->randomNumber(2),
            'start_year' => fake()->numberBetween(2000, 2023),
            'manager_id' => function () {
                return FundManager::inRandomOrder()->first()->id;
            },
        ];
    }
}
