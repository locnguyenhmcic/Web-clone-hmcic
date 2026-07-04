<?php

namespace App\Filament\Widgets;

use App\Services\DashboardService;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totals = app(DashboardService::class)->getTotals();

        return [
            Stat::make('Tong so bai viet', number_format($totals['posts_count']))
                ->description('So bai viet hien co')
                ->icon('heroicon-o-document-text'),

            Stat::make('Tong so danh muc', number_format($totals['categories_count']))
                ->description('Tong danh muc da tao')
                ->icon('heroicon-o-folder'),

            Stat::make('Tong so media', number_format($totals['media_count']))
                ->description('So tep trong media library')
                ->icon('heroicon-o-photo'),

            Stat::make('Tong dung luong luu tru', $totals['media_storage_human_readable'])
                ->description('Tong dung luong file media')
                ->icon('heroicon-o-circle-stack'),
        ];
    }
}
