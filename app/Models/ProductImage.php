<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'product_id',
        'image_path'
    ];

    protected $hidden =
    [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'product_id'        => 'integer',
        'image_path'        => 'string',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    //for api image with url retrieve
    public function getImagePathAttribute($value): string | null
    {
        if (request()->is('api/*') && !empty($value)) {
            return url($value);
        }
        return $value;
    }
}
