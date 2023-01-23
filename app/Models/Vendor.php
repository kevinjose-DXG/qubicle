<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Authenticatable
{
    use  HasFactory;

    public function vendorDetail()
    {
        return $this->hasOne(VendorDetail::class,'vendor_id');
    }
}
