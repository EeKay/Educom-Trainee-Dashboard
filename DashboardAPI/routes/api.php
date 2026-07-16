<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/aii', [ApiController::class, 'AiUsage']);
Route::get('/ai/', [ApiController::class, 'getAiUsage']);
Route::get('/ai/user/{id}', [ApiController::class, 'getUserAiUsage']);
Route::get('/users', [ApiController::class, 'getUsers']);
Route::get('/ai/period', [ApiController::class, 'getAiUsagePeriod']);
Route::get('/ai/period/user/{id}', [ApiController::class, 'getUserAiUsagePeriod']);

//API call for testing purposes only, do not call
Route::get('/addme', [ApiController::class, 'AddMe']);