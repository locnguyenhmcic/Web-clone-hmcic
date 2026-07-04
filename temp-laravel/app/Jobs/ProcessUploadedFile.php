<?php

namespace App\Jobs;

use App\Models\Media;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessUploadedFile implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Media $media,
        public mixed $file,
    ) {
    }

    public function handle(): void
    {
        try {
            // Validate file
            $this->validateFile();

            // Process file (optimize image, generate thumbnail, etc.)
            $path = $this->processAndStore();

            // Update media record
            $this->media->update([
                'path' => $path,
                'status' => 'completed',
            ]);
        } catch (\Exception $e) {
            $this->media->update([
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);

            $this->fail($e);
        }
    }

    private function validateFile(): void
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'video/mp4'];
        $mime = mime_content_type($this->file->getRealPath());

        if (!in_array($mime, $allowedMimes)) {
            throw new \Exception('Invalid file type: ' . $mime);
        }

        if ($this->file->getSize() > 50 * 1024 * 1024) {
            throw new \Exception('File too large');
        }
    }

    private function processAndStore(): string
    {
        $disk = 'public';
        $extension = $this->file->getClientOriginalExtension();
        $mimeType = $this->file->getMimeType();

        // Determine folder based on mime type
        if (str_starts_with($mimeType, 'image/')) {
            $folder = 'images';
        } elseif ($mimeType === 'application/pdf') {
            $folder = 'pdf';
        } elseif (str_starts_with($mimeType, 'video/')) {
            $folder = 'videos';
        } else {
            $folder = 'files';
        }

        // Create date-based path
        $datePath = now()->format('Y/m');
        $filename = uniqid() . '.' . $extension;
        $path = $folder . '/' . $datePath . '/' . $filename;

        // Store file
        Storage::disk($disk)->putFileAs($folder . '/' . $datePath, $this->file, $filename);

        return $path;
    }
}
