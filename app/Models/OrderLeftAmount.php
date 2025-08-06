<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLeftAmount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'amount',
    ];

    protected $casts = [
        'user_id'        => 'integer',
        'order_id'       => 'integer',
        'amount'         => 'integer',
    ];

    // User Relation
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Valet Relation
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
