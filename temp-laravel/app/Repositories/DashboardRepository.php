<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Media;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;

class DashboardRepository
{
    public function getTotals(): array
    {
        return [
            'posts_count' => Post::query()->count(),
            'categories_count' => Category::query()->count(),
            'media_count' => Media::query()->count(),
            'media_storage_bytes' => (int) Media::query()->sum('size'),
        ];
    }

    public function getRecentPostsQuery(): Builder
    {
        return Post::query()
            ->latest('created_at');
    }
}
