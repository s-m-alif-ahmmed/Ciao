<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable =
        [
            'name',
            'image',
            'status',
        ];

     //list of hidden fields
     protected $hidden = [
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'name'       => 'string',
        'image'      => 'string',
        'status'     => 'string',
    ];

    public function subcategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    //for api image with url retrieve
    public function getImageAttribute($value): string | null
    {
        if (request()->is('api/*') && !empty($value)) {
            return url($value);
        }
        return $value;
    }
}
