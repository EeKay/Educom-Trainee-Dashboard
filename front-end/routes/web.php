<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route :: get('/', function(){
    return Inertia::render('Dashboard');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('/faq', function () {
    return Inertia::render('Faq');
})->name('faq');

Route::get('/login', function () {
    return Inertia::render('Login');
});