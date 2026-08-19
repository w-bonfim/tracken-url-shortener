<?php

use App\Http\Controllers\RedirectShortUrlController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'name' => 'Tracken URL Shortener API',
        'docs' => '/api',
    ]);
});

Route::get('/{code}', RedirectShortUrlController::class)
    ->where('code', '[A-Za-z0-9]{6}')
    ->name('short-urls.redirect');
