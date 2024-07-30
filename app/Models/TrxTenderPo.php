<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class TrxTenderPo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['customer_po_no', 'trx_tender_id', 'customer_po_doc', 'customer_po_date', 'customer_po_note',
        'principle_po_doc', 'principle_po_no',
        'principle_po_date',

    ];
    // protected $dates = ['created_at', 'updated_at'];

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

    public function tender()
    {
        return $this->belongsTo(TrxTender::class, 'trx_tender_id');
    }

    public function userCreator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function statusJourney()
    {
        return $this->belongsTo(M_tender_status_journey::class, 'status_journey', 'order');
    }

    public function delpoint()
    {
        return $this->hasMany(TrxTenderPoDelpoint::class, 'trx_tender_po_id');
    }

    // Define the relationship with the M_cusprin model
    public function term()
    {
        return $this->hasMany(TrxTenderPoTerm::class, 'trx_tender_po_id');
    }


}
