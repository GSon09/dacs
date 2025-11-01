<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Helpers\NotificationHelper;

class CheckoutController extends Controller
{
    // Lấy hoặc tạo giỏ hàng
    private function getCart()
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->first();
        } else {
            $sessionId = Session::getId();
            return Cart::where('session_id', $sessionId)->first();
        }
    }

    // Hiển thị trang checkout
    public function index()
    {
        $cart = $this->getCart();
        
        if (!$cart || $cart->items()->count() == 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $cartItems = $cart->items()->with('book')->get();
        
        // Kiểm tra tồn kho trước khi checkout
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->book->stock) {
                return redirect()->route('cart.index')->with('error', 'Sản phẩm "' . $item->book->title . '" không đủ số lượng trong kho!');
            }
        }

        return view('checkout.index', compact('cart', 'cartItems'));
    }

    // Xử lý đặt hàng
    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
        ]);

        $cart = $this->getCart();
        
        if (!$cart || $cart->items()->count() == 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $cartItems = $cart->items()->with('book')->get();

        // Kiểm tra tồn kho
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->book->stock) {
                return redirect()->route('cart.index')->with('error', 'Sản phẩm "' . $item->book->title . '" không đủ số lượng trong kho!');
            }
        }

        DB::beginTransaction();
        
        try {
            // Tính tổng tiền
            $subtotal = $cart->total;
            $shippingFee = $subtotal >= 150000 ? 0 : 30000;
            $total = $subtotal + $shippingFee;

            // Tạo order
            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'price' => $subtotal,
                'tax' => 0,
                'total' => $total,
                'status' => 'pending',
                'order_date' => now(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'notes' => $request->notes,
            ]);

            // Tạo order items và trừ stock
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $item->book_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->price * $item->quantity,
                ]);

                // Trừ số lượng sản phẩm
                $book = Book::find($item->book_id);
                $book->stock -= $item->quantity;
                $book->save();
            }

            // Xóa giỏ hàng
            $cart->items()->delete();
            $cart->delete();

            // Tạo thông báo
            if ($order->user_id) {
                NotificationHelper::orderCreated($order);
            }

            DB::commit();

            return redirect()->route('checkout.success', $order->id)->with('success', 'Đặt hàng thành công!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'Có lỗi xảy ra khi đặt hàng: ' . $e->getMessage());
        }
    }

    // Trang thành công
    public function success($orderId)
    {
        $order = Order::with('items.book')->findOrFail($orderId);
        return view('checkout.success', compact('order'));
    }
}
