<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $table='categories';
    public $translatedAttributes = ['name'];
    protected $fillable=['img_category'];
    protected $appends=['img_category_path'];

    public function markets(){
        return $this->hasMany(User::class,'category_id')->where('status','unblock')->where('expiry_date','>=',Carbon::now());
    }



    public function getImgCategoryPathAttribute(){
        return asset('storage/'.$this->img_category);
    }

    public function scopeWhenSearch($query,$search)

    {

        $query->where(function ($w) use ($search){

            $w->when($search,function ($q) use ($search){

                return $q->where('name','like','%'.$search.'%');


            });
        })->latest()->paginate(10);



    }
}
