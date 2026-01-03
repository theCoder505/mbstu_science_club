<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WebSettings>
 */
class WebSettingsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'president_name' => $this->faker->name,
            'president_sign' => $this->faker->word,
            'facebook_link' => $this->faker->url,
            'twitter_link' => $this->faker->url,
            'instagram_link' => $this->faker->url,
            'linkedin_link' => $this->faker->url,
            'contact_email' => $this->faker->email,
            'phone_number' => $this->faker->phoneNumber,
            'about_us' => $this->faker->paragraph,
            'privacy_policy' => $this->faker->text,
            'terms_conditions' => $this->faker->text,
        ];
    }
}