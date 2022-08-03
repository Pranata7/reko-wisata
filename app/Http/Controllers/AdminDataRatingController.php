<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\Rating;

class AdminDataRatingController extends Controller
{
    public function Index(){
        if(!Session::get('loginAdmin')){
            return redirect('/admin/login');
        }

        $rating = DB::table('rating')->get();
        return view('admin/data_rating',['rating' => $rating]);
    }

    public function search(Request $request)
	{
		if(!Session::get('loginAdmin')){
            return redirect('/admin/login');
        }
		// menangkap data pencarian
		$cari = $request->cari;
 
    		// mengambil data dari table pegawai sesuai pencarian data
		$rating = DB::table('rating')
		->where('username','like',"%".$cari."%")
		->paginate();
 
    		// mengirim data pegawai ke view index
		return view('admin/data_rating',['rating' => $rating]);
 
	}
}
