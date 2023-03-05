<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function orderdetail()
    {
        return $this->hasMany(OrderDetail::class,'order_id','order_no');
    }
    public function customer()
    {
        return $this->hasOne(User::class,'id','customer_id');
    }
}
