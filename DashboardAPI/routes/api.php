<?php

use App\Http\Controllers\UsageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\nanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('test', function () {return 'Succes';})
    ->middleware(['cookie.filter', 'auth:sanctum', 'ability:trainee']);


Route::get('/ai/fetch/period', [UsageController::class, 'fetchUsagePeriod']); 

//Updates database with today's AI usage data from LiteLLM API
//params: none
Route::get('/ai/fetch', [UsageController::class, 'fetchUsage']); 

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

//Retrieves full list of specified user's spend and tokens over a time period in daily intervals
//params: {id} -> user_id, request -> {'start_date', 'end_date'}
Route::get('/ai/spend/period/daily/user/{id}', [UsageController::class, 'getUserSpendPeriodDaily']);

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




//Contacts the n8n workflow
//params: request -> {(int)'traineeId', 'question', 'sessionId', (boolean)'faqRejected'}
Route::post('/nan', [nanController::class, 'contactWorkflow']); 

//Sends email to reset password
//params: none
Route::post('/nan/resetPassword', [nanController::class, 'resetPassword'])
    ->middleware(['auth:sanctum', 'ability:trainee']); 





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




//login
//params: request->{'name', 'password'} 
Route::post('/login', [AuthController::class, 'login']);
//logout
//params: none
Route::get('/logout', [AuthController::class, 'logout']);





//API call for testing purposes only, do not call
Route::get('/addme', [UserController::class, 'AddMe']);
Route::get('/addedu', [UserController::class, 'AddEdu']);
Route::get('/addru', [UserController::class, 'AddRuLian']);