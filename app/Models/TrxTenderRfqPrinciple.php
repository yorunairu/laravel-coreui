<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class TrxTenderRfqPrinciple extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trx_tender_rfq_id',
        'principle_id',
        'status',
        'date_delivery',
        'date_delivery_goods',
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

    // Define the relationship with the M_cusprin model
    public function tenderRfq()
    {
        return $this->belongsTo(TrxTenderRfq::class, 'trx_tender_rfq_id');
    }

    // Define the relationship with the M_cusprin model
    public function principle()
    {
        return $this->belongsTo(M_cusprin::class, 'principle_id');
    }

    public function currency()
    {
        return $this->belongsTo(M_currency::class, 'm_currency_id');
    }

    public function userCreator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function userUpdator()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
