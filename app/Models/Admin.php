<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    public $incrementing = false;
    
    protected $fillable = [
        'id_admin',
        'username',
        'password',
        'nama_admin',
        'jk',
        'no_hp',
    ];
    protected $hidden = [
        'password',
    ];

    public function setPasswordAttribute($value)
    {
      $this->attributes['password'] = bcrypt($value);
    }
}
