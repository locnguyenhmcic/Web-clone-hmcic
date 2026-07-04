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
            $query->whereHas('categories', function ($q) {
                $q->where('category_id', request('category_id'));
            });
        }

        // Order by published date descending
        $query->orderByDesc('published_at')->orderByDesc('created_at');

        return $query->paginate($perPage, ['*'], 'page', $page);
    }
}
