<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class M_cusprin extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'm_cusprins';

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

    // Define the inverse relationship with TrxListTender model
    public function trxTenders()
    {
        return $this->hasMany(TrxListTender::class, 'customer_id');
    }
}
