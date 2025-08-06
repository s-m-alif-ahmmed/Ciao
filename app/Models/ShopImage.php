<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopImage extends Model
{
    protected $fillable =
    [
        'shop_id',
        'image'
    ];

    protected $hidden =
    [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'shop_id'   => 'integer',
        'image'     => 'string',
    ];

    //for api image with url retrieve
    public function getImageAttribute($value): string | null
    {
        if (request()->is('api/*') && !empty($value)) {
            return url($value);
        }
        return $value;
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
