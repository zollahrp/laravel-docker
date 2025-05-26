<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'created_by',
        'deleted_by',
    ];

    /**
     * Get the user who created the customer.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who deleted the customer.
     */
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Record activity in history log
     */
    public static function boot()
    {
        parent::boot();

        static::created(function ($customer) {
            History::create([
                'description' => "Customer dibuat",
                
                'action' => 'create',
                'affected_table' => 'customers',
            ]);
        });

        static::deleted(function ($customer) {
            History::create([
                'description' => "Customer dihapus",
                
                'action' => 'delete',
                'affected_table' => 'customers',
            ]);
        });
    }
}
