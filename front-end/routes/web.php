<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ViewController;


Route::get('/dashboard', [ViewController::class, "Dashboard"]);

Route::get('/dashboard-admin/{currentUser?}', [ViewController::class, "DashboardAdmin"]);

Route::get('/api/range-usage', [ViewController::class, 'RangeUsage']);
Route::get('/api/range-usage-admin', [ViewController::class, 'RangeUsageAdmin']);

Route::get('/faq', [ViewController::class, 'Faq'])->name('faq');
Route::get('/faq-admin', [ViewController::class, 'FaqAdmin'])->name('faqAdmin');
Route::put('/faq/activate/{id?}', [ViewController::class, 'FaqActivate'])->name('faqActivate');
Route::put('/faq/deactivate/{id?}', [ViewController::class, 'FaqDeactivate'])->name('faqDeactivate');
Route::delete('/faq/delete/{id?}', [ViewController::class, 'FaqDelete'])->name('faqDelete');
Route::post('/faq/create/{id?}', [ViewController::class, 'FaqCreate'])->name('faqCreate');

Route::get('/passwordReset', [ViewController::class, 'passwordReset'])->name('passwordReset');

Route::post('/nan', [ViewController::class, 'Chatbot']);

Route::post('/login', [ViewController::class, 'Login'])->name('loginPost');
Route::get('/login', [ViewController::class, 'Login'])->name('loginGet');

Route::post('', [ViewController::class, 'Login'])->name('loginPost');
Route::get('', [ViewController::class, 'Login'])->name('loginGet');


