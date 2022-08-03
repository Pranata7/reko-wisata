<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Bagusindrayana\LaravelCoordinate\Traits\LaravelCoordinate;

class Wisata extends Model
{
    use HasFactory, LaravelCoordinate;
    protected $table = 'wisata';
    protected $primaryKey = 'id_wisata';
    public $incrementing = false;
    
    protected $fillable = [
        'nama_wisata',
        'jenis_wisata',
        'alamat',
        'kota',
        'gambar_wisata',
        'deskripsi',
        'kontak',
        'lat',
        'lng',
    ];

    public $_latitudeName = "lat"; //default name is latitude
    public $_longitudeName = "lng"; //default name is longitude

    public function wisata() {
        return $this->hasMany('App\Models\Rating', 'id_wisata');
    }

    public function rating() {
        return $this->hasMany('App\Models\Rating', 'id_wisata');
    }

}
