<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable =
    [
        'name',
        'latitude',
        'longitude',
        'location',
        'stall_number',
        'status'
    ];

    protected $hidden =
    [
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'name'           => 'string',
        'latitude'       => 'string',
        'longitude'      => 'string',
        'location'       => 'string',
        'stall_number'   => 'string',
        'status'         => 'string',
    ];

    public function images()
    {
        return $this->hasMany(ShopImage::class);
    }

    public function subcategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function bannerImages()
    {
        return $this->hasMany(CMS::class);
    }

    // every Shops cart for fetch
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
