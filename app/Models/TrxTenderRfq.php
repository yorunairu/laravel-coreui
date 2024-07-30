<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class TrxTenderRfq extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trx_tender_id',
        'rfq_no',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_at',
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
    
    public function userCreator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function userUpdator()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Define the relationship with the M_cusprin model
    public function tender()
    {
        return $this->belongsTo(TrxTender::class, 'trx_tender_id');
    }

    // Define the relationship with the M_cusprin model
    public function rfqPrinciple()
    {
        return $this->hasMany(TrxTenderRfqPrinciple::class, 'trx_tender_rfq_id');
    }

    // Define the relationship with the M_cusprin model
    public function delpoint()
    {
        return $this->hasMany(TrxTenderRfqDelpoint::class, 'trx_tender_rfq_id');
    }

    // Define the relationship with the M_cusprin model
    public function term()
    {
        return $this->hasMany(TrxTenderRfqTermComp::class, 'trx_tender_rfq_id');
    }
}
