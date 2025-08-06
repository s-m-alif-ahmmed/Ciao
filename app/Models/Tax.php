<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Tax extends Model
{
    use HasFactory;

    protected $fillable = [
        'tax',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'tax'       => 'integer',
        'status'    => 'string',
    ];

}
