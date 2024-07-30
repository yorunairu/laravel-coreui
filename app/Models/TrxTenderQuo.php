<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TrxTenderQuo extends Model
{
    use HasFactory;

    protected $fillable = ['trx_tender_id', 'quo_no', 'note'];

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
