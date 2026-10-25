<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $payments
    ) {}

    public function index(Request $request)
    {
        return $this->paginatedSuccess(
            $this->payments->paginate(
                $request->integer("per_page",15),
                $request->only([
                    'status',
                    'order_id',
                    'provider',
                ])
            )
        );
    }

    public function show(string $id)
    {
        return $this->success(
            $this->payments->find($id)
        );
    }


}