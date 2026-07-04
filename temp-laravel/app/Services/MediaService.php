<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

class MediaService
{
    private const FILE_SIZE_LIMITS = [
        'image' => 20 * 1024 * 1024,
        'pdf' => 50 * 1024 * 1024,
        'video' => 500 * 1024 * 1024,
    ];

    private const ALLOWED_EXTENSIONS = [
        'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'pdf' => ['pdf'],
        'video' => ['mp4', 'mov', 'webm'],
    ];

    private const ALLOWED_MIME_TYPES = [
        'image' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
        'pdf' => ['application/pdf'],
        'video' => ['video/mp4', 'video/quicktime', 'video/webm'],
    ];

    public function formatStorageSize(int $bytes): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = (int) floor(log($bytes, 1024));
        $power = min($power, count($units) - 1);
        $value = $bytes / (1024 ** $power);

        return number_format($value, 2) . ' ' . $units[$power];
    }

    public function resolveFileType(string $mimeType): string
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }

        if ($mimeType === 'application/pdf') {
            return 'pdf';
        }

        if (str_starts_with($mimeType, 'video/')) {
            return 'video';
        }

        return 'other';
    }

    public function resolveFileTypeLabel(string $mimeType): string
    {
        return match ($this->resolveFileType($mimeType)) {
            'image' => 'Image',
            'pdf' => 'PDF',
            'video' => 'Video',
            default => 'Other',
        };
    }

    public function buildStorageDirectory(string $mimeType, ?Carbon $date = null): string
    {
        $date ??= now();

        $folder = match ($this->resolveFileType($mimeType)) {
            'image' => 'images',
            'pdf' => 'pdf',
            'video' => 'videos',
            default => 'files',
        };

        return sprintf('%s/%s/%s', $folder, $date->format('Y'), $date->format('m'));
    }

    public function makeFileName(string $directory, string $baseName, string $extension, string $disk = 'public'): string
    {
        $slug = Str::slug($baseName);

        if ($slug === '') {
            $slug = 'file';
        }

        $candidate = sprintf('%s.%s', $slug, $extension);
        $counter = 1;

        while (Storage::disk($disk)->exists($directory . '/' . $candidate)) {
            $candidate = sprintf('%s-%d.%s', $slug, $counter, $extension);
            $counter++;
        }

        return $candidate;
    }

    public function createFromStoredUpload(string $storedPath, array $attributes = []): Media
    {
        $disk = $attributes['disk'] ?? 'public';

        if (! Storage::disk($disk)->exists($storedPath)) {
            throw new InvalidArgumentException('Uploaded file does not exist.');
        }

        $originalFileName = basename($storedPath);
        $baseName = pathinfo($originalFileName, PATHINFO_FILENAME);
        $extension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION) ?: 'bin');
        $detectedMimeType = Storage::disk($disk)->mimeType($storedPath);
        $mimeType = $detectedMimeType ?: $this->mimeTypeFromExtension($extension);

        if ($this->resolveFileType($mimeType) === 'other') {
            $mimeType = $this->mimeTypeFromExtension($extension);
        }

        $size = (int) Storage::disk($disk)->size($storedPath);

        $this->validateStoredUpload($extension, $mimeType, $size);

        $directory = $this->buildStorageDirectory($mimeType, now());
        $finalFileName = $this->makeFileName($directory, $attributes['original_name'] ?? $baseName, $extension, $disk);
        $finalPath = $directory . '/' . $finalFileName;

        Storage::disk($disk)->makeDirectory($directory);
        Storage::disk($disk)->move($storedPath, $finalPath);

        return Media::query()->create([
            'filename' => $finalFileName,
            'original_name' => $attributes['original_name'] ?? $baseName,
            'mime_type' => $mimeType,
            'extension' => $extension,
            'disk' => $disk,
            'path' => $finalPath,
            'size' => $size,
            'alt' => $attributes['alt'] ?? null,
            'caption' => $attributes['caption'] ?? null,
            'created_by' => $attributes['created_by'] ?? auth()->id(),
        ]);
    }

    private function validateStoredUpload(string $extension, string $mimeType, int $size): void
    {
        $type = $this->resolveFileType($mimeType);

        if (! isset(self::ALLOWED_EXTENSIONS[$type], self::ALLOWED_MIME_TYPES[$type], self::FILE_SIZE_LIMITS[$type])) {
            throw ValidationException::withMessages([
                'upload' => 'Unsupported file type. Only image, PDF, and video are allowed.',
            ]);
        }

        if (! in_array($mimeType, self::ALLOWED_MIME_TYPES[$type], true)) {
            throw ValidationException::withMessages([
                'upload' => 'Invalid MIME type for uploaded file.',
            ]);
        }

        if (! in_array($extension, self::ALLOWED_EXTENSIONS[$type], true)) {
            throw ValidationException::withMessages([
                'upload' => 'Invalid file extension for uploaded file type.',
            ]);
        }

        if ($size > self::FILE_SIZE_LIMITS[$type]) {
            throw ValidationException::withMessages([
                'upload' => sprintf(
                    'File size exceeds the %s limit for %s files.',
                    $this->formatStorageSize(self::FILE_SIZE_LIMITS[$type]),
                    strtoupper($type),
                ),
            ]);
        }
    }

    public function rename(Media $media, string $newName): Media
    {
        $normalizedName = trim($newName);

        if ($normalizedName === '') {
            throw new InvalidArgumentException('Media name cannot be empty.');
        }

        $directory = dirname($media->path);
        $finalFileName = $this->makeFileName($directory, $normalizedName, $media->extension, $media->disk);
        $finalPath = $directory . '/' . $finalFileName;

        if ($finalPath !== $media->path) {
            Storage::disk($media->disk)->move($media->path, $finalPath);
        }

        $media->fill([
            'filename' => $finalFileName,
            'original_name' => $normalizedName,
            'path' => $finalPath,
        ]);
        $media->save();

        return $media->refresh();
    }

    public function delete(Media $media): bool
    {
        if (Storage::disk($media->disk)->exists($media->path)) {
            Storage::disk($media->disk)->delete($media->path);
        }

        return (bool) $media->delete();
    }

    public function previewUrl(Media $media): string
    {
        return Storage::disk($media->disk)->url($media->path);
    }

    public function previewImageSource(Media $media): string
    {
        if (! $media->is_image) {
            return $this->previewUrl($media);
        }

        if (! Storage::disk($media->disk)->exists($media->path)) {
            return $this->previewUrl($media);
        }

        $contents = Storage::disk($media->disk)->get($media->path);

        return sprintf('data:%s;base64,%s', $media->mime_type, base64_encode($contents));
    }

    public function previewPdfSource(Media $media): string
    {
        if (! $media->is_pdf) {
            return $this->previewUrl($media);
        }

        if (! Storage::disk($media->disk)->exists($media->path)) {
            return $this->previewUrl($media);
        }

        $contents = Storage::disk($media->disk)->get($media->path);

        return sprintf('data:%s;base64,%s', $media->mime_type, base64_encode($contents));
    }

    private function mimeTypeFromExtension(string $extension): string
    {
        return match (strtolower($extension)) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'pdf' => 'application/pdf',
            'mp4' => 'video/mp4',
            'mov' => 'video/quicktime',
            'webm' => 'video/webm',
            default => 'application/octet-stream',
        };
    }
}