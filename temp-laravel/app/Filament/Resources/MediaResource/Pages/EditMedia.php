<?php

namespace App\Filament\Resources\MediaResource\Pages;

use App\Filament\Resources\MediaResource;
use App\Models\Media;
use App\Services\MediaService;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditMedia extends EditRecord
{
    protected static string $resource = MediaResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        /** @var Media $record */
        $mediaService = app(MediaService::class);

        if (array_key_exists('original_name', $data) && $data['original_name'] !== $record->original_name) {
            $record = $mediaService->rename($record, (string) $data['original_name']);
        }

        $record->fill([
            'alt' => $data['alt'] ?? null,
            'caption' => $data['caption'] ?? null,
        ]);
        $record->save();

        return $record->refresh();
    }
}
