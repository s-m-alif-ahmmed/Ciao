<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValetProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'paper_work',
        'user_name',
        'meet_requirement',
        'status',
    ];

    protected $hidden =
    [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'user_id'           => 'integer',
        'paper_work'        => 'string',
        'user_name'         => 'string',
        'status'            => 'string',
    ];

    //for api image with url retrieve
    public function getPaperWorkAttribute($value): string | null
    {
        if (request()->is('api/*') && !empty($value)) {
            return url($value);
        }
        return $value;
    }

    public function images()
    {
        return $this->hasMany(ValetProfileImage::class);
    }

    // In ValetProfile model
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
