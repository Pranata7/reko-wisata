<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use App\Models\Users;
use App\Models\Wisata;
use App\Models\Rating;
use Exception;
use Illuminate\Support\Facades\Mail;
use DB;
class UsersController extends Controller
{
	public function regisIndex(){
	    return view('user.registrasi');
	}

    public function regisSave(Request $request){
        // dd($request->all());
        $user = new Users();
        $user->nama_user = $request->nama_user;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;
        $user->alamat = $request->alamat;
        $user->jk = $request->jk;
        $user->username = $request->username;
        $user->password = $request->password;
        $user->save();

        return redirect('/user/login');
    }

	public function loginIndex() {
        if(Session::get('loginUser')){
            return redirect('/user/home');
        }

        return view('user.login');
    }

	public function loginProcess(Request $request){
        $username = $request->username;
        $password = $request->password;

        $user = Users::where('username', $username)->first();

        if($user){
            if(Hash::check($password, $user->password)){
                Session::put('loginUser', Hash::make($user->username));
                Session::put('usernameuser', $user->username);
				Session::put('nameUser', $user->nama_user);
                return redirect('/user/home')->with('alert-success', 'Login berhasil!');
            }else{
                return redirect('/user/login')->with('alert-danger', 'Password salah!');
            }
        }else{
            return redirect('/user/login')->with('alert-danger', 'Username salah!');
        }
    }

	public function logout(){
        if(!Session::get('loginUser')){
            return redirect('/user/login');
        }

        Session::forget('loginUser');
        Session::forget('usernameuser');
        Session::forget('namaUser');

		return redirect('/user/login')->with('alert-success', 'Logout berhasil!');
    }

    public function landing(){
        if(Session::get('loginUser')){
            return redirect('/user/home');
        }

        return view('user.landing_page');
    }

	public function home(){
        if(!Session::get('loginUser')){
            return redirect('/user/login')->with('alert-danger', 'Anda harus login terlebih dahulu!');
        }

		$nama = Session::get('nameUser');
        $username = Session::get('usernameuser');
        $jenis_wisata = Wisata::select('jenis_wisata')->groupBy('jenis_wisata')->get();

        return view('user.home', compact('nama', 'username', 'jenis_wisata'));
    }

    public function detailWisata($idWisata){
        if(!Session::get('loginUser')){
            return redirect('/user/login')->with('alert-danger', 'Anda harus login terlebih dahulu!');
        }

        $username = Session::get('usernameuser');
        $nama = Session::get('nameUser');
        $wisata = Wisata::where('id_wisata', $idWisata)->first();
        return \view('user.detail_wisata',\ compact('wisata', 'username','nama'));
    }

    public function riwayatRating(){
        if(!Session::get('loginUser')){
            return redirect('/user/login')->with('alert-danger', 'Anda harus login terlebih dahulu!');
        }

        $username = Session::get('usernameuser');
        return view('user.riwayat', compact('username'));
    }

    public function rating(){
        if(!Session::get('loginUser')){
            return redirect('/user/login')->with('alert-danger', 'Anda harus login terlebih dahulu!');
        }

        $username = Session::get('usernameuser');
        $nama = Session::get('nameUser');
        $wisata = Wisata::get();
        return view('user.rating', compact('username', 'wisata', 'nama'));
    }

    public function addRating(Request $request) {
        if(!Session::get('loginUser')){
            return redirect('/user/login');
        }

        $this->validate($request, [
            'wisata' => '|required'
        ]);

        $username = $request->username;
        $idWisata = $request->wisata;
        $angka_rating = $request->angka_rating;
        $komentar = $request->komentar;

        $rated = Rating::where('username', $username)->where('id_wisata', $idWisata)->get();
        if($rated->count() > 0){
            return \redirect()->back()->with('alert-danger', 'Anda sudah pernah merating wisata!');
        }

        $rating = new Rating();
        $rating->id_rating = uniqid();
        $rating->username = $username;
        $rating->id_wisata = $idWisata;
        $rating->angka_rating = $angka_rating;
        $rating->komentar = $komentar;
        // dd($request);
        $rating->save();
        return \redirect()->back()->with('alert-success', 'Berhasil menambah rating!');
    }

    public function index()
    {
		if(!Session::get('loginAdmin')){
            return redirect('/admin/login');
        }
        $user = DB::table('users')->get();

    	// mengirim data pegawai ke view index
    	return view('admin/data_user',['users' => $user]);
    }

    public function search(Request $request)
	{
		if(!Session::get('loginAdmin')){
            return redirect('/admin/login');
        }
		// menangkap data pencarian
		$cari = $request->cari;

    		// mengambil data dari table pegawai sesuai pencarian data
		$user = DB::table('users')
		->where('nama_user','like',"%".$cari."%")
		->paginate();

    		// mengirim data pegawai ke view index
		return view('admin/data_user',['users' => $user]);

	}
}
