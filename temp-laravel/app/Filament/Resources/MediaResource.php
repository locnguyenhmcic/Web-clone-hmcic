<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaResource\Pages;
use App\Models\Media;
use App\Services\MediaService;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Media Library';

    protected static ?string $modelLabel = 'Media';

    protected static ?string $pluralModelLabel = 'Media Library';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Section::make('Upload')
                    ->schema([
                        FileUpload::make('upload')
                            ->label('File')
                            ->disk('public')
                            ->directory(fn (): string => static::storageDirectory())
                            ->preserveFilenames()
                            ->maxSize(512000)
                            ->acceptedFileTypes([
                                'image/*',
                                'application/pdf',
                                'video/*',
                            ])
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->visible(fn (string $operation): bool => $operation === 'create')
                            ->helperText('Allowed: image (max 20 MB), PDF (max 50 MB), video (max 500 MB).'),
                        TextInput::make('original_name')
                            ->label('Tên media')
                            ->required(fn (string $operation): bool => $operation === 'edit')
                            ->visible(fn (string $operation): bool => $operation === 'edit')
                            ->maxLength(255),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('alt')
                                    ->maxLength(255),
                                TextInput::make('caption')
                                    ->maxLength(65535),
                            ]),
                    ]),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        $mediaService = app(MediaService::class);

        return $table
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->columns([
                ImageColumn::make('path')
                    ->label('Preview')
                    ->disk(fn (Media $record): string => $record->disk)
                    ->getStateUsing(fn (Media $record): ?string => $record->is_image ? $record->path : null)
                    ->defaultImageUrl(fn (Media $record): string => static::mediaCardPlaceholder($record))
                    ->height(120)
                    ->extraImgAttributes([
                        'class' => 'w-full rounded-lg object-cover bg-gray-100',
                    ]),
                TextColumn::make('original_name')
                    ->label('Name')
                    ->searchable(['original_name', 'filename'])
                    ->sortable()
                    ->limit(40),
                TextColumn::make('mime_type')
                    ->label('Type')
                    ->formatStateUsing(fn (string $state): string => $mediaService->resolveFileTypeLabel($state))
                    ->sortable(query: fn (Builder $query, string $direction): Builder => $query->orderByRaw("case when mime_type like 'image/%' then 1 when mime_type = 'application/pdf' then 2 when mime_type like 'video/%' then 3 else 4 end {$direction}")),
                TextColumn::make('size')
                    ->label('Size')
                    ->formatStateUsing(fn (string|int|null $state): string => $mediaService->formatStorageSize((int) $state))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Uploaded')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('File type')
                    ->options([
                        'image' => 'Image',
                        'pdf' => 'PDF',
                        'video' => 'Video',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;

                        return match ($value) {
                            'image' => $query->where('mime_type', 'like', 'image/%'),
                            'pdf' => $query->where('mime_type', 'application/pdf'),
                            'video' => $query->where('mime_type', 'like', 'video/%'),
                            default => $query,
                        };
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn (Media $record): string => $record->original_name)
                    ->modalWidth('7xl')
                    ->modalSubmitAction(false)
                    ->modalContent(fn (Media $record) => view('filament.media.preview', [
                        'record' => $record,
                    ])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMedia::route('/'),
            'create' => Pages\CreateMedia::route('/create'),
            'edit' => Pages\EditMedia::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->latest('created_at');
    }

    protected static function storageDirectory(): string
    {
        return 'uploads/tmp/' . now()->format('Y/m');
    }

    protected static function mediaCardPlaceholder(Media $record): string
    {
        $label = match (true) {
            $record->is_pdf => 'PDF',
            $record->is_video => 'VIDEO',
            default => 'FILE',
        };

        $svg = sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" width="480" height="300"><rect width="100%%" height="100%%" fill="#f3f4f6"/><text x="50%%" y="50%%" text-anchor="middle" dominant-baseline="middle" font-family="Arial, sans-serif" font-size="32" fill="#111827">%s</text></svg>',
            $label,
        );

        return 'data:image/svg+xml;utf8,' . rawurlencode($svg);
    }
}
