<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Book;
use App\Helpers\NotificationHelper;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    /**
     * Cancel an order by the authenticated user.
     */
    public function cancel(Request $request, $id)
    {
        $order = Order::with('items.book')->findOrFail($id);

        // Only owner can cancel
        if (!$order->user_id || $order->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền hủy đơn này.');
        }

        // Only allow cancel if not delivered or already canceled
        if (in_array($order->status, ['delivered', 'canceled'])) {
            return redirect()->back()->with('error', 'Đơn hàng không thể hủy được.');
        }

        DB::beginTransaction();
        try {
            // Restore stock for each item
            foreach ($order->items as $item) {
                $book = Book::find($item->book_id);
                if ($book) {
                    $book->stock += $item->quantity;
                    $book->save();
                }
            }

            $oldStatus = $order->status;
            $order->status = 'canceled';
            $order->save();

            // Notify user/admin if applicable
            if ($order->user_id) {
                NotificationHelper::orderStatusChanged($order, $oldStatus, 'canceled');
            }

            DB::commit();
            return redirect()->back()->with('success', 'Đơn hàng đã được hủy và số lượng đã được hoàn trả về kho.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi khi hủy đơn: ' . $e->getMessage());
        }
    }

    /**
     * Re-order: add order items back to current user's cart (or session cart).
     */
    public function reorder(Request $request, $id)
    {
        $order = Order::with('items.book')->findOrFail($id);

        if (!Auth::check() || $order->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền thực hiện hành động này.');
        }

        // Get or create cart
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        } else {
            $cart = Cart::firstOrCreate(['session_id' => Session::getId()]);
        }

        $skipped = [];
        foreach ($order->items as $item) {
            $book = $item->book;
            if (!$book) {
                $skipped[] = ['title' => 'Unknown', 'reason' => 'Không tìm thấy sản phẩm'];
                continue;
            }

            if ($book->stock <= 0) {
                $skipped[] = ['title' => $book->title, 'reason' => 'Hết hàng'];
                continue;
            }

            $desired = min($item->quantity, $book->stock);

            $existing = $cart->items()->where('book_id', $book->id)->first();
            if ($existing) {
                $newQty = min($existing->quantity + $desired, $book->stock);
                $existing->update(['quantity' => $newQty]);
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'book_id' => $book->id,
                    'quantity' => $desired,
                    'price' => $book->price,
                ]);
            }
        }

        if (count($skipped) > 0) {
            $titles = implode(', ', array_map(fn($s) => $s['title'], $skipped));
            return redirect()->route('cart.index')->with('warning', 'Một số sản phẩm không thể thêm vào giỏ: ' . $titles);
        }

        return redirect()->route('cart.index')->with('success', 'Đã thêm sản phẩm vào giỏ hàng.');
    }
}
