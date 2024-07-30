<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class TrxTenderLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'trx_tender_id',
        'created_at',
        'created_by',
        'updated_by',
        // Add other fillable attributes here if any
    ];
    
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }
}
