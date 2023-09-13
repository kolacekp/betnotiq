<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bet>
 */
class BetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'url' => fake()->url(),
            'value' => fake()->randomFloat(2, 0, 10),
            'user_id' => function () {
                if ($user = User::all()->random(1)->first()) {
                    return $user->id;
                }

                return User::factory()->create()->id;
            },
        ];
    }
}
