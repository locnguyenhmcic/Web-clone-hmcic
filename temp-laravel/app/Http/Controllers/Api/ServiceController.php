<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Cache;

class ServiceController extends Controller
{
    public function __construct(private CategoryRepository $repository)
    {
    }

    public function index()
    {
        $services = Cache::remember('services_list', 3600, function () {
            return $this->repository->getAll();
        });

        return response()->json([
            'data' => $services->map(fn ($service) => [
                'id' => $service->id,
                'name' => $service->name,
                'slug' => $service->slug,
            ]),
        ]);
    }
}
