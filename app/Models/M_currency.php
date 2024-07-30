<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class M_currency extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'currency',
        'price_rate',
        'date_rate',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_at',
        // Add other fillable attributes here if any
    ];
    protected $table = 'm_currencies';

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

    public function userUpdator()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function trxTenders()
    {
        return $this->hasMany(TrxListTender::class, 'currency_id');
    }
}
