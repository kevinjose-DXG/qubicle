<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComboOffer extends Model
{
    use HasFactory;

    public function comboDetails()
    {
        return $this->hasMany(ComboOfferDetail::class,'combo_id','id');
    }
}
