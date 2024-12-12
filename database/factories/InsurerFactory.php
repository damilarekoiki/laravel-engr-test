<?php

namespace Database\Factories;

use App\Enums\BatchingDateTypeEnum;
use Illuminate\Bus\Batch;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Insurer>
 */
class InsurerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();
        return [
            'name' => $name,
            'code' => Str::slug($name),
            'email' => fake()->email(),
            'minimum_batch_size' => fake()->numberBetween(1, 100),
            'maximum_batch_size' => fake()->numberBetween(101, 140),
            'daily_processing_capacity' => fake()->numberBetween(170, 200),
            'batching_date_type' => fake()->randomElement(BatchingDateTypeEnum::cases())->value,
        ];
    }
}
