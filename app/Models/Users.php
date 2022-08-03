<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;   

class Users extends Authenticatable
{
    use HasFactory;

    protected $table = 'users';
    protected $primaryKey = 'username';
    public $incrementing = false;
    
    protected $fillable = [
        'username',
        'email',
        'password',
        'nama_user',
        'jk',
        'no_hp',
        'provider',
        'provider_id',
        'alamat'
    ];

    protected $hidden = [
        'password',
    ];

    public function setPasswordAttribute($value)
    {
      $this->attributes['password'] = bcrypt($value);
    }

    public function wisata() {
        return $this->hasMany('App\Models\Rating', 'username');
    }
}
