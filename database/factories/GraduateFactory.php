<?php

namespace Database\Factories;

use App\Models\Career;
use App\Models\Graduate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Graduate>
 */
class GraduateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'carnet' => 'G'.$this->faker->unique()->numberBetween(100000, 999999),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'graduation_year' => $this->faker->year(),
            'photo_path' => null,
            'career_id' => Career::factory(),
        ];
    }
}
