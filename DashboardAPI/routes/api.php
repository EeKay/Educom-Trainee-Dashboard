<?php

use App\Http\Controllers\UsageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FaqController;
use Illuminate\Http\Request;

Route::get('/aii', [UsageController::class, 'AiUsage']); //Updates database with today's AI usage data from LiteLLM API
Route::get('/ai/', [UsageController::class, 'getAiUsage']); //Retrieves all AI usage data from all users
Route::get('/ai/user/{id}', [UsageController::class, 'getUserAiUsage']); //Retrieves all AI usage data of user with user_id = {id}
Route::get('/ai/period', [UsageController::class, 'getAiUsagePeriod']);//Retrieves all AI usage data between the specified start_time and end_time
Route::get('/ai/period/user/{id}', [UsageController::class, 'getUserAiUsagePeriod']);//Retrieves all AI usage data between the specified start_time and end_time of user with user_id = {id}

Route::get('/users', [UserController::class, 'getUsers']);//Retrieves a list of all users


Route::get('/faq', [FaqController::class, 'getAll']);//Retrieves full faq
Route::get('/faq/create', [FaqController::class, 'create']);//Creates new faq item
Route::get('/faq/update/{id}', [FaqController::class, 'update']);//Updates specified faq item
Route::get('/faq/activate/{id}', [FaqController::class, 'activate']);//Sets specified faq item to active
Route::get('/faq/deactivate/{id}', [FaqController::class, 'deactivate']);//Sets specified faq item to inactive
Route::get('/faq/delete/{id}', [FaqController::class, 'delete']);//Deletes specified faq item






//API call for testing purposes only, do not call
Route::get('/addme', [UserController::class, 'AddMe']);