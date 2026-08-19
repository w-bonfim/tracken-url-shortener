<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'name' => 'Tracken URL Shortener API',
        'docs' => '/api',
    ]);
});
