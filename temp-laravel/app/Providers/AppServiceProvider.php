<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Media;
use App\Models\Post;
use App\Policies\CategoryPolicy;
use App\Policies\MediaPolicy;
use App\Policies\PostPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Post::class, PostPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Media::class, MediaPolicy::class);
    }
}
