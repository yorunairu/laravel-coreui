<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class TrxTenderSalesActivity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['trx_tender_id', 'doc_evidence', 'description', 'user_id'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
            }
            if (!$model->date) {
                $model->date = now(); // Mengisi kolom date dengan waktu saat ini jika kosong
            }
        });
    }
    public function tenders()
    {
        return $this->belongsTo(TrxTender::class, 'trx_tender_id');
    }

    public function userCreator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
