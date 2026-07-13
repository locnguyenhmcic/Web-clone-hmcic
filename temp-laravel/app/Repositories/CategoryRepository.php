<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Throwable;

class CategoryRepository
{
    public function allOrdered(): Collection
    {
        try {
            return Cache::remember('categories_list', 3600, function () {
                return Category::orderBy('name')->get();
            });
        } catch (Throwable $exception) {
            report($exception);

            return $this->getAllWithoutCache();
        }
    }

    public function getAll(): Collection
    {
        return $this->allOrdered();
    }

    public function getAllWithoutCache(): Collection
    {
        return Category::orderBy('name')->get();
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);

        return $category->refresh();
    }

    public function isSlugUnique(string $slug, ?int $excludeId = null): bool
    {
        $query = Category::where('slug', $slug);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return ! $query->exists();
    }
}
