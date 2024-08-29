<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/upload-image', [ImageController::class, 'upload']);
Route::get('/upload-form', function () {
    return view('upload');
});

