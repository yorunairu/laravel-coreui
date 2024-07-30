<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class M_delpoint extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'm_delpoints';
    protected $dates = ['deleted_at'];

    public $timestamps = false;

    public function save(array $options = [])
    {
        if (!$this->exists) {
            $this->created_at = now();
        }

        // Selalu isi updated_at jika model sudah ada
        if ($this->exists) {
            $this->updated_at = now();
        }

        // Simpan model tanpa timestamps otomatis
        parent::save($options);

        // Simpan timestamps secara manual setelah save()
        if (!$this->exists) {
            $this->updated_at = null; // Set updated_at to null during create
            $this->saveQuietly();
        }
    }
    
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
}
