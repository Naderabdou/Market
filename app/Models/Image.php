<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',

    ];

    protected $appends=['image_path'];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
    public function getImagePathAttribute()
    {
        return asset('storage/'.$this->attributes['image']);
    }

    public function imageable()
    {
        return $this->morphTo();
    }
}
