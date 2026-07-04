<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Str;

class CategoryService
{
    public function __construct(
        private readonly CategoryRepository $repository
    ) {}

    public function create(array $data): Category
    {
        $data['slug'] = $this->resolveSlug($data['slug'] ?? '', $data['name']);

        return $this->repository->create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $data['slug'] = $this->resolveSlug($data['slug'] ?? '', $data['name'], $category->id);

        return $this->repository->update($category, $data);
    }

    public function generateSlug(string $name): string
    {
        return Str::slug($name);
    }

    private function resolveSlug(string $slug, string $name, ?int $excludeId = null): string
    {
        $base = $slug !== '' ? Str::slug($slug) : Str::slug($name);
        $candidate = $base;
        $i = 1;

        while (! $this->repository->isSlugUnique($candidate, $excludeId)) {
            $candidate = $base . '-' . $i++;
        }

        return $candidate;
    }
}
