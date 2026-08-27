<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'team' => 'Trainees Eindhoven',
            'name' => 'Paul Broeckx',
            'role' => 'trainee',
            'email' => 'paulhoi541@gmail.com',
            'litellm_key_alias' => 'educom_openclaw_key_paulhoi541gmailcom',
            'password' => '12345678'
        ]);

        User::factory()->create([
            'team' => 'Trainees Eindhoven',
            'name' => 'Loek de Kleijn',
            'role' => 'trainee',
            'email' => 'loekdekleijn03@gmail.com',
            'litellm_key_alias' => 'educom_openclaw_key_loekdekleijn03gmailc',
            'password' => '12345678'
        ]);

        User::factory()->create([
            'team' => 'Trainees Eindhoven',
            'name' => 'Ru-Lian Wang',
            'role' => 'trainee',
            'email' => '0909wang@gmail.com',
            'litellm_key_alias' => 'educom_openclaw_key_0909wanggmailcom',
            'password' => '12345678'
        ]);

        User::factory()->create([
            'team' => 'Trainees Eindhoven',
            'name' => 'Edwin Klesman',
            'role' => 'admin',
            'email' => 'ek@edu-deta.com',
            'litellm_key_alias' => 'educom_openclaw_key_edwin_klesman',
            'password' => '12345678'
        ]);
    }
}
