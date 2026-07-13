<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsCollection;
use App\Models\Category;
use App\Repositories\PostRepository;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function __construct(private PostRepository $repository)
    {
    }

    private function thumbnailUrl(Request $request, ?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return rtrim($request->getSchemeAndHttpHost(), '/') . '/storage/' . ltrim($path, '/');
    }

    private function transformNewsItem(Request $request, $item): array
    {
        return [
            'id' => $item->id,
            'title' => $item->title,
            'slug' => $item->slug,
            'excerpt' => $item->excerpt,
            'thumbnail_url' => $this->thumbnailUrl($request, $item->thumbnail?->path),
            'category' => optional($item->categories->first())->name,
            'created_at' => $item->published_at?->format('Y-m-d'),
        ];
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 6);
        $page = $request->get('page', 1);
        $categoryId = $request->get('category_id');
        $search = $request->get('search');

        $news = $this->repository->getPaginated($perPage, $page, [
            'status' => 'published',
            'category_id' => $categoryId,
            'search' => $search,
        ]);

        return new NewsCollection($news);
    }

    public function categories()
    {
        $categories = Category::query()
            ->whereHas('posts', function ($query) {
                $query->where('status', 'published');
            })
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        return response()->json([
            'data' => $categories,
        ]);
    }

    public function show(Request $request, string $slug)
    {
        $post = $this->repository->findPublishedBySlug($slug);

        if (! $post) {
            abort(404);
        }

        $relatedPosts = $this->repository->getRelatedPublished($post);

        return response()->json([
            'data' => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => $post->excerpt,
                'content' => $post->content,
                'thumbnail_url' => $this->thumbnailUrl($request, $post->thumbnail?->path),
                'category' => optional($post->categories->first())->name,
                'created_at' => $post->published_at?->format('Y-m-d'),
                'related_posts' => $relatedPosts->map(fn ($item) => $this->transformNewsItem($request, $item))->values(),
            ],
        ]);
    }
}
