<?php

namespace App\Services;

use App\Repositories\DashboardRepository;
use Illuminate\Database\Eloquent\Builder;

class DashboardService
{
    public function __construct(
        protected DashboardRepository $dashboardRepository,
    ) {
    }

    public function getTotals(): array
    {
        $totals = $this->dashboardRepository->getTotals();

        $totals['media_storage_human_readable'] = $this->formatStorageSize($totals['media_storage_bytes']);

        return $totals;
    }

    public function getRecentPostsQuery(): Builder
    {
        return $this->dashboardRepository->getRecentPostsQuery();
    }

    private function formatStorageSize(int $bytes): string
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
}
