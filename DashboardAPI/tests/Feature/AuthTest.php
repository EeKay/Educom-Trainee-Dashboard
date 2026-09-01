<?php

use App\Models\User;
use Database\Seeders\TestUsageSeeder;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

pest()->use(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(TestUsageSeeder::class);
});

test('login', function () {
    $response = $this->postJson('api/login', ['name' => 'test_name1', 'password' => '12345678']);

    $response->assertStatus(200);
    $response->assertJson(fn (AssertableJson $json) =>
            $json->has('token')
                 ->has('id')
                 ->where('role', 'trainee')
                 ->where('message', 'Logged in')
                );
});

test('logout', function () {
    Sanctum::actingAs(
        User::first(),
        ['trainee']
    );
    $this->get('api/logout');

    //$this->assertGuest();
});
