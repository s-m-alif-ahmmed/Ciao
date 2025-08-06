<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_id',
        'valet_id',
        'order_number',
        'payment_method',
        'payment_id',
        'discount',
        'tax',
        'tax_percentage',
        'valet_charge',
        'valet_charge_extra',
        'valet_tip',
        'platform_fee',
        'sub_total',
        'total_price',
        'not_found_total',
        'shopping_payment',
        'valet_payment',
        'payment_status',
        'status',
    ];

    protected $casts = [
        'user_id'               => 'integer',
        'shop_id'               => 'integer',
        'valet_id'              => 'integer',
        'order_number'          => 'string',
        'payment_method'        => 'string',
        'payment_id'            => 'string',
        'discount'              => 'string',
        'tax'                   => 'string',
        'tax_percentage'        => 'integer',
        'valet_charge'          => 'string',
        'valet_charge_extra'    => 'string',
        'valet_tip'             => 'string',
        'platform_fee'          => 'string',
        'sub_total'             => 'string',
        'total_price'           => 'string',
        'not_found_total'       => 'string',
        'shopping_payment'      => 'string',
        'valet_payment'         => 'string',
        'payment_status'        => 'string',
        'status'                => 'string',
    ];

    // User Relation
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Valet Relation
    public function valet()
    {
        return $this->belongsTo(User::class, 'valet_id');
    }

    // Shop Relation
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    // Order Details Relation
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    // Order Receipts Relation
    public function orderReceipts()
    {
        return $this->hasMany(OrderReceipt::class);
    }

    // Order Left Amount Relation
    public function orderRestAmount()
    {
        return $this->hasMany(OrderLeftAmount::class);
    }

    // Order User Spend Amount Relation
    public function userOrderSpendAmount()
    {
        return $this->hasMany(OrderUserSpendAmount::class);
    }

}
