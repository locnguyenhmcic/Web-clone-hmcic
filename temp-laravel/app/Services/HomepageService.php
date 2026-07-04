<?php

namespace App\Services;

use App\Repositories\PostRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Cache;

class HomepageService
{
    public function __construct(
        private PostRepository $postRepository,
        private ProjectRepository $projectRepository,
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function getHomepageData()
    {
        return Cache::remember('homepage_data', 3600, function () {
            return [
                'recent_news' => $this->getRecentNews(),
                'featured_projects' => $this->getFeaturedProjects(),
                'services' => $this->getServices(),
            ];
        });
    }

    public function getRecentNews()
    {
        return $this->postRepository
            ->getPaginated(perPage: 6, filters: ['status' => 'published'])
            ->items();
    }

    public function getFeaturedProjects()
    {
        return $this->projectRepository
            ->getPaginated(perPage: 6, filters: ['status' => 'published', 'featured' => true])
            ->items();
    }

    public function getServices()
    {
        return $this->categoryRepository->getAll();
    }
}
