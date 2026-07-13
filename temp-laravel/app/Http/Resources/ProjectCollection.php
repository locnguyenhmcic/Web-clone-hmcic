<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectCollection extends ResourceCollection
{
    private function thumbnailUrl($request, $path): ?string
    {
        if (!$path) {
            return null;
        }

        return rtrim($request->getSchemeAndHttpHost(), '/') . '/storage/' . ltrim($path, '/');
    }

    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,
                    'excerpt' => $item->excerpt,
                    'thumbnail_url' => $this->thumbnailUrl(request(), $item->thumbnail?->path),
                    'created_at' => $item->published_at?->format('Y-m-d'),
                    'featured' => $item->featured,
                ];
            }),
            'pagination' => [
                'total' => $this->total(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'has_more' => $this->hasMorePages(),
            ],
        ];
    }
}
