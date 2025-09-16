<?php

namespace Database\Seeders;

use App\Models\AidRequest;
use Illuminate\Database\Seeder;

class AidRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AidRequest::factory(40)->create();
    }
}
