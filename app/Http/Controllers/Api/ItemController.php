<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\ItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function __construct(
        private ItemService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json($this->service->getByRequest($request));
    }
}
