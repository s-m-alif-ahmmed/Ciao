<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'found_item'
    ];

    protected $casts = [
        'order_id'       => 'integer',
        'product_id'     => 'integer',
        'quantity'       => 'integer',
        'price'          => 'string',
        'found_item'     => 'string',
        'created_at'     => 'string',
        'updated_at'     => 'string',
    ];

    // Order Relation
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Product Relation
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
