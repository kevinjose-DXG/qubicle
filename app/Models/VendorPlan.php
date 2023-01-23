<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPlan extends Model
{
    use HasFactory;

    public function plans()
    {
        return $this->hasOne(PlanMaster::class,'id','plan_id');
    }
    public function flyer()
    {
        return $this->hasMany(VendorFlyer::class,'plan_id','id');
    }
    public function userDetail()
    {
        return $this->hasOne(User::class,'id','vendor_id');
    }
}
