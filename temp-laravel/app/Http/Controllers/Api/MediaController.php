<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessUploadedFile;
use App\Services\MediaService;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function __construct(private MediaService $mediaService)
    {
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:51200',
        ]);

        $file = $request->file('file');

        // Save temp and dispatch job
        $media = $this->mediaService->saveTemp($file);

        ProcessUploadedFile::dispatch($media, $file);

        return response()->json([
            'id' => $media->id,
            'status' => 'processing',
            'message' => 'File is being processed',
        ], 202);
    }

    public function status($id)
    {
        $media = \App\Models\Media::find($id);

        if (!$media) {
            return response()->json(['error' => 'Media not found'], 404);
        }

        return response()->json([
            'id' => $media->id,
            'status' => $media->status ?? 'completed',
            'url' => $media->url ?? null,
        ]);
    }
}
