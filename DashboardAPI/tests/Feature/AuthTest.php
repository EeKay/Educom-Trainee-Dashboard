<?php

use App\Models\User;
use Database\Seeders\UserSeeder;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

pest()->use(RefreshDatabase::class);

test('login', function () {
    $this->seed(UserSeeder::class);
    $response = $this->postJson('api/login', ['name' => 'Educom LLM', 'password' => '12345678']);

    $response->assertStatus(200);
    $response->assertJson(fn (AssertableJson $json) =>
            $json->has('token')
                 ->where('role', 'trainee')
                 ->where('message', 'Logged in')
                );
});

test('logout', function () {
    $this->seed(UsageSeeder::class);
    Sanctum::actingAs(
        User::where('id', 1)->first(),
        ['trainee']
    );
    $this->get('api/logout');

    //$this->assertGuest();
});
