<?php

namespace App\Services;

use App\Models\ShortUrl;
use Illuminate\Support\Str;

class ShortUrlService
{
    private const CODE_LENGTH = 6;

    public function create(string $originalUrl, ?string $customCode = null): ShortUrl
    {
        return ShortUrl::create([
            'original_url' => $originalUrl,
            'short_code' => $customCode ?? $this->generateUniqueCode(),
        ]);
    }

    private function generateUniqueCode(): string
    {
        do {
            $code = Str::random(self::CODE_LENGTH);
        } while (ShortUrl::where('short_code', $code)->exists());

        return $code;
    }
}
