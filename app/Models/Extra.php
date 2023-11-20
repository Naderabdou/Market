<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extra extends Model
{
    use HasFactory;
    public $guarded = [];



    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class,'imageable');
    }
    public function image()
    {
        return $this->morphOne(Image::class,'imageable');
    }


}
