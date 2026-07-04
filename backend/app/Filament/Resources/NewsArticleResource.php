<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsArticleResource\Pages;
use App\Models\NewsArticle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class NewsArticleResource extends Resource
{
	protected static ?string $model = NewsArticle::class;

	protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

	protected static ?string $navigationLabel = 'Posts';

	protected static ?string $modelLabel = 'Post';

	protected static ?string $pluralModelLabel = 'Posts';

	public static function form(Form $form): Form
	{
		return $form->schema([
			Forms\Components\Section::make('Basic Information')
				->schema([
					Forms\Components\TextInput::make('title')
						->required()
						->maxLength(255)
						->live(onBlur: true)
						->afterStateUpdated(function (string $operation, ?string $state, Forms\Set $set): void {
							if ($operation !== 'create') {
								return;
							}

							$set('slug', Str::slug((string) $state));
						}),
					Forms\Components\TextInput::make('slug')
						->required()
						->maxLength(255)
						->unique(ignoreRecord: true),
					Forms\Components\FileUpload::make('thumbnail_path')
						->label('Thumbnail')
						->disk('public')
						->directory('thumbnail')
						->image()
						->imageEditor()
						->nullable(),
					Forms\Components\Select::make('serviceCategories')
						->label('Categories')
						->relationship('serviceCategories', 'name')
						->multiple()
						->preload()
						->searchable()
						->required(),
					Forms\Components\RichEditor::make('content')
						->required()
						->columnSpanFull(),
				])
				->columns(2),
			Forms\Components\Section::make('Publish')
				->schema([
					Forms\Components\Select::make('status')
						->options([
							'draft' => 'Draft',
							'published' => 'Published',
							'hidden' => 'Hidden',
						])
						->required()
						->default('draft'),
					Forms\Components\DateTimePicker::make('published_at')
						->label('Publish Date')
						->seconds(false),
				])
				->columns(2),
			Forms\Components\Section::make('SEO')
				->schema([
					Forms\Components\TextInput::make('seo_title')
						->maxLength(255),
					Forms\Components\Textarea::make('seo_description')
						->rows(3)
						->maxLength(500),
					Forms\Components\TextInput::make('canonical_url')
						->url()
						->maxLength(2048),
				])
				->columns(1),
		]);
	}

	public static function table(Table $table): Table
	{
		return $table
			->columns([
				Tables\Columns\ImageColumn::make('thumbnail_path')
					->label('Thumbnail')
					->disk('public')
					->square(),
				Tables\Columns\TextColumn::make('title')
					->label('Title')
					->searchable()
					->sortable()
					->limit(70),
				Tables\Columns\TextColumn::make('serviceCategories.name')
					->label('Category')
					->badge()
					->separator(', ')
					->toggleable(),
				Tables\Columns\TextColumn::make('status')
					->badge()
					->formatStateUsing(fn (string $state): string => Str::headline($state))
					->color(fn (string $state): string => match ($state) {
						'published' => 'success',
						'hidden' => 'warning',
						default => 'gray',
					})
					->sortable(),
				Tables\Columns\TextColumn::make('published_at')
					->label('Publish Date')
					->dateTime('d/m/Y H:i')
					->sortable(),
			])
			->filters([
				SelectFilter::make('service_category')
					->label('Category')
					->relationship('serviceCategories', 'name')
					->searchable()
					->preload(),
				SelectFilter::make('status')
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
			->defaultPaginationPageOption(10)
			->paginationPageOptions([10, 25, 50]);
	}

	public static function getRelations(): array
	{
		return [];
	}

	public static function getPages(): array
	{
		return [
			'index' => Pages\ListNewsArticles::route('/'),
			'create' => Pages\CreateNewsArticle::route('/create'),
			'edit' => Pages\EditNewsArticle::route('/{record}/edit'),
		];
	}

	public static function getEloquentQuery(): Builder
	{
		return parent::getEloquentQuery()->withoutGlobalScopes([
			\Illuminate\Database\Eloquent\SoftDeletingScope::class,
		]);
	}
}

