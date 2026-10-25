<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orders) {}

    public function index(Request $request)
    {
        return $this->paginatedSuccess($this->orders->list($request->user()));
    }

    public function show(Request $request, string $id)
    {
        return $this->success($this->orders->find($request->user(), $id));
    }
}
