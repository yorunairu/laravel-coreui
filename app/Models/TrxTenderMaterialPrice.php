<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class TrxTenderMaterialPrice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trx_tender_material_id',
        'm_currency_id',
        'price',
        'type',
        'price_rate',
        'date_rate',
        'total_idr_convert',
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

    public function currency()
    {
        return $this->belongsTo(M_currency::class, 'm_currency_id');
    }

    // Define the relationship with the M_cusprin model
    public function tenderMaterial()
    {
        return $this->belongsTo(TrxTenderMaterial::class, 'trx_tender_material_id');
    }
}
