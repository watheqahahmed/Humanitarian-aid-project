<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DonationFactory extends Factory
{
    public function definition(): array
    {
        $types = ['food', 'clothing', 'medical', 'other'];
        $statuses = ['pending', 'received', 'distributed'];

        return [
            'donor_name' => fake()->name(),
            'type' => $types[array_rand($types)],
            'quantity' => fake()->numberBetween(1, 100),
            'status' => $statuses[array_rand($statuses)],
        ];
    }
}
