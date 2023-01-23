<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorDetail extends Model
{
    use HasFactory;

    public function planDetail()
    {
        return $this->hasOne(VendorPlan::class,'id','plan_id');
    }
    public function userDetail()
    {
        return $this->hasOne(User::class,'id','vendor_id');
    }
    public function states()
    {
        return $this->hasOne(State::class,'id','state');
    }
    public function districts()
    {
        return $this->hasOne(District::class,'id','district');
    }
    public function locations()
    {
        return $this->hasOne(Location::class,'id','location');
    }
    public function flyers()
    {
        return $this->hasMany(VendorFlyer::class,'plan_id','plan_id');
    }
}