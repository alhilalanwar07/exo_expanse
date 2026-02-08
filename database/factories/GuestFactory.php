<?php

namespace Database\Factories;

use App\Enums\GuestStatus;
use App\Models\Invitation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guest>
 */
class GuestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invitation_id' => Invitation::factory(),
            'name' => fake()->name(),
            'slug' => fake()->slug(),
            'phone_number' => fake()->phoneNumber(),
            'status' => fake()->randomElement([GuestStatus::CONFIRMED, GuestStatus::DECLINED, GuestStatus::PENDING]),
            'pax' => fake()->numberBetween(1, 5),
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => GuestStatus::CONFIRMED,
            'pax' => fake()->numberBetween(1, 5),
        ]);
    }

    public function declined(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => GuestStatus::DECLINED,
            'pax' => 0,
        ]);
    }
}
