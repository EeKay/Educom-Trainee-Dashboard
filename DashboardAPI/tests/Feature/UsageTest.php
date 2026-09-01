<?php

use App\Models\User;
use App\Http\Controller\UsageController;
use Laravel\Sanctum\Sanctum;
use Database\Seeders\TestUsageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Illuminate\Testing\Fluent\AssertableJson;
use Carbon\Carbon;

pest()->use(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(TestUsageSeeder::class);
});

//test getTotalSpend output
test('getTotalSpend', function () {
    Sanctum::actingAs(
        User::where('id', 1)->first(),
        ['trainee']
    );

   
    $response = $this->withSession(['banned' => false])->get('api/ai/spend');

    $response->assertStatus(200);
    //test full json layout based on seeder
    $response->assertJson(fn (AssertableJson $json) =>
        $json->has(2)
             ->has(0, fn (AssertableJson $json) =>
            $json->where('user_id', 1)
                 ->where('name', 'test_name1')
                 ->where('spend', 0.6)
                 ->where('tokens', 666)
             )
             ->has(1, fn (AssertableJson $json) =>
            $json->where('user_id', 2)
                 ->where('name', 'test_name2')
                 ->where('spend', 0.1)
                 ->where('tokens', 111)
             )
        );
});

//test getUserSpend output
test('getUserSpend', function () {
    Sanctum::actingAs(
        User::where('id', 1)->first(),
        ['admin']
    );
    $response = $this->get('api/ai/spend/user/1');

    $response->assertStatus(200);
    //test full json layout based on seeder
    $response->assertJson(fn (AssertableJson $json) =>
        $json->where('user_id', 1)
             ->where('name', 'test_name1')
             ->where('spend', 0.6)
             ->where('tokens', 666)
             ->has('models', fn (AssertableJson $json) =>
                $json->has(3)
                     ->where(0, 'model1')
                     ->where(1, 'model2')
                     ->where(2, 'model3')
             )
             ->has('data', fn (AssertableJson $json) =>
                $json->has(3)
                     ->has('model1', fn (AssertableJson $json) =>
                    $json->has(2)
                         ->where('spend', 0.1)
                         ->where('tokens', 111)
                     )
                     ->has('model2', fn (AssertableJson $json) =>
                    $json->has(2)
                         ->where('spend', 0.2)
                         ->where('tokens', 222)
                     )
                     ->has('model3', fn (AssertableJson $json) =>
                    $json->has(2)
                         ->where('spend', 0.3)
                         ->where('tokens', 333)
                     )
             )
    );
});

//test getCurrentUserSpend output
test('getCurrentUserSpend', function () {
    Sanctum::actingAs(
        User::where('id', 1)->first(),
        ['trainee']
    );
    $response = $this->get('api/ai/spend/user');

    $response->assertStatus(200);
    //test full json layout based on seeder
    $response->assertJson(fn (AssertableJson $json) =>
        $json->where('user_id', 1)
             ->where('name', 'test_name1')
             ->where('spend', 0.6)
             ->where('tokens', 666)
             ->has('models', fn (AssertableJson $json) =>
                $json->has(3)
                     ->where(0, 'model1')
                     ->where(1, 'model2')
                     ->where(2, 'model3')
             )
             ->has('data', fn (AssertableJson $json) =>
                $json->has(3)
                     ->has('model1', fn (AssertableJson $json) =>
                    $json->has(2)
                         ->where('spend', 0.1)
                         ->where('tokens', 111)
                     )
                     ->has('model2', fn (AssertableJson $json) =>
                    $json->has(2)
                         ->where('spend', 0.2)
                         ->where('tokens', 222)
                     )
                     ->has('model3', fn (AssertableJson $json) =>
                    $json->has(2)
                         ->where('spend', 0.3)
                         ->where('tokens', 333)
                     )
             )
    );
});

//test getTotalSpendPeriod output
test('getTotalSpendPeriod', function () {
    $id = 1;

    Sanctum::actingAs(
        User::where('id', $id)->first(),
        ['trainee']
    );
    $response = $this->get('api/ai/spend/period?start_date=2026-01-30&end_date=2026-01-30');

    $response->assertStatus(200);
    //test full json layout based on seeder
    $response->assertJson(fn (AssertableJson $json) =>
        $json->has(2)
             ->has(0, fn (AssertableJson $json) =>
                $json->where('user_id', 1)
                     ->where('name', 'test_name1')
                     ->where('spend', 0)
                     ->where('tokens', 0)
             )
             ->has(1, fn (AssertableJson $json) =>
                $json->where('user_id', 2)
                     ->where('name', 'test_name2')
                     ->where('spend', 0.1)
                     ->where('tokens', 111)
             )
    );
});

//test getUserSpendPeriod output
test('getUserSpendPeriod', function () {
    $id = 2;

    Sanctum::actingAs(
        User::where('id', $id)->first(),
        ['admin']
    );
    $response = $this->get('api/ai/spend/period/user/'.$id.'?start_date=2026-01-30&end_date=2026-01-30');

    $response->assertStatus(200);
    //test full json layout based on seeder
    $response->assertJson(fn (AssertableJson $json) =>
        $json->where('user_id', 2)
             ->where('name', 'test_name2')
             ->where('spend', 0.1)
             ->where('tokens', 111)
             ->has('models', fn (AssertableJson $json) =>
                $json->where(0, 'model1')
             )
             ->has('data', fn (AssertableJson $json) =>
                $json->has('model1', fn (AssertableJson $json) =>
                    $json->where('spend', 0.1)
                         ->where('tokens', 111)
                )
             )
    );
});

//test getCurrentUserSpendPeriod
test('getCurrentUserSpendPeriod', function () {
    $id = 2;

    Sanctum::actingAs(
        User::where('id', $id)->first(),
        ['trainee']
    );
    $response = $this->get('api/ai/spend/period/user?start_date=2026-01-30&end_date=2026-01-30');

    $response->assertStatus(200);
    //test full json layout based on seeder
    $response->assertJson(fn (AssertableJson $json) =>
        $json->where('user_id', 2)
             ->where('name', 'test_name2')
             ->where('spend', 0.1)
             ->where('tokens', 111)
             ->has('models', fn (AssertableJson $json) =>
                $json->where(0, 'model1')
             )
             ->has('data', fn (AssertableJson $json) =>
                $json->has('model1', fn (AssertableJson $json) =>
                    $json->where('spend', 0.1)
                         ->where('tokens', 111)
                )
             )
    );
});

//test getUserSpendPeriodDaily output
test('getUserSpendPeriodDaily', function () {
    $id = 1;

    Sanctum::actingAs(
        User::where('id', $id)->first(),
        ['admin']
    );
    $response = $this->get('api/ai/spend/period/daily/user/'.$id.'?start_date=2026-01-29&end_date=2026-01-31');

    $response->assertStatus(200);
    //test full json layout based on seeder
    $response->assertJson(fn (AssertableJson $json) => 
        $json->has('models', fn (AssertableJson $json) =>
                $json->where(0, 'model1')
             )
             ->has('results', fn (AssertableJson $json) =>
                $json->has('0', fn (AssertableJson $json) =>
                        $json->where('date', '2026-01-29')
                             ->has('data', fn (AssertableJson $json) =>
                                $json->has('model1', fn (AssertableJson $json) =>
                                    $json->where('spend', 0)
                                         ->where('tokens', 0)
                                )
                            )
                        )
                     ->has('1', fn (AssertableJson $json) =>
                        $json->where('date', '2026-01-30')
                             ->has('data', fn (AssertableJson $json) =>
                                $json->has('model1', fn (AssertableJson $json) =>
                                    $json->where('spend', 0)
                                         ->where('tokens', 0)
                                )
                            )
                        )
                     ->has('2', fn (AssertableJson $json) =>
                        $json->where('date', '2026-01-31')
                             ->has('data', fn (AssertableJson $json) =>
                                $json->has('model1', fn (AssertableJson $json) =>
                                    $json->where('spend', 0.1)
                                         ->where('tokens', 111)
                                )
                            )
                        )
             )
    );
});

//test getCurrentUserSpendPeriodDaily output
test('getCurrentUserSpendPeriodDaily', function () {
    $id = 1;

    Sanctum::actingAs(
        User::where('id', $id)->first(),
        ['trainee']
    );
    $response = $this->get('api/ai/spend/period/daily/user?start_date=2026-01-29&end_date=2026-01-31');

    $response->assertStatus(200);
    //test full json layout based on seeder
    $response->assertJson(fn (AssertableJson $json) => 
        $json->has('models', fn (AssertableJson $json) =>
                $json->where(0, 'model1')
             )
             ->has('results', fn (AssertableJson $json) =>
                $json->has('0', fn (AssertableJson $json) =>
                        $json->where('date', '2026-01-29')
                             ->has('data', fn (AssertableJson $json) =>
                                $json->has('model1', fn (AssertableJson $json) =>
                                    $json->where('spend', 0)
                                         ->where('tokens', 0)
                                )
                            )
                        )
                     ->has('1', fn (AssertableJson $json) =>
                        $json->where('date', '2026-01-30')
                             ->has('data', fn (AssertableJson $json) =>
                                $json->has('model1', fn (AssertableJson $json) =>
                                    $json->where('spend', 0)
                                         ->where('tokens', 0)
                                )
                            )
                        )
                     ->has('2', fn (AssertableJson $json) =>
                        $json->where('date', '2026-01-31')
                             ->has('data', fn (AssertableJson $json) =>
                                $json->has('model1', fn (AssertableJson $json) =>
                                    $json->where('spend', 0.1)
                                         ->where('tokens', 111)
                                )
                            )
                        )
             )
    );
});

//test getTotalSpendMonth output
test('getTotalSpendMonth', function () {
    $id = 1;

    Carbon::setTestNow(Carbon::create(2026, 1, 31, 0));
    Sanctum::actingAs(
        User::where('id', $id)->first(),
        ['trainee']
    );
    $response = $this->get('api/ai/spend/month');

    $response->assertStatus(200);
    //test full json layout based on seeder
    $response->assertJson(fn (AssertableJson $json) => 
        $json->has('0', fn (AssertableJson $json) => 
            $json->where('user_id', 1)
                 ->where('name', 'test_name1')
                 ->where('spend', 0.6)
                 ->where('tokens', 666)
        )
             ->has('1', fn (AssertableJson $json) =>
            $json->where('user_id', 2)
                 ->where('name', 'test_name2')
                 ->where('spend', 0.1)
                 ->where('tokens', 111)
        )
    );
});

//test getUserSpendMonth output
test('getUserSpendMonth', function () {
    $id = 1;

    Carbon::setTestNow(Carbon::create(2026, 1, 31, 0));
    Sanctum::actingAs(
        User::where('id', $id)->first(),
        ['admin']
    );
    $response = $this->get('api/ai/spend/month/user/'.$id);

    $response->assertStatus(200);
    //test full json layout based on seeder
    $response->assertJson(fn (AssertableJson $json) => 
        $json->where('user_id', 1)
             ->where('name', 'test_name1')
             ->where('spend', 0.6)
             ->where('tokens', 666)
             ->has('models', fn (AssertableJson $json) =>
                $json->where(0, 'model1')
                     ->where(1, 'model2')
                     ->where(2, 'model3')
             )
             ->has('data', fn (AssertableJson $json) =>
                $json->has('model1', fn (AssertableJson $json) =>
                        $json->where('spend', 0.1)
                             ->where('tokens', 111)
                     )
                     ->has('model2', fn (AssertableJson $json) =>
                        $json->where('spend', 0.2)
                             ->where('tokens', 222)
                     )
                     ->has('model3', fn (AssertableJson $json) =>
                        $json->where('spend', 0.3)
                             ->where('tokens', 333)
                     )
             )
    );
});

//test getCurrentUserSpendMonth output
test('getCurrentUserSpendMonth', function () {
    $id = 1;

    Carbon::setTestNow(Carbon::create(2026, 1, 31, 0));
    Sanctum::actingAs(
        User::where('id', $id)->first(),
        ['trainee']
    );
    $response = $this->get('api/ai/spend/month/user');

    $response->assertStatus(200);
    //test full json layout based on seeder
    $response->assertJson(fn (AssertableJson $json) => 
        $json->where('user_id', 1)
             ->where('name', 'test_name1')
             ->where('spend', 0.6)
             ->where('tokens', 666)
             ->has('models', fn (AssertableJson $json) =>
                $json->where(0, 'model1')
                     ->where(1, 'model2')
                     ->where(2, 'model3')
             )
             ->has('data', fn (AssertableJson $json) =>
                $json->has('model1', fn (AssertableJson $json) =>
                        $json->where('spend', 0.1)
                             ->where('tokens', 111)
                     )
                     ->has('model2', fn (AssertableJson $json) =>
                        $json->where('spend', 0.2)
                             ->where('tokens', 222)
                     )
                     ->has('model3', fn (AssertableJson $json) =>
                        $json->where('spend', 0.3)
                             ->where('tokens', 333)
                     )
             )
    );
});

//test getTotalSpendWeek output
test('getTotalSpendWeek', function () {
    $id = 1;

    Carbon::setTestNow(Carbon::create(2026, 1, 31, 0));
    Sanctum::actingAs(
        User::where('id', $id)->first(),
        ['trainee']
    );
    $response = $this->get('api/ai/spend/week');

    $response->assertStatus(200);
    //test full json layout based on seeder
    $response->assertJson(fn (AssertableJson $json) => 
        $json->has('0', fn (AssertableJson $json) => 
            $json->where('user_id', 1)
                 ->where('name', 'test_name1')
                 ->where('spend', 0.3)
                 ->where('tokens', 333)
        )
             ->has('1', fn (AssertableJson $json) =>
            $json->where('user_id', 2)
                 ->where('name', 'test_name2')
                 ->where('spend', 0.1)
                 ->where('tokens', 111)
        )
    );

});

//test getUserSpendWeek output
test('getUserSpendWeek', function () {
    $id = 1;

    Carbon::setTestNow(Carbon::create(2026, 1, 31, 0));
    Sanctum::actingAs(
        User::where('id', $id)->first(),
        ['admin']
    );
    $response = $this->get('api/ai/spend/week/user/'.$id);

    $response->assertStatus(200);
    //test full json layout based on seeder
    $response->assertJson(fn (AssertableJson $json) => 
        $json->where('user_id', 1)
             ->where('name', 'test_name1')
             ->where('spend', 0.3)
             ->where('tokens', 333)
             ->has('models', fn (AssertableJson $json) =>
                $json->where(0, 'model1')
                     ->where(1, 'model2')
             )
             ->has('data', fn (AssertableJson $json) =>
                $json->has('model1', fn (AssertableJson $json) =>
                        $json->where('spend', 0.1)
                             ->where('tokens', 111)
                     )
                     ->has('model2', fn (AssertableJson $json) =>
                        $json->where('spend', 0.2)
                             ->where('tokens', 222)
                     )
             )
    );
});

//test getCurrentUserSpendWeek output
test('getCurrentUserSpendWeek', function () {
    $id = 1;

    Carbon::setTestNow(Carbon::create(2026, 1, 31, 0));
    Sanctum::actingAs(
        User::where('id', $id)->first(),
        ['trainee']
    );
    $response = $this->get('api/ai/spend/week/user');

    $response->assertStatus(200);
    //test full json layout based on seeder
    $response->assertJson(fn (AssertableJson $json) => 
        $json->where('user_id', 1)
             ->where('name', 'test_name1')
             ->where('spend', 0.3)
             ->where('tokens', 333)
             ->has('models', fn (AssertableJson $json) =>
                $json->where(0, 'model1')
                     ->where(1, 'model2')
             )
             ->has('data', fn (AssertableJson $json) =>
                $json->has('model1', fn (AssertableJson $json) =>
                        $json->where('spend', 0.1)
                             ->where('tokens', 111)
                     )
                     ->has('model2', fn (AssertableJson $json) =>
                        $json->where('spend', 0.2)
                             ->where('tokens', 222)
                     )
             )
    );
});