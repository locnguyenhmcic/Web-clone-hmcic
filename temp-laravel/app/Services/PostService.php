<?php

namespace App\Services;

use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class PostService
{
    public function __construct(
        private readonly PostRepository $repository,
    ) {
    }

    public function create(array $data): Post
    {
        $hasCategories = array_key_exists('categories', $data);
        $categories = $hasCategories ? Arr::pull($data, 'categories', []) : [];

        $data['slug'] = $this->resolveSlug((string) ($data['slug'] ?? ''), (string) ($data['title'] ?? ''));
        $data['published_at'] = $data['published_at'] ?? now();
        $data = $this->resolveSeoData($data);
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        $post = $this->repository->create($data);
        if ($hasCategories) {
            $post->categories()->sync($categories);
        }

        return $post->refresh();
    }

    public function update(Post $post, array $data): Post
    {
        $hasCategories = array_key_exists('categories', $data);
        $categories = $hasCategories ? Arr::pull($data, 'categories', []) : [];

        $data['slug'] = $this->resolveSlug((string) ($data['slug'] ?? ''), (string) ($data['title'] ?? ''), $post->id);
        $data = $this->resolveSeoData($data);
        $data['updated_by'] = auth()->id();

        $post = $this->repository->update($post, $data);
        if ($hasCategories) {
            $post->categories()->sync($categories);
        }

        return $post->refresh();
    }

    private function resolveSlug(string $slug, string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug !== '' ? $slug : $title);

        if ($base === '') {
            $base = 'post';
        }

        $candidate = $base;
        $counter = 1;

        while (Post::query()
            ->when($ignoreId !== null, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->where('slug', $candidate)
            ->exists()) {
            $candidate = $base . '-' . $counter;
            $counter++;
        }

        return $candidate;
    }

    private function resolveSeoData(array $data): array
    {
        $title = trim((string) ($data['title'] ?? ''));
        $excerpt = trim((string) ($data['excerpt'] ?? ''));
        $content = trim(strip_tags((string) ($data['content'] ?? '')));
        $slug = trim((string) ($data['slug'] ?? ''));

        $seoTitle = trim((string) ($data['seo_title'] ?? ''));
        $seoDescription = trim((string) ($data['seo_description'] ?? ''));
        $canonicalUrl = trim((string) ($data['canonical_url'] ?? ''));

        if ($seoTitle === '' && $title !== '') {
            $data['seo_title'] = Str::limit($title, 255, '');
        }

        if ($seoDescription === '') {
            $fallbackDescription = $excerpt !== '' ? $excerpt : $content;
            $data['seo_description'] = $fallbackDescription !== ''
                ? Str::limit($fallbackDescription, 160, '')
                : null;
        }

        if ($canonicalUrl === '' && $slug !== '') {
            $baseUrl = rtrim((string) config('app.url', ''), '/');
            $data['canonical_url'] = $baseUrl !== ''
                ? $baseUrl . '/posts/' . $slug
                : null;
        }

        if (($data['seo_title'] ?? null) === '') {
            $data['seo_title'] = null;
        }

        if (($data['seo_description'] ?? null) === '') {
            $data['seo_description'] = null;
        }

        if (($data['canonical_url'] ?? null) === '') {
            $data['canonical_url'] = null;
        }

        return $data;
    }
}
