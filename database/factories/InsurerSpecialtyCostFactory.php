<?php

namespace Database\Factories;

use App\Models\Insurer;
use App\Models\Specialty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InsurerSpecialtyCost>
 */
class InsurerSpecialtyCostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'insurer_id' => Insurer::inRandomOrder()->value('id'),
            'specialty_id' => Specialty::inRandomOrder()->value('id'),
            'percent_cost' => $this->faker->randomNumber(5),
        ];
    }
}
