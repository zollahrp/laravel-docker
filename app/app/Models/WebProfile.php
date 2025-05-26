<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebProfile extends Model
{
    protected $fillable = [
        'logo',
        'site_name',
        'email',
        'phone',
        'address',
        'facebook',
        'instagram',
        'twitter',
    ];
}
