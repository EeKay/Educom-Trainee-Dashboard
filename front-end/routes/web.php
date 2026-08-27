<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\FaqController;

Route::get('/passwordReset', [ViewController::class, 'passwordReset'])->name('passwordReset');
Route::match(['get', 'post'], '/', [ViewController::class, 'login']);
Route::match(['get', 'post'], '/login', [ViewController::class, 'login']);

Route::get('/dashboard', [ViewController::class, "Dashboard"]);
Route::get('/dashboard-admin/{currentUser?}', [ViewController::class, "DashboardAdmin"]);

Route::get('/api/range-usage', [ViewController::class, 'RangeUsage']);
Route::get('/api/range-usage-admin', [ViewController::class, 'RangeUsageAdmin']);
Route::post('/nan', [ViewController::class, 'Chatbot']);

//faq controller
Route::get('/faq', [FaqController::class, 'Faq'])->name('faq');
Route::get('/faq-admin', [FaqController::class, 'FaqAdmin'])->name('faqAdmin');
Route::put('/faq/activate/{id?}', [FaqController::class, 'FaqActivate'])->name('faqActivate');
Route::put('/faq/deactivate/{id?}', [FaqController::class, 'FaqDeactivate'])->name('faqDeactivate');
Route::delete('/faq/delete/{id?}', [FaqController::class, 'FaqDelete'])->name('faqDelete');
Route::post('/faq/create', [FaqController::class, 'FaqCreate'])->name('faqCreate');


