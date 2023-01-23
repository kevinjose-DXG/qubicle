<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanMaster extends Model
{
    use HasFactory;

    public function highlights()
    {
        return $this->hasMany(PlanMasterHighlight::class,'plan_id','id');
    }
}
