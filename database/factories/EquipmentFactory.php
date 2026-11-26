<?php

namespace Database\Factories;

use App\Models\Equipment;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Equipment>
 */
class EquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'brand' => fake()->company(),
            'condition_description' => fake()->sentence(),
            'responsible_person_id' => Person::factory(),
        ];
    }
}
