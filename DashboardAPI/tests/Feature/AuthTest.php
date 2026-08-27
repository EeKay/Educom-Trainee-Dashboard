<?php

use App\Models\User;
use Database\Seeders\TestUsageSeeder;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

pest()->use(RefreshDatabase::class);

test('login', function () {
    $this->refreshDatabase();
    $this->seed(TestUsageSeeder::class);
    $response = $this->postJson('api/login', ['name' => 'test_name1', 'password' => '12345678']);

    $response->assertStatus(200);
    $response->assertJson(fn (AssertableJson $json) =>
            $json->has('token')
                 ->where('role', 'trainee')
                 ->where('message', 'Logged in')
                );
});

test('logout', function () {
    $this->refreshDatabase();
    $this->seed(TestUsageSeeder::class);
    Sanctum::actingAs(
        User::first(),
        ['trainee']
    );
    $this->get('api/logout');

    //$this->assertGuest();
});
