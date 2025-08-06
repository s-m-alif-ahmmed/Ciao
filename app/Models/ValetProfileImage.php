<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValetProfileImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'valet_profile_id',
        'image_path'
    ];

    protected $hidden =
    [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'valet_profile_id'  => 'integer',
        'image_path'        => 'string',
    ];

    public function getImagePathAttribute($value): string | null
    {
        if (request()->is('api/*') && !empty($value)) {
            return url($value);
        }
        return $value;
    }

    public function valetProfile()
    {
        return $this->belongsTo(ValetProfile::class);
    }
}
