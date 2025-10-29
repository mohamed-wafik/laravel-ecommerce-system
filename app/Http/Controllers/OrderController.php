<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $countOrders = Order::count();
        $countOfOrderPending = Order::where("status", "pending")->count();
        $countOfOrderCompleted = Order::where("status", "completed")->count();

        $totalRevenue = Order::sum("total_amount");

        $orders = Order::with(["user", "itemOrders"])->latest()->paginate(10);

        return view("dashboard.orders",
         compact(
            "countOrders",
            "countOfOrderPending",
            "countOfOrderCompleted",
            "totalRevenue",
            "orders"
        ));
    }
    public function show($id) {
        $order = Order::with(["user","products"])->find($id);
        return view("dashboard.orders.show",compact("order"));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
            'country' => 'nullable|string|max:100',
            'total_amount' => 'nullable|numeric|min:0',
        ]);

        $order = Order::find($id);
        $order->update($validated);

        return redirect()
            ->back()
            ->with('success', 'Order updated successfully!');
    }
}

