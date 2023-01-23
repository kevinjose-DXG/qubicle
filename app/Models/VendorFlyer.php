<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorFlyer extends Model
{
    use HasFactory;

    public function flyers()
    {
        return $this->hasMany(VendorFlyerDetail::class,'vendor_flyer_id','id');
    }
    public function userDetail()
    {
        return $this->hasOne(User::class,'id','vendor_id');
    }
    public function planDetail()
    {
        return $this->hasOne(VendorPlan::class,'id','plan_id');
    }
    public function designer()
    {
        return $this->hasOne(DesignedBy::class,'id','designed_by');
    }
    
}
