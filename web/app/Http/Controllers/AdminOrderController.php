<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Helpers\NotificationHelper;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items']);
        
        // Lọc theo trạng thái
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Tìm kiếm theo mã đơn hoặc tên khách hàng
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('customer_phone', 'LIKE', "%{$search}%");
            });
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with(['user', 'items.book.author', 'items.book.publisher'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,delivered,canceled,waiting_pickup',
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->save();

        // Gửi thông báo nếu có thay đổi status
        if ($oldStatus != $request->status && $order->user_id) {
            NotificationHelper::orderStatusChanged($order, $oldStatus, $request->status);
        }

        return redirect()->route('orders.index')->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        
        // Hoàn trả số lượng sản phẩm về kho khi xóa đơn
        foreach ($order->items as $item) {
            $book = $item->book;
            $book->stock += $item->quantity;
            $book->save();
        }
        
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Đã xóa đơn hàng và hoàn trả số lượng vào kho!');
    }
}
