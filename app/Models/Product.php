<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable =
        [
            'category_id',
            'sub_category_id',
            'shop_id',
            'name',
            'description',
            'thumbnail',
            'price',
            'stock',
            'quantity',
            'status',
        ];

    protected $hidden =
    [
        'status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'category_id'      => 'integer',
        'sub_category_id'  => 'integer',
        'shop_id'          => 'integer',
        'name'             => 'string',
        'description'      => 'string',
        'thumbnail'        => 'string',
        'price'            => 'string',
        'stock'            => 'string',
        'quantity'         => 'string',
        'status'           => 'string',
    ];

    public function getThumbnailAttribute($value): string | null
    {
        if (request()->is('api/*') && !empty($value)) {
            return url($value);
        }
        return $value;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
