<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    
    public function brandmodel()
    {
        return $this->hasMany(BrandModel::class,'brand_id','id');
    }
}
