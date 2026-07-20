<?php

use App\Http\Controllers\UsageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FaqController;
use Illuminate\Http\Request;

//Updates database with today's AI usage data from LiteLLM API
//params: none
Route::get('/aii', [UsageController::class, 'AiUsage']); 

//Retrieves all AI usage data from all users(old)
//params: none
Route::get('/ai/', [UsageController::class, 'getUsage']); 

//Retrieves all AI usage data of specified user
//params: {id} -> user_id
Route::get('/ai/user/{id}', [UsageController::class, 'getUserUsage']); 

//Retrieves all AI usage data for the specified period
//params: request -> {'start_date', 'end_date'}
Route::get('/ai/period', [UsageController::class, 'getUsagePeriod']);

//Retrieves all AI usage data of specified user for specified period
//params: {id} -> user_id, request -> {'start_date', 'end_date'}
Route::get('/ai/period/user/{id}', [UsageController::class, 'getUserUsagePeriod']);

//Retrieves full list of all users' spend and tokens
//params: none
Route::get('/ai/spend', [UsageController::class, 'getTotalSpend']);

//Retrieves full list of specified user's spend and tokens
//params: {id} -> user_id
Route::get('/ai/spend/user/{id}', [UsageController::class, 'getUserSpend']);

//Retrieves full list of all users' spend and tokens over a time period
//params: request -> {'start_date', 'end_date'}
Route::get('/ai/spend/period', [UsageController::class, 'getTotalSpendPeriod']); 

//Retrieves full list of specified user's spend and tokens over a time period
//params: {id} -> user_id, request -> {'start_date', 'end_date'}
Route::get('/ai/spend/period/user/{id}', [UsageController::class, 'getUserSpendPeriod']);

//Retrieves full list of all users' spend and tokens for this month
//params: none
Route::get('/ai/spend/month', [UsageController::class, 'getTotalSpendMonth']);

//Retrieves full list of specified user's spend and tokens for this month
//params: {id} -> user_id
Route::get('/ai/spend/month/user/{id}', [UsageController::class, 'getUserSpendMonth']);

//Retrieves full list of all users' spend and tokens for this week
//params: none
Route::get('/ai/spend/week', [UsageController::class, 'getTotalSpendWeek']);

//Retrieves full list of specified user's spend and tokens for this week
//params: {id} -> user_id
Route::get('/ai/spend/week/user/{id}', [UsageController::class, 'getUserSpendWeek']);




//Retrieves full list of users
//params: none
Route::get('/users', [UserController::class, 'getUsers']);//Retrieves a list of all users




//Retrieves full list of Faq entries
//params: none
Route::get('/faq', [FaqController::class, 'getFaqEntries']);

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






//API call for testing purposes only, do not call
Route::get('/addme', [UserController::class, 'AddMe']);
Route::get('/addedu', [UserController::class, 'AddEdu']);