<?php

namespace Database\Factories;

use App\Models\Theme;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invitation>
 */
class InvitationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'theme_id' => Theme::factory(),
            'title' => $this->faker->sentence(3),
            'slug' => $this->faker->unique()->slug(),

            // Couple
            'groom_name' => $this->faker->firstName('male'),
            'groom_nickname' => $this->faker->firstName('male'),
            'groom_father' => $this->faker->name('male'),
            'groom_mother' => $this->faker->name('female'),
            'groom_photo' => null,

            'bride_name' => $this->faker->firstName('female'),
            'bride_nickname' => $this->faker->firstName('female'),
            'bride_father' => $this->faker->name('male'),
            'bride_mother' => $this->faker->name('female'),
            'bride_photo' => null,

            // Events
            'akad_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'akad_time' => '08:00',
            'akad_venue' => $this->faker->city().' Hall',
            'akad_address' => $this->faker->address(),
            'akad_maps_link' => 'https://maps.google.com',

            'resepsi_date' => $this->faker->dateTimeBetween('+1 year', '+2 years'),
            'resepsi_time' => '11:00',
            'resepsi_venue' => $this->faker->city().' Hotel',
            'resepsi_address' => $this->faker->address(),
            'resepsi_maps_link' => 'https://maps.google.com',

            // Settings
            'is_published' => true,
            'enable_rsvp' => true,
            'enable_wishes' => true,
            'enable_gallery' => true,
        ];
    }
}
