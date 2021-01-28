<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('change-image', [ImageController::class, 'changeImage'])
    ->name('image');
