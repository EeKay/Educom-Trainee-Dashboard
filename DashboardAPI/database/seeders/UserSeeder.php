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
            'name' => 'Educom LLM',
            'role' => 'trainee',
            'email' => 'email',
            'key_alias' => 'Educom Dashboard LLM Key',
            'password' => '12345678'
        ]);
    }
}
