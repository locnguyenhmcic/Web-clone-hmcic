<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function (Category $record, Actions\DeleteAction $action): void {
                    if ($record->children()->exists()) {
                        $action->cancel();
                        $action->failureNotificationTitle('Cannot delete a category that has sub-categories.');
                        $action->sendFailureNotification();
                    }
                }),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        /** @var Category $record */
        return app(CategoryService::class)->update($record, $data);
    }
}
