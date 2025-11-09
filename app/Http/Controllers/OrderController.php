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
        $countOfOrderPending = Order::where("status", "pending")->count();
        $countOfOrderCompleted = Order::where("status", "completed")->count();

        $totalRevenue = Order::sum("total_amount");

        $orders = Order::with(["user", "itemOrders"])->latest()->paginate(10);

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
        $validated = $request->validate([
            "status" => "required|in:pending,processing,completed,cancelled",
        ]);

        $order = Order::findorFail($id);
        if(!$order) {
            return response()->json(["message" => "Order not found"], 404);
        }
        $order->status = $validated["status"];
        $order->save();
        return response()->json(["message" => "Order status updated successfully"]);
    }
    public function destory($id)
    {
        $order = Order::findorFail($id);
        if(!$order) {
            return redirect()
                    ->back()
                    ->with("error", "Order not found");
        }
        if($order->status !== 'cancelled') {
            return redirect()
                    ->back()
                    ->with("error", "Only cancelled orders can be deleted");
        }
        if(!empty($order->itemorders)) {
            $order->itemorders()->delete();
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
        $order = Order::with(['user', 'itemOrders.product'])->findOrFail($id);

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
        
        Mail::to($order->user->email)->send(new OrderStatusMail($order));

        return redirect()
            ->back()
            ->with('success', 'Order status email resent successfully');
    }
}