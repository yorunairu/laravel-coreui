<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class TrxTenderRfqDelpoint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trx_tender_rfq_id',
        'm_term_delpoint_id',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_at',
        // Add other fillable attributes here if any
    ];
    protected $table = 'trx_tender_rfq_delpoints';
    
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
    public function mDelpoint()
    {
        return $this->belongsTo(M_delpoint::class, 'm_term_delpoint_id');
    }
}
