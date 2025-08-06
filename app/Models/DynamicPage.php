<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DynamicPage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'page_title',
        'page_content',
        'slug',
        'status',
    ];

    protected $hidden = [
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'page_title'    => 'string',
        'page_content'  => 'string',
        'slug'          => 'string',
        'status'        => 'string',
    ];

    public function getSlugAttribute($value): string | null
    {
        if (request()->is('api/*') && !empty($value)) {
            return url('page/'.$value);
        }
        return $value;
    }

}
