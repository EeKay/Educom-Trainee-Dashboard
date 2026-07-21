<?php

namespace Database\Seeders;

use App\Models\Usage;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsageSeeder extends Seeder
{
    use WithoutModelEvents;
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'team' => 'Trainees Eindhoven',
            'name' => 'Paul Broeckx',
            'email' => 'paulhoi541@gmail.com',
            'key_alias' => 'educom_openclaw_key_paulhoi541gmailcom',
            'password' => '12345678'
        ]);

        $user->Usage()->create([
            'date' => date('Y-m-d'),
            'model' => 'model',
            'spend'=> 0.5,
            'tokens' => 555
        ]);

        $user->Usage()->create([
            'date' => Carbon::now()->startOfWeek()->format('Y-m-d'),
            'model' => 'model',
            'spend'=> 0.5,
            'tokens' => 555
        ]);

        $user->Usage()->create([
            'date' => Carbon::now()->startOfMonth()->format('Y-m-d'),
            'model' => 'model',
            'spend'=> 0.5,
            'tokens' => 555
        ]);
    }
}
