<?php

namespace Database\Factories;

use App\Enums\ClaimPriorityEnum;
use App\Models\Insurer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InsurerPriorityCost>
 */
class InsurerPriorityCostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'insurer_id' => Insurer::inRandomOrder()->value('id'),
            'priority_level' => fake()->randomElement(ClaimPriorityEnum::cases())->value,
            'percent_cost' => $this->faker->randomNumber(3),
        ];
    }
}
