<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // Lấy hoặc tạo giỏ hàng
    private function getCart()
    {
        if (Auth::check()) {
            // Nếu đã đăng nhập
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        } else {
            // Nếu chưa đăng nhập, dùng session
            $sessionId = Session::getId();
            return Cart::firstOrCreate(['session_id' => $sessionId]);
        }
    }

    // Hiển thị giỏ hàng
    public function index()
    {
        $cart = $this->getCart();
        $cartItems = $cart->items()->with('book.author', 'book.publisher')->get();
        return view('cart.index', compact('cart', 'cartItems'));
    }

    // Thêm sản phẩm vào giỏ
    public function add(Request $request, $bookId)
    {
        $book = Book::findOrFail($bookId);
        $cart = $this->getCart();
        
        $quantity = $request->input('quantity', 1);

        // Kiểm tra xem sách đã có trong giỏ chưa
        $cartItem = $cart->items()->where('book_id', $bookId)->first();

        if ($cartItem) {
            // Nếu đã có, tăng số lượng
            $newQuantity = $cartItem->quantity + $quantity;
            if ($newQuantity > $book->stock) {
                return back()->with('error', 'Số lượng vượt quá tồn kho!');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // Nếu chưa có, thêm mới
            if ($quantity > $book->stock) {
                return back()->with('error', 'Số lượng vượt quá tồn kho!');
            }
            CartItem::create([
                'cart_id' => $cart->id,
                'book_id' => $bookId,
                'quantity' => $quantity,
                'price' => $book->price,
            ]);
        }

        return back()->with('success', 'Đã thêm vào giỏ hàng!');
    }

    // Cập nhật số lượng
    public function update(Request $request, $itemId)
    {
        $cartItem = CartItem::findOrFail($itemId);
        $quantity = $request->input('quantity', 1);

        if ($quantity > $cartItem->book->stock) {
            return back()->with('error', 'Số lượng vượt quá tồn kho!');
        }

        if ($quantity <= 0) {
            $cartItem->delete();
            return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
        }

        $cartItem->update(['quantity' => $quantity]);
        return back()->with('success', 'Đã cập nhật giỏ hàng!');
    }

    // Xóa sản phẩm
    public function remove($itemId)
    {
        $cartItem = CartItem::findOrFail($itemId);
        $cartItem->delete();
        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    // Xóa toàn bộ giỏ hàng
    public function clear()
    {
        $cart = $this->getCart();
        $cart->items()->delete();
        return back()->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }
}
