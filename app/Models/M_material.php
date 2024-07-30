<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_material extends Model
{
    use HasFactory;

    // Define the relationship with the M_cusprin model
    public function tenderDetail()
    {
        return $this->hasMany(TrxTender::class, 'm_material_id');
    }
}
