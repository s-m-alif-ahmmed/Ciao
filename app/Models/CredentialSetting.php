<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CredentialSetting extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable = [
        'paypal_mode',
        'paypal_client_id',
        'paypal_client_secret_id',
    ];

}
