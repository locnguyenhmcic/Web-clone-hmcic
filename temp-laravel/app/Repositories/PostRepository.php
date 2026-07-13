<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository
{
    public function create(array $data): Post
    {
        return Post::query()->create($data);
    }

    public function update(Post $post, array $data): Post
    {
        $post->update($data);

        return $post->refresh();
    }

    public function getPaginated(int $perPage = 12, int $page = 1, array $filters = [])
    {
        $query = Post::query()->with(['thumbnail', 'categories', 'creator']);

        // Status filter
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Category filter
        if (isset($filters['category_id'])) {
            $categoryId = $filters['category_id'];

            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        if (!empty($filters['search'])) {
            $search = trim((string) $filters['search']);

            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // Order by published date descending
        $query->orderByDesc('published_at')->orderByDesc('created_at');

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function findPublishedBySlug(string $slug): ?Post
    {
        return Post::query()
            ->with(['thumbnail', 'categories', 'creator'])
            ->where('status', 'published')
            ->where('slug', $slug)
            ->first();
    }

    public function getRelatedPublished(Post $post, int $limit = 3)
    {
        $categoryIds = $post->categories()->pluck('categories.id');

        return Post::query()
            ->with(['thumbnail', 'categories'])
            ->where('status', 'published')
            ->whereKeyNot($post->id)
            ->when(
                $categoryIds->isNotEmpty(),
                fn ($query) => $query->whereHas('categories', fn ($categoryQuery) => $categoryQuery->whereIn('categories.id', $categoryIds))
            )
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }
}
