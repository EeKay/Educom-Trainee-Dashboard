<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

Route::get('/ai', [ApiController::class, 'AIusage']);
Route::get('/showai', [ApiController::class, 'showAIusage']);
Route::get('/addme', [ApiController::class, 'AddMe']);