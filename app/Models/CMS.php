<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class CMS extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shop_id',
        'banner_image',
        'status',
    ];

    protected $casts = [
        'shop_id'       => 'integer',
        'banner_image'  => 'string',
        'status'        => 'string',
    ];

     //for api image with url retrieve
     public function getBannerImageAttribute($value): string | null
     {
         if (request()->is('api/*') && !empty($value)) {
             return url($value);
         }
         return $value;
     }

     public function shop(){
         return $this->belongsTo(Shop::class);
     }
}
