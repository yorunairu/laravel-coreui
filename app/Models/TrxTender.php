<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class TrxTender extends Model
{
    use HasFactory, Softdeletes;

    protected $dates = ['deleted_at'];

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
    public function tenderPo()
    {
        return $this->belongsTo(TrxTenderPo::class, 'id','trx_tender_id');
    }
    public function activity()
    {
        return $this->hasMany(TrxTenderSalesActivity::class, 'tender_id');
    }
    public function customer()
    {
        return $this->belongsTo(M_cusprin::class, 'customer_id');
    }

    public function principle()
    {
        return $this->belongsTo(M_cusprin::class, 'customer_id');
    }

    public function currency()
    {
        return $this->belongsTo(M_currency::class, 'currency_id');
    }

    public function userCreator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function review()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function userUpdator()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'id');
    }

    public function tenderLogs()
    {
        return $this->hasMany(TrxTenderLog::class, 'trx_tender_id');
    }

    public function tenderMaterial()
    {
        return $this->hasMany(TrxTenderMaterial::class, 'trx_tender_id');
    }
    public function tenderRfq()
    {
        return $this->belongsTo(TrxTenderRfq::class, 'id', 'trx_tender_id');
    }
    public function statusJourney()
    {
        return $this->belongsTo(M_tender_status_journey::class, 'status_journey', 'order');
    }

    public function bgGuarantee()
    {
        return $this->hasMany(TrxTenderBgGuarantee::class, 'trx_tender_id');
    }
}
