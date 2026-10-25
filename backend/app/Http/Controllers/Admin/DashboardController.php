<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboard) {}

    public function index(Request $request): JsonResponse
    {
        $days = min(
            max((int) $request->integer('days', 30), 7),
            365
        );
        return $this->success($this->dashboard->getDashboard($days));
    }
}