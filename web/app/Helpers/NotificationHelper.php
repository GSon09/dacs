<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;

class NotificationHelper
{
    // Order status changed
    public static function orderStatusChanged($order, $oldStatus, $newStatus)
    {
        if (!$order->user_id) return;

        $statusMessages = [
            'pending' => 'Đơn hàng đang chờ xử lý',
            'waiting_pickup' => 'Đơn hàng đang chờ lấy hàng',
            'delivered' => 'Đơn hàng đã được giao thành công! Hãy đánh giá sản phẩm',
            'canceled' => 'Đơn hàng đã bị hủy'
        ];

        $icons = [
            'pending' => '⏳',
            'waiting_pickup' => '📦',
            'delivered' => '✅',
            'canceled' => '❌'
        ];

        Notification::create([
            'user_id' => $order->user_id,
            'type' => 'order_status',
            'title' => $icons[$newStatus] . ' Cập nhật đơn hàng #' . $order->order_number,
            'message' => $statusMessages[$newStatus],
            'link' => route('checkout.success', $order->id),
            'data' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ]
        ]);
    }

    // New order created
    public static function orderCreated($order)
    {
        if (!$order->user_id) return;

        Notification::create([
            'user_id' => $order->user_id,
            'type' => 'order_created',
            'title' => '🎉 Đặt hàng thành công!',
            'message' => 'Đơn hàng #' . $order->order_number . ' đã được tạo. Tổng: ' . number_format($order->total, 0, ',', '.') . '₫',
            'link' => route('checkout.success', $order->id),
            'data' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'total' => $order->total
            ]
        ]);
    }

    // Review received on user's review
    public static function reviewReply($userId, $book, $replyText)
    {
        Notification::create([
            'user_id' => $userId,
            'type' => 'review_reply',
            'title' => '💬 Phản hồi đánh giá',
            'message' => 'Có phản hồi mới cho đánh giá của bạn về "' . $book->title . '"',
            'link' => route('book.detail', $book->id) . '#reviews',
            'data' => [
                'book_id' => $book->id,
                'reply' => $replyText
            ]
        ]);
    }

    // Welcome notification for new user
    public static function welcomeUser($user)
    {
        Notification::create([
            'user_id' => $user->id,
            'type' => 'welcome',
            'title' => '🎊 Chào mừng đến với Nhà sách!',
            'message' => 'Cảm ơn bạn đã đăng ký. Khám phá hàng ngàn đầu sách hay ngay!',
            'link' => route('products.all'),
            'data' => []
        ]);
    }

    // Promotion notification
    public static function promotion($userId, $title, $message, $link = null)
    {
        Notification::create([
            'user_id' => $userId,
            'type' => 'promotion',
            'title' => '🎁 ' . $title,
            'message' => $message,
            'link' => $link,
            'data' => []
        ]);
    }

    // Low stock alert (for favorite books)
    public static function lowStockAlert($userId, $book)
    {
        Notification::create([
            'user_id' => $userId,
            'type' => 'low_stock',
            'title' => '⚠️ Sắp hết hàng!',
            'message' => '"' . $book->title . '" chỉ còn ' . $book->stock . ' cuốn. Đặt ngay!',
            'link' => route('book.detail', $book->id),
            'data' => [
                'book_id' => $book->id,
                'stock' => $book->stock
            ]
        ]);
    }
}
