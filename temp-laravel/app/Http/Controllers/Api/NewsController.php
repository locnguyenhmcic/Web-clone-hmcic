<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsCollection;
use App\Repositories\PostRepository;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function __construct(private PostRepository $repository)
    {
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 6);
        $page = $request->get('page', 1);
        $categoryId = $request->get('category_id');

        $news = $this->repository->getPaginated($perPage, $page, [
            'status' => 'published',
            'category_id' => $categoryId,
        ]);

        return new NewsCollection($news);
    }
}
