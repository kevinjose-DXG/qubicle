<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandModel extends Model
{
    use HasFactory;

    public function brands()
    {
        return $this->hasOne(Brand::class,'id','brand_id');
    }
}
