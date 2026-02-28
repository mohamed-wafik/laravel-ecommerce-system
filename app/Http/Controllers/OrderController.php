<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use App\Mail\OrderStatusMail;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function index()
    {
        $countOrders = Order::count();
        $countOfOrderPending = Order::where("order_status", "pending")->count();
        $countOfOrderCompleted = Order::where("order_status", "completed")->count();

        $totalRevenue = Order::sum("total");
        
        $orders = Order::with(["user","items.product"])
                    ->orderBy("created_at","desc")
                    ->paginate(10);

        return view("dashboard.orders.index",
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
    public function updateStatus(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                "order_status" => "required|in:pending,processing,completed,cancelled",
            ]);

            $order = Order::findOrFail($id);
            $order->order_status = $validated["order_status"];
            $order->save();

            return response()->json([
                "success" => true,
                "message" => "Order status updated successfully",
                "data" => $order
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                "success" => false,
                "message" => "Order not found"
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 422);
        }
    }
    public function destory($id)
    {
        $order = Order::findorFail($id);
        if(!$order) {
            return redirect()
                    ->back()
                    ->with("error", "Order not found");
        }
        if($order->order_status !== 'cancelled') {
            return redirect()
                    ->back()
                    ->with("error", "Only cancelled orders can be deleted");
        }
        if(!empty($order->items)) {
            $order->items()->delete();
        }
        $order->delete();
        return redirect()
                ->back()
                ->with("success", "Order deleted successfully");
    }
    public function exportOrder() {
        return Excel::download(new OrdersExport, 'orders.xlsx');
    }
    public function printOrder($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);

        $pdf = Pdf::loadView('dashboard.orders.invoice', compact('order'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("invoice_order_{$order->id}.pdf");
    }
    public function resetEmail($id)
    {
        $order = Order::with('user')->findOrFail($id);
        if (!$order) {
            return redirect()
                ->back()
                ->with('error', 'Order not found');
        }
        
        Mail::to($order->customer_email)->send(new OrderStatusMail($order));

        return redirect()
            ->back()
            ->with('success', 'Order status email resent successfully');
    }
}