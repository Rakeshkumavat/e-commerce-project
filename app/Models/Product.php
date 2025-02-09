<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\Product;
use App\Models\User;
// use App\Models\Category;
class Product extends Model
{
    use HasFactory;
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function user(){
        return $this->hasOne(User::class,'user_id','id');
    }
}
