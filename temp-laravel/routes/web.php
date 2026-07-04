<?php

use App\Http\Controllers\Web\ContactSubmissionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
});

Route::post('/contact', ContactSubmissionController::class)
    ->middleware('throttle:10,1')
    ->name('contact.submit');
