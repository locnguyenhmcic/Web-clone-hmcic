<?php

namespace App\Repositories;

use App\Models\Project;

class ProjectRepository
{
    public function create(array $data): Project
    {
        return Project::query()->create($data);
    }

    public function update(Project $project, array $data): Project
    {
        $project->update($data);

        return $project->refresh();
    }

    public function getPaginated(int $perPage = 12, int $page = 1, array $filters = [])
    {
        $query = Project::query()->with(['thumbnail', 'creator']);

        // Status filter
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Featured filter
        if (isset($filters['featured']) && $filters['featured']) {
            $query->where('featured', true);
        }

        // Order by published date descending
        $query->orderByDesc('published_at')->orderByDesc('created_at');

        return $query->paginate($perPage, ['*'], 'page', $page);
    }
}
