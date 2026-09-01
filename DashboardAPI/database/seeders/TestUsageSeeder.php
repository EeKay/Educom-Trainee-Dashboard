<?php

namespace Database\Seeders;

use App\Models\Usage;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TestUsageSeeder extends Seeder
{
    use WithoutModelEvents;
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'id' => '1',
            'team' => 'test_team1',
            'name' => 'test_name1',
            'role' => 'trainee',
            'email' => 'test_email1',
            'litellm_key_alias' => 'test_key1',
            'password' => '12345678'
        ]);

        $user1 = User::factory()->create([
            'id' => '2',
            'team' => 'test_team2',
            'name' => 'test_name2',
            'role' => 'trainee',
            'email' => 'test_email2',
            'litellm_key_alias' => 'test_key2',
            'password' => '12345678'
        ]);

        $user->Usage()->create([
            'date' => Carbon::create(2026, 1, 31, 0)->format('Y-m-d'),
            'model' => 'model1',
            'spend'=> 0.1,
            'tokens' => 111
        ]);

        $user->Usage()->create([
            'date' => Carbon::create(2026, 1, 31, 0)->startOfWeek()->format('Y-m-d'),
            'model' => 'model2',
            'spend'=> 0.2,
            'tokens' => 222
        ]);

        $user->Usage()->create([
            'date' => Carbon::create(2026, 1, 31, 0)->startOfMonth()->format('Y-m-d'),
            'model' => 'model3',
            'spend'=> 0.3,
            'tokens' => 333
        ]);

        $user1->Usage()->create([
            'date' => Carbon::create(2026, 1, 30, 0)->format('Y-m-d'),
            'model' => 'model1',
            'spend'=> 0.1,
            'tokens' => 111
        ]);
    }
}
