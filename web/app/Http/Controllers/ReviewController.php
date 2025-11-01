<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Book;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Store a new review
    public function store(Request $request, $bookId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'order_id' => 'nullable|exists:orders,id'
        ]);

        $book = Book::findOrFail($bookId);
        
        // Check if user already reviewed this book
        if (Auth::check()) {
            $existingReview = Review::where('book_id', $bookId)
                ->where('user_id', Auth::id())
                ->first();
                
            if ($existingReview) {
                return redirect()->back()->with('error', 'Bạn đã đánh giá sách này rồi!');
            }
        }

        // Check if this is a verified purchase
        $isVerifiedPurchase = false;
        if ($request->order_id) {
            $order = Order::where('id', $request->order_id)
                ->where('status', 'delivered')
                ->whereHas('items', function($query) use ($bookId) {
                    $query->where('book_id', $bookId);
                })
                ->first();
                
            if ($order) {
                $isVerifiedPurchase = true;
            }
        }

        Review::create([
            'book_id' => $bookId,
            'user_id' => Auth::id(),
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'reviewer_name' => Auth::check() ? null : $request->reviewer_name,
            'is_verified_purchase' => $isVerifiedPurchase
        ]);

        return redirect()->back()->with('success', 'Cảm ơn bạn đã đánh giá!');
    }

    // Get reviews for a book
    public function getReviews($bookId)
    {
        $reviews = Review::where('book_id', $bookId)
            ->with('user')
            ->orderBy('is_verified_purchase', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($reviews);
    }

    // Check if user can review a book (purchased and delivered)
    public function canReview($bookId)
    {
        if (!Auth::check()) {
            return response()->json(['can_review' => false]);
        }

        // Check if user has purchased this book and order is delivered
        $hasPurchased = Order::where('user_id', Auth::id())
            ->where('status', 'delivered')
            ->whereHas('items', function($query) use ($bookId) {
                $query->where('book_id', $bookId);
            })
            ->exists();

        // Check if user already reviewed
        $hasReviewed = Review::where('book_id', $bookId)
            ->where('user_id', Auth::id())
            ->exists();

        $eligibleOrders = Order::where('user_id', Auth::id())
            ->where('status', 'delivered')
            ->whereHas('items', function($query) use ($bookId) {
                $query->where('book_id', $bookId);
            })
            ->whereDoesntHave('items.book.reviews', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->get(['id', 'order_number']);

        return response()->json([
            'can_review' => $hasPurchased && !$hasReviewed,
            'has_purchased' => $hasPurchased,
            'has_reviewed' => $hasReviewed,
            'eligible_orders' => $eligibleOrders
        ]);
    }
}
