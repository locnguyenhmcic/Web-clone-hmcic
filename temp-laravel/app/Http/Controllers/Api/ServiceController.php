<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Cache;
use Throwable;

class ServiceController extends Controller
{
    public function __construct(private CategoryRepository $repository)
    {
    }

    public function index()
    {
        try {
            $services = Cache::remember('services_list', 3600, function () {
                return $this->repository->getAll();
            });
        } catch (Throwable $exception) {
            report($exception);
            $services = $this->repository->getAllWithoutCache();
        }

        return response()->json([
            'data' => $services->map(fn ($service) => [
                'id' => $service->id,
                'name' => $service->name,
                'slug' => $service->slug,
            ]),
        ]);
    }
}
