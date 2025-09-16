<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AidRequestFactory extends Factory
{
    public function definition(): array
    {
        $types = ['food', 'clothing', 'medical', 'other'];
        $statuses = ['pending', 'approved', 'denied'];

        return [
            'beneficiary_id' => User::where('role', 'beneficiary')->inRandomOrder()->first()->id,
            'type' => $types[array_rand($types)],
            'description' => fake()->sentence(),
            'document_url' => fake()->imageUrl(640, 480, 'documents'),
            'status' => $statuses[array_rand($statuses)],
        ];
    }
}
