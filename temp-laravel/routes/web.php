<?php

use App\Http\Controllers\Web\ContactSubmissionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

$foundationFlags = static fn (): array => [
    'version' => 'public-v1',
    'has_header' => true,
    'has_navigation' => true,
    'has_footer' => true,
    'responsive_ready' => true,
];

Route::get('/', function () use ($foundationFlags) {
    return Inertia::render('Home', [
        'foundation' => $foundationFlags(),
    ]);
});

Route::get('/tin-tuc', function () use ($foundationFlags) {
    return Inertia::render('NewsIndex', [
        'foundation' => $foundationFlags(),
    ]);
});

Route::get('/gioi-thieu', function () use ($foundationFlags) {
    return Inertia::render('IntroductionPage', [
        'foundation' => $foundationFlags(),
    ]);
});

Route::get('/du-an', function () use ($foundationFlags) {
    return Inertia::render('ProjectsIndex', [
        'foundation' => $foundationFlags(),
    ]);
});

Route::get('/lien-he', function () use ($foundationFlags) {
    return Inertia::render('ContactPage', [
        'foundation' => $foundationFlags(),
    ]);
});

Route::get('/hoi-dap', function () use ($foundationFlags) {
    return Inertia::render('FaqPage', [
        'foundation' => $foundationFlags(),
    ]);
});

Route::post('/contact', ContactSubmissionController::class)
    ->middleware('throttle:10,1')
    ->name('contact.submit');
