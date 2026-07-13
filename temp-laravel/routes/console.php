<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Models\Media;
use App\Models\Post;
use App\Models\Project;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('media:audit-missing {--limit=50 : Max missing rows shown in table}', function () {
    $limit = max(1, (int) $this->option('limit'));

    $missingRows = [];

    $pushMissing = function (string $type, int $id, string $title, ?Media $media) use (&$missingRows): void {
        if (!$media || !$media->path) {
            return;
        }

        $disk = $media->disk ?: 'public';

        if (!Storage::disk($disk)->exists($media->path)) {
            $missingRows[] = [
                'type' => $type,
                'id' => $id,
                'title' => mb_strimwidth($title, 0, 60, '...'),
                'media_id' => $media->id,
                'disk' => $disk,
                'path' => $media->path,
            ];
        }
    };

    Post::query()->with('thumbnail')->chunk(200, function ($posts) use ($pushMissing) {
        foreach ($posts as $post) {
            $pushMissing('post', $post->id, (string) $post->title, $post->thumbnail);
        }
    });

    Project::query()->with('thumbnail')->chunk(200, function ($projects) use ($pushMissing) {
        foreach ($projects as $project) {
            $pushMissing('project', $project->id, (string) $project->title, $project->thumbnail);
        }
    });

    $orphanMedia = Media::query()
        ->select(['id', 'disk', 'path', 'original_name'])
        ->whereNotNull('path')
        ->get()
        ->filter(function (Media $media) {
            $disk = $media->disk ?: 'public';
            return !Storage::disk($disk)->exists($media->path);
        });

    $this->newLine();
    $this->info('Media audit summary');
    $this->line('- Missing thumbnails in post/project records: ' . count($missingRows));
    $this->line('- Missing files in media table: ' . $orphanMedia->count());

    if (!empty($missingRows)) {
        $this->newLine();
        $this->warn('Missing thumbnails (first ' . $limit . ' rows):');
        $this->table(
            ['Type', 'ID', 'Title', 'Media ID', 'Disk', 'Path'],
            array_slice($missingRows, 0, $limit)
        );
    }

    if ($orphanMedia->isNotEmpty()) {
        $this->newLine();
        $this->warn('Missing media rows (first ' . $limit . ' rows):');
        $rows = $orphanMedia->take($limit)->map(function (Media $media) {
            return [
                'media_id' => $media->id,
                'disk' => $media->disk ?: 'public',
                'path' => $media->path,
                'name' => mb_strimwidth((string) $media->original_name, 0, 50, '...'),
            ];
        })->values()->all();

        $this->table(['Media ID', 'Disk', 'Path', 'Original Name'], $rows);
    }

    if (empty($missingRows) && $orphanMedia->isEmpty()) {
        $this->newLine();
        $this->info('All referenced media files exist on disk.');
        return self::SUCCESS;
    }

    $this->newLine();
    $this->error('Missing media detected. Re-upload missing files or mount persistent storage.');

    return self::FAILURE;
})->purpose('Audit missing media files referenced by posts/projects and media table');
