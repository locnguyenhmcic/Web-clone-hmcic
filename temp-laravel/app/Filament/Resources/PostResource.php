<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use App\Models\Media;
use App\Services\MediaService;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Posts';

    protected static ?string $modelLabel = 'Post';

    protected static ?string $pluralModelLabel = 'Posts';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Basic Information')
                ->schema([
                    TextInput::make('title')
                        ->label('Title')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, ?string $state, Set $set): void {
                            if ($operation === 'create' && $state !== null) {
                                $set('slug', Str::slug($state));
                            }
                        }),
                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(table: 'posts', column: 'slug', ignoreRecord: true),
                    Select::make('thumbnail_id')
                        ->label('Thumbnail')
                        ->relationship(
                            name: 'thumbnail',
                            titleAttribute: 'original_name',
                            modifyQueryUsing: fn (EloquentBuilder $query): EloquentBuilder => $query
                                ->where('mime_type', 'like', 'image/%')
                                ->orderByDesc('created_at')
                        )
                        ->getOptionLabelFromRecordUsing(fn ($record): string => $record->original_name)
                        ->searchable()
                        ->preload()
                        ->helperText('Choose an image from Media Library for the article thumbnail.')
                        ->nullable(),
                    Select::make('categories')
                        ->label('Category')
                        ->relationship('categories', 'name')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->required(),
                    RichEditor::make('content')
                        ->label('Content')
                        ->required()
                        ->columnSpanFull()
                        ->toolbarButtons([
                            'h2',
                            'h3',
                            'bold',
                            'italic',
                            'underline',
                            'link',
                            'orderedList',
                            'bulletList',
                            'blockquote',
                            'codeBlock',
                            'attachFiles',
                            'undo',
                            'redo',
                        ])
                        ->fileAttachmentsDisk('public')
                        ->fileAttachmentsVisibility('public')
                        ->saveUploadedFileAttachmentsUsing(function (TemporaryUploadedFile $file): Media {
                            $mimeType = $file->getMimeType() ?? '';

                            $isSupportedType = str_starts_with($mimeType, 'image/')
                                || $mimeType === 'application/pdf'
                                || str_starts_with($mimeType, 'video/');

                            if (! $isSupportedType) {
                                throw ValidationException::withMessages([
                                    'content' => 'Rich editor only supports image, PDF, and video attachments.',
                                ]);
                            }

                            $storedPath = $file->storePublicly('editor/tmp', 'public');

                            return app(MediaService::class)->createFromStoredUpload($storedPath, [
                                'disk' => 'public',
                                'original_name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                                'created_by' => auth()->id(),
                            ]);
                        })
                        ->getUploadedAttachmentUrlUsing(fn (Media $file): string => $file->url)
                        ->helperText('Formatting tools support headings, text styling, lists, links, quotes, code blocks, and media embedding (image, PDF, video).')
                ])
                ->columns(2),
            Section::make('Publish')
                ->schema([
                    Select::make('status')
                        ->label('Status')
                        ->required()
                        ->options([
                            'draft' => 'Draft',
                            'published' => 'Published',
                            'hidden' => 'Hidden',
                        ])
                        ->default('draft'),
                    DateTimePicker::make('published_at')
                        ->label('Publish Date')
                        ->default(fn (string $operation): ?string => $operation === 'create' ? now()->format('Y-m-d H:i') : null)
                        ->seconds(false),
                ])
                ->columns(2),
            Section::make('SEO')
                ->schema([
                    TextInput::make('seo_title')
                        ->label('SEO Title')
                        ->maxLength(255),
                    Textarea::make('seo_description')
                        ->label('SEO Description')
                        ->rows(3)
                        ->maxLength(500),
                    TextInput::make('canonical_url')
                        ->label('Canonical URL')
                        ->url()
                        ->maxLength(2048),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail.path')
                    ->label('Thumbnail')
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->limit(70),
                TextColumn::make('categories.name')
                    ->label('Category')
                    ->badge()
                    ->separator(', '),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Str::headline($state))
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'hidden' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('published_at')
                    ->label('Publish Date')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Category')
                    ->relationship('categories', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'hidden' => 'Hidden',
                    ]),
                Filter::make('published_at')
                    ->label('Publish Date')
                    ->form([
                        Forms\Components\DatePicker::make('published_from')->label('From'),
                        Forms\Components\DatePicker::make('published_until')->label('Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['published_from'] ?? null,
                                fn (Builder $builder, string $date): Builder => $builder->whereDate('published_at', '>=', $date)
                            )
                            ->when(
                                $data['published_until'] ?? null,
                                fn (Builder $builder, string $date): Builder => $builder->whereDate('published_at', '<=', $date)
                            );
                    }),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('published_at', 'desc')
            ->paginationPageOptions([10, 25, 50])
            ->defaultPaginationPageOption(10);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
    }
}
