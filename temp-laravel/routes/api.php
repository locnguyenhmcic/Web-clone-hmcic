<?php

use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\FAQController;
use App\Http\Controllers\Api\MediaController;
use Illuminate\Support\Facades\Route;

Route::get('/news', [NewsController::class, 'index']);
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/faq', [FAQController::class, 'index']);
Route::post('/media/upload', [MediaController::class, 'upload']);
Route::get('/media/{id}/status', [MediaController::class, 'status']);
