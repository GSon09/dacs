<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'book_id',
        'user_id',
        'order_id',
        'rating',
        'comment',
        'reviewer_name',
        'is_verified_purchase'
    ];

    protected $casts = [
        'is_verified_purchase' => 'boolean',
        'rating' => 'integer'
    ];

    // Relationships
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Helper to get reviewer name
    public function getReviewerDisplayName()
    {
        if ($this->user) {
            return $this->user->name;
        }
        return $this->reviewer_name ?? 'Khách hàng';
    }
}
