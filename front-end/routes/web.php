<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ViewController;


Route::get('/dashboard', [ViewController::class, "Dashboard"]);

Route::get('/dashboard-admin/{currentUser?}', [ViewController::class, "DashboardAdmin"]);

Route::get('/api/range-usage', [ViewController::class, 'RangeUsage']);

Route::get('/faq', [ViewController::class, 'Faq'])->name('faq');

Route::get('/passwordReset', [ViewController::class, 'passwordReset'])->name('passwordReset');

Route::post('/nan', [ViewController::class, 'Chatbot']);

Route::post('/login', [ViewController::class, 'Login'])->name('loginPost');
Route::get('/login', [ViewController::class, 'Login'])->name('loginGet');

Route::post('', [ViewController::class, 'Login'])->name('loginPost');
Route::get('', [ViewController::class, 'Login'])->name('loginGet');


