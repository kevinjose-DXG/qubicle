<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    public function states()
    {
        return $this->hasOne(State::class,'id','state_id');
    }
    public function district()
    {
        return $this->hasOne(District::class,'id','district_id');
    }
}
