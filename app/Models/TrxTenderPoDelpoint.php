<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TrxTenderPoDelpoint extends Model
{
    use HasFactory;

    protected $fillable = ['trx_tender_po_id', 'delpoint_id'];

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

    public function mDelpoint()
    {
        return $this->belongsTo(M_delpoint::class, 'delpoint_id');
    }
}
