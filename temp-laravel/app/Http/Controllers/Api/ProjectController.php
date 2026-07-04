<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectCollection;
use App\Repositories\ProjectRepository;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(private ProjectRepository $repository)
    {
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 6);
        $page = $request->get('page', 1);
        $featured = $request->get('featured', false);

        $projects = $this->repository->getPaginated($perPage, $page, [
            'status' => 'published',
            'featured' => $featured,
        ]);

        return new ProjectCollection($projects);
    }
}
