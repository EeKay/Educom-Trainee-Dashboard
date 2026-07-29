<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ViewController;


Route :: get('/dashboard', [ViewController::class, "Dashboard"]);

Route :: get('/api/range-usage', [ViewController::class, 'RangeUsage']);

Route :: get('/faq', [ViewController::class, 'Faq'])->name('faq');

Route :: get('/login', function() {
    return Inertia::render('Login');
});

Route :: get('/passwordReset', [ViewController::class, 'passwordReset'])->name('passwordReset');
