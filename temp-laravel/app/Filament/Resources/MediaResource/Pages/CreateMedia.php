<?php

namespace App\Filament\Resources\MediaResource\Pages;

use App\Filament\Resources\MediaResource;
use App\Services\MediaService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateMedia extends CreateRecord
{
    protected static string $resource = MediaResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(MediaService::class)->createFromStoredUpload($data['upload'], [
            'original_name' => $data['original_name'] ?? null,
            'alt' => $data['alt'] ?? null,
            'caption' => $data['caption'] ?? null,
            'created_by' => auth()->id(),
        ]);
    }
}
