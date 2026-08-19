<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\RedirectResponse;

class RedirectShortUrlController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $code): RedirectResponse
    {
        $shortUrl = ShortUrl::where('short_code', $code)
            ->where('is_active', true)
            ->firstOrFail();

        return redirect()->away($shortUrl->original_url);
    }
}
