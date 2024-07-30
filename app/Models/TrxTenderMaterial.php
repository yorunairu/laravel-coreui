<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class TrxTenderMaterial extends Model
{
    use HasFactory, SoftDeletes;
    
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

    // Define the relationship with the M_cusprin model
    public function uom()
    {
        return $this->belongsTo(M_uom::class, 'm_uom_id');
    }

    // Define the relationship with the M_cusprin model
    public function material()
    {
        return $this->belongsTo(M_material::class, 'm_material_id');
    }

    // Define the relationship with the M_cusprin model
    public function price()
    {
        return $this->hasMany(TrxTenderMaterialPrice::class, 'trx_tender_material_id');
    }
}
