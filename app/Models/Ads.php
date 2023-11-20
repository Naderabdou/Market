<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    use HasFactory;
    protected $guarded=[];

    protected $appends = ['image_path'];
    //get image from database
    public function getImagePathAttribute()
    {
        return asset('storage/' . $this->image);
    }
    public function  user()
    {
        return $this->belongsTo(User::class,'user_id');
    }



}
