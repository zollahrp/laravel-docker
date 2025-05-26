<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = [
        'description',
        'action',
        'affected_table',
    ];


    /**
     * Record activity in history log
     */
    public static function boot()
    {
        parent::boot();

    
    }
}
