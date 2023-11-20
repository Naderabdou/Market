<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    protected $appends = ['img_profile_path','img_background_path'];
    public function setPasswordAttribute($value)
    {

        $this->attributes['password'] = bcrypt($value);
    }
    public function setRegistrationDateAttribute($value)
    {

        $this->attributes['registration_date'] = date('Y-m-d', strtotime($value));
    }
    public function setExpiryDateAttribute($value)
    {

        $this->attributes['expiry_date'] = date('Y-m-d', strtotime($value));
    }
    public function setDateOfBirthAttribute($value)
    {

        $this->attributes['date_of_birth'] = date('Y-m-d', strtotime($value));
    }

    public function setCreatedAt($value)
    {

            $this->attributes['created_at'] = date('Y-m-d', strtotime($value));
    }
public function setUpdatedAt($value)
    {

            $this->attributes['updated_at'] = date('Y-m-d', strtotime($value));
    }


    public function getImgProfilePathAttribute(){

        return   asset('storage/'.$this->attributes['img_profile']);
    }
    public function getImgBackgroundPathAttribute(){
        return asset('storage/'.$this->attributes['img_background']);
    }
    public function scopeWhenSearch($query,$search)

    {

          $query->where(function ($w) use ($search){

            $w->when($search,function ($q) use ($search){

                return $q->where('name','like','%'.$search.'%')
                    ->orWhere('phone','like','%'.$search.'%');

            });
        })->latest()->paginate(10);



    }

    public function  category(){
        return $this->belongsTo(Category::class,'category_id');
    }

    public function products(){
        return $this->hasMany(Product::class,'market_id');
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

}
