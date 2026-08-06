<?php

use App\Http\Controllers\UsageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\nanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Updates database with AI usage data of the specified period from LiteLLM API
//params: request -> {'start_date', 'end_date'}
Route::get('/ai/fetch/period', [UsageController::class, 'fetchUsagePeriod']);//for internal use

//Updates database with today's AI usage data from LiteLLM API
//params: none
Route::get('/ai/fetch', [UsageController::class, 'fetchUsage']);//for internal use

//Retrieves all AI usage data from all users(old)
//params: none
Route::get('/ai/', [UsageController::class, 'getUsage']);//deprecated

//Retrieves all AI usage data of specified user
//params: {id} -> user_id
Route::get('/ai/user/{id}', [UsageController::class, 'getUserUsage']);//deprectated

//Retrieves all AI usage data for the specified period
//params: request -> {'start_date', 'end_date'}
Route::get('/ai/period', [UsageController::class, 'getUsagePeriod']);//deprecated

//Retrieves all AI usage data of specified user for specified period
//params: {id} -> user_id, request -> {'start_date', 'end_date'}
Route::get('/ai/period/user/{id}', [UsageController::class, 'getUserUsagePeriod']);//deprecated


//Trainee usage data fetches
Route::group(['middleware' => ['cookie.filter', 'auth:sanctum','ability:admin,trainee']], function() {

    //Retrieves full list of all users' spend and tokens
    //params: none
    Route::get('/ai/spend', [UsageController::class, 'getTotalSpend']);//trainee

    //Retrieves full list of specified user's spend and tokens
    Route::get('/ai/spend/user', [UsageController::class, 'getCurrentUserSpend']);//trainee 
    
    //Retrieves full list of all users' spend and tokens over a time period
    //params: request -> {'start_date', 'end_date'}
    Route::get('/ai/spend/period', [UsageController::class, 'getTotalSpendPeriod']);//trainee

    //Retrieves full list of specified user's spend and tokens over a time period
    //params: request -> {'start_date', 'end_date'}
    Route::get('/ai/spend/period/user', [UsageController::class, 'getCurrentUserSpendPeriod']);//trainee

    //Retrieves full list of current user's spend and tokens per model over a time period in daily intervals
    //params: request -> {'start_date', 'end_date'}
    Route::get('/ai/spend/period/daily/user', [UsageController::class, 'getCurrentUserSpendPeriodDaily']);//trainee

    //Retrieves full list of all users' spend and tokens for this month
    //params: none
    Route::get('/ai/spend/month', [UsageController::class, 'getTotalSpendMonth']);//trainee

    //Retrieves full list of specified user's spend and tokens for this month
    Route::get('/ai/spend/month/user', [UsageController::class, 'getCurrentUserSpendMonth']);//trainee

    //Retrieves full list of all users' spend and tokens for this week
    //params: none
    Route::get('/ai/spend/week', [UsageController::class, 'getTotalSpendWeek']);//trainee

    //Retrieves full list of specified user's spend and tokens for this week
    Route::get('/ai/spend/week/user', [UsageController::class, 'getCurrentUserSpendWeek']);//trainee

});



//Admin usage data fetches
Route::group(['middleware' => ['cookie.filter', 'auth:sanctum','ability:admin']], function() {

    //Retrieves full list of specified user's spend and tokens
    //params: {id} -> user_id
    Route::get('/ai/spend/user/{id}', [UsageController::class, 'getUserSpend']);//admin

    //Retrieves full list of specified user's spend and tokens over a time period
    //params: {id} -> user_id, request -> {'start_date', 'end_date'}
    Route::get('/ai/spend/period/user/{id}', [UsageController::class, 'getUserSpendPeriod']);//admin

    //Retrieves full list of specified user's spend and tokens per model over a time period in daily intervals
    //params: {id} -> user_id, request -> {'start_date', 'end_date'}
    Route::get('/ai/spend/period/daily/user/{id}', [UsageController::class, 'getUserSpendPeriodDaily']);//admin

    //Retrieves full list of specified user's spend and tokens for this month
    //params: {id} -> user_id
    Route::get('/ai/spend/month/user/{id}', [UsageController::class, 'getUserSpendMonth']);//admin

    //Retrieves full list of specified user's spend and tokens for this week
    //params: {id} -> user_id
    Route::get('/ai/spend/week/user/{id}', [UsageController::class, 'getUserSpendWeek']);//admin

});



//Admin user CRUD
Route::group(['middleware' => ['cookie.filter', 'auth:sanctum','ability:admin']], function() {

    //Retrieves full list of users
    //params: none
    Route::get('/users', [UserController::class, 'getUsers']);

    //create user
    //params: {id} -> user_id, request -> {'team', 'name', 'role'(optional), 'email', 'key_alias', 'password'}
    Route::post('/user/create', [UserController::class, 'create']);

    //update specified user
    //params: {id} -> user_id, request -> {'team', 'name', 'role', 'email', 'key_alias', 'password'}
    Route::post('/user/update/{id}', [UserController::class, 'update']);

    //delete specified user
    //params: {id} -> user_id
    Route::delete('/user/delete/{id}', [UserController::class, 'delete']);

});



//n8n routes
Route::group(['middleware' => ['cookie.filter', 'auth:sanctum','ability:admin,trainee']], function() {

    //Contacts the n8n workflow
    //params: request -> {'question', (boolean)'faqRejected'}
    Route::post('/nan', [nanController::class, 'contactWorkflow']); 

    //Sends email to reset password
    //params: none
    Route::post('/nan/resetPassword', [nanController::class, 'resetPassword']); 

});



//Trainee faq get
Route::group(['middleware' => ['cookie.filter', 'auth:sanctum','ability:admin,trainee']], function() {

    //Retrieves full list of Faq entries
    //params: none
    Route::get('/faq', [FaqController::class, 'getFaqEntries']);

});



//Admin faq CRUD
Route::group(['middleware' => ['cookie.filter', 'auth:sanctum','ability:admin']], function() {

    //Creates new faq item
    //params: request -> {'question', 'answer'}
    Route::post('/faq/create', [FaqController::class, 'create']);

    //Updates specified faq item
    //params: {id} -> faq_id, request -> {'question', 'answer'}
    Route::put('/faq/update/{id}', [FaqController::class, 'update']);

    //Sets specified faq item to active
    //params: {id} -> faq_id
    Route::put('/faq/activate/{id}', [FaqController::class, 'activate']);

    //Sets specified faq item to inactive
    //params: {id} -> faq_id
    Route::put('/faq/deactivate/{id}', [FaqController::class, 'deactivate']);

    //Deletes specified faq item
    //params: {id} -> faq_id
    Route::delete('/faq/delete/{id}', [FaqController::class, 'delete']);

});




//login
//params: request->{'name', 'password'} 
Route::post('/login', [AuthController::class, 'login']);

//logout
//params: none
Route::get('/logout', [AuthController::class, 'logout']);





//API call for testing purposes only, do not call
Route::get('/addme', [UserController::class, 'AddMe']);