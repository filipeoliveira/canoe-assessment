<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company as Company;
use App\Models\Fund as Fund;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FundCompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fund_id' => function () {
                return Fund::inRandomOrder()->first()->id;
            },
            'company_id' => function () {
                return Company::inRandomOrder()->first()->id;
            },
        ];
    }
}
