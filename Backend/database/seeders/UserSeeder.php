<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create specific users for easy login during development
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@app.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Volunteer User',
            'email' => 'volunteer@app.com',
            'role' => 'volunteer',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Beneficiary User',
            'email' => 'beneficiary@app.com',
            'role' => 'beneficiary',
            'password' => Hash::make('password'),
        ]);

        // Create 30 random users of mixed roles
        User::factory(30)->create();
    }
}
