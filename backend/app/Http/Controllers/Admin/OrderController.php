<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orders
    ) {}

    public function index(Request $request)
    {
        return $this->paginatedSuccess(
            $this->orders->paginate(
                $request->integer('per_page', 15),
                $request->only([
                    'search',
                    'status',
                    'user_id',
                    'date_from',
                    'date_to',
                ])
            )
        );
    }

    public function show(string $id)
    {
        return $this->success(
            $this->orders->find($id)
        );
    }

    public function updateStatus(Request $request, string $id)
    {
        $data = $request->validate([
            'status' => [
                'required',
                'in:pending,processing,paid,shipped,delivered,cancelled',
            ],
        ]);

        try {
            $order = $this->orders->updateStatus(
                $id,
                $data['status']
            );
        } catch (ValidationException $e) {
            return $this->validationError(
                $e->errors(),
                $e->getMessage()
            );
        }

        return $this->success(
            $order,
            'Order status updated successfully.'
        );
    }

    public function refund(string $id)
    {
        try {
            $order = $this->orders->refund($id);
        } catch (ValidationException $e) {
            return $this->validationError(
                $e->errors(),
                $e->getMessage()
            );
        }

        return $this->success(
            $order,
            'Order refunded successfully.'
        );
    }

    public function stats()
    {
        return $this->success(
            $this->orders->stats()
        );
    }
}