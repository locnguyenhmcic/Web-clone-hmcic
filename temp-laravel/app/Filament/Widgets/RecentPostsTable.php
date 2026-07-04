<?php

namespace App\Filament\Widgets;

use App\Services\DashboardService;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentPostsTable extends TableWidget
{
    protected static ?string $heading = 'Danh sach bai viet moi';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(app(DashboardService::class)->getRecentPostsQuery())
            ->columns([
                TextColumn::make('title')
                    ->label('Tieu de')
                    ->searchable()
                    ->limit(80),
                TextColumn::make('status')
                    ->label('Trang thai')
                    ->badge(),
                TextColumn::make('published_at')
                    ->label('Ngay dang')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-'),
                TextColumn::make('created_at')
                    ->label('Ngay tao')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5);
    }
}
