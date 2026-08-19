<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShortUrlRequest;
use App\Http\Requests\UpdateShortUrlRequest;
use App\Http\Resources\ShortUrlResource;
use App\Models\ShortUrl;
use App\Services\ShortUrlService;
use Illuminate\Http\Response;

class ShortUrlController extends Controller
{
    public function __construct(private readonly ShortUrlService $shortUrlService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shortUrls = ShortUrl::latest()->get();

        return ShortUrlResource::collection($shortUrls);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShortUrlRequest $request)
    {
        $shortUrl = $this->shortUrlService->create(
            $request->validated('original_url'),
            $request->validated('custom_code'),
        );

        return (new ShortUrlResource($shortUrl))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShortUrl $shortUrl)
    {
        return new ShortUrlResource($shortUrl);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShortUrlRequest $request, ShortUrl $shortUrl)
    {
        $shortUrl->update($request->validated());

        return new ShortUrlResource($shortUrl);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShortUrl $shortUrl)
    {
        $shortUrl->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
