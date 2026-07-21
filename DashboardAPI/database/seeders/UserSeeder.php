<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'team' => 'Trainees Eindhoven',
            'name' => 'Paul Broeckx',
            'email' => 'paulhoi541@gmail.com',
            'key_alias' => 'educom_openclaw_key_paulhoi541gmailcom',
            'password' => '12345678'
        ]);
    }
}
