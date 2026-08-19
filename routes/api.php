<?php

use App\Http\Controllers\Api\ShortUrlController;
use Illuminate\Support\Facades\Route;

Route::apiResource('short-urls', ShortUrlController::class);
