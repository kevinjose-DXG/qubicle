<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    public function customer()
    {
        return $this->hasOne(User::class,'id','customer_id');
    }
    public function category()
    {
        return $this->hasOne(Category::class,'id','category_id');
    }
    public function subcategory()
    {
        return $this->hasOne(SubCategory::class,'id','sub_category_id');
    }
    public function brands()
    {
        return $this->hasOne(Brand::class,'id','brand_id');
    }
    public function brandmodel()
    {
        return $this->hasOne(BrandModel::class,'id','model_id');
    }
}
