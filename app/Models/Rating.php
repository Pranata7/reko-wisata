<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    protected $table = 'rating';
    protected $primaryKey = 'id_rating';
    public $incrementing = false;
    
    protected $fillable = [
        'id_rating',
        'username',
        'id_wisata',
        'angka_rating',
        'komentar'
    ];

    public function wisata()
    {
        return $this->belongsTo('App\Wisata', 'id_wisata');
    }

    public function user()
    {
        return $this->belongsTo('App\Wisata', 'username');
    }
}
