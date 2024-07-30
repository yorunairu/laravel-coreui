<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class TrxTenderBgGuarantee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trx_tender_id',
        'no_guarantee',
        'price',
        'type',
        'note',
        'time_period',
        'doc_bg',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
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

    // Define the relationship with the M_cusprin model
    public function tender()
    {
        return $this->belongsTo(TrxTender::class, 'trx_tender_id');
    }
}
