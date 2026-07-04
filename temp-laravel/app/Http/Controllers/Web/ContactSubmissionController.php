<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactSubmissionRequest;
use App\Services\ContactSubmissionService;
use Illuminate\Http\JsonResponse;

class ContactSubmissionController extends Controller
{
    public function __invoke(StoreContactSubmissionRequest $request, ContactSubmissionService $contactSubmissionService): JsonResponse
    {
        $result = $contactSubmissionService->submit($request->validated());

        return response()->json([
            'message' => 'Contact submitted successfully.',
            'data' => $result,
        ]);
    }
}