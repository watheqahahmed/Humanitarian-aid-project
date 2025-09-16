<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Donation;
use App\Models\AidRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class DistributionFactory extends Factory
{
    public function definition(): array
    {
        $deliveryStatuses = ['assigned', 'in_progress', 'completed'];
        return [
            'volunteer_id' => User::where('role', 'volunteer')->inRandomOrder()->first()->id,
            'beneficiary_id' => User::where('role', 'beneficiary')->inRandomOrder()->first()->id,
            'donation_id' => Donation::inRandomOrder()->first()->id,
            'delivery_status' => $deliveryStatuses[array_rand($deliveryStatuses)],
            'proof_file' => fake()->imageUrl(640, 480, 'proofs'),
        ];
    }
}
