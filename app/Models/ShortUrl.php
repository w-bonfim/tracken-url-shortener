<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    protected $fillable = [
        'original_url',
        'short_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    protected function shortUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => rtrim(config('app.url'), '/').'/'.$this->short_code,
        );
    }
}
