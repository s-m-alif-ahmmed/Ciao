<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */

    use  HasApiTokens, HasFactory, Notifiable, SoftDeletes,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'number',
        'role',
        'country',
        'password',
        'google_id',
        'facebook_id',
        'avatar',
        'payment_email',
        'email_verified_at',
    ];
    // protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'name' => 'string',
            'email' => 'string',
            'number' => 'string',
            'role' => 'string',
            'country' => 'string',
            'google_id' => 'string',
            'facebook_id' => 'string',
            'avatar' => 'string',
            'payment_email' => 'string',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //for api image with url retrieve
    public function getAvatarAttribute($value): string | null
    {
        if (request()->is('api/*') && !empty($value)) {
            return url($value);
        }
        return $value;
    }


    // every User cart for fetch
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    // In User model
    public function valetProfile()
    {
        return $this->hasOne(ValetProfile::class);
    }

    // In User model
    public function orderLeftAmount()
    {
        return $this->hasOne(OrderLeftAmount::class);
    }

    // In User model
    public function orderUserSpendAmount()
    {
        return $this->hasOne(OrderUserSpendAmount::class);
    }
}
