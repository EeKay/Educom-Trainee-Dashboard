<?php

use Illuminate\Testing\Fluent\AssertableJson;

test('FetchUsage', function () {
    $this->seed(UserSeeder::class);
    $response = $this->get('http://localhost:8000/api/ai/fetch');

    $response->assertStatus(200);

    $this->assertDatabaseHas('Usage', [
        'date' => date('Y-m-d')
    ]);
});

test('getTotalSpendWeek', function () {
    $this->seed(UsageSeeder::class);
    $response = $this->getJson('http://localhost:8000/api/ai/spend/week');

    $response
        ->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) =>
            $json->where('name', 'Paul Broeckx')
            ->etc()
        );


});
