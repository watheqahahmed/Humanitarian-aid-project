<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['new_request', 'status_update', 'new_message', 'reminder'];
        $statuses = ['read', 'unread'];

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'message' => fake()->sentence(),
            'type' => $types[array_rand($types)],
            'status' => $statuses[array_rand($statuses)],
        ];
    }
}
