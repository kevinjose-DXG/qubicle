<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public function userDetail()
    {
        return $this->hasOne(User::class,'id','vendor_id');
    }
    public function planDetail()
    {
        return $this->hasOne(VendorPlan::class,'id','plan_id');
    }
}
