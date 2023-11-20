<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    public $translatedAttributes=['name','description'];
    protected $guarded=[];
  //  protected $appends=['img_product_path'];

    public function market()
    {
        return $this->belongsTo(User::class,'market_id');
    }

    /*public function getImgProductPathAttribute()
    {
        return asset('storage/'.$this->attributes['img_product']);
    }*/

    public function scopeWhenSearch($query,$search)

    {

        $query->where(function ($w) use ($search){

            $w->when($search,function ($q) use ($search){

                return $q->where('name','like','%'.$search.'%');


            });
        })->latest()->paginate(10);
    }

   /* public function images()
    {
        return $this->hasMany(Image::class,'product_id');
    }*/

    public function images()
    {
        return $this->morphMany(Image::class,'imageable');
    }
    public function image()
    {
        return $this->morphOne(Image::class,'imageable');
    }
    public function extras()
    {
        return $this->hasMany(Extra::class,'product_id');
    }
}
