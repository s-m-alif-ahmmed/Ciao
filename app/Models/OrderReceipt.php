<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'image',
    ];

    protected $casts = [
        'order_id'       => 'integer',
        'image'          => 'string',
    ];

    public function getImageAttribute($value): string | null
    {
        if (request()->is('api/*') && !empty($value)) {
            return url($value);
        }
        return $value;
    }

    // User Relation
    public function order()
    {
        return $this->belongsTo(order::class);
    }

}
