<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Vendor extends Model
{
    use HasFactory;

    use Notifiable ;
    protected $table = "vendors";

    protected $fillable = [
        'name',
        'mobile',
        'password',
        'email' ,
        'address',
        'category_id',
        'logo' ,
        'active' ,
        'latitude',
        'longitude',
        'created_at' ,
        'updated_at'
    ];

    protected $hiddin = ['category_id','password'];


    public  function scopeSelection($query)
    {
        return $query->select('id','name','email', 'latitude','longitude','mobile','address','category_id','logo','active','password');
    }

    public function scopeActive($query)
    {

        return $query->where('active', 1);
    }
    public  function  getActive()
    {
        return $this->active == 1 ? 'مفعل' : 'غير مفعل';
    }
    public  function getLogoAttribute($val)
    {
        return ($val !== null) ? asset('assets/'.$val):"";
    }

    public function category()
    {
        return $this->belongsTo(MainCategory::class,'category_id','id');
    }

    public function setPasswordAttribute($password)
    {
        if(!empty($password))
        {
            return $this->attributes['password'] = bcrypt($password);
        }
    }


}
