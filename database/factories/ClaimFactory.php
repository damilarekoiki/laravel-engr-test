<?php

namespace Database\Factories;

use App\Enums\BatchingDateTypeEnum;
use App\Enums\ClaimPriorityEnum;
use App\Models\Insurer;
use App\Models\Provider;
use App\Models\Specialty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Claim>
 */
class ClaimFactory extends Factory
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
            'provider_id' => Provider::inRandomOrder()->value('id'),
            'priority_level' => fake()->randomElement(ClaimPriorityEnum::cases())->value,
            'encounter_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'submission_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'total_amount' => fake()->numberBetween(100, 2500),
        ];
    }
}
