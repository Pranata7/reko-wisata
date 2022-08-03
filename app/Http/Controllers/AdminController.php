<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Models\Admin;
use App\Models\Wisata;
use App\Models\Users;
use App\Models\Rating;
use Exception;

class AdminController extends Controller {
    public function loginIndex() {
        if(Session::get('loginAdmin')){
            return redirect('/admin/dashboard');
        }

        $admin = Admin::get();
        if($admin->count() < 1){
            $adm = new Admin();
            $adm->id_admin = 'adm';
            $adm->username = 'admin';
            $adm->password = 'admin123';
            $adm->nama_admin = 'Default Admin';
            $adm->jk = 'P';
            $adm->no_hp = '088888888888';
            $adm->save();
        }

        return view('admin.login');
    }

    public function loginProcess(Request $request){
        $username = $request->username;
        $password = $request->password;

        $admin = Admin::where('username', $username)->first();

        if($admin){
            if(Hash::check($password, $admin->password)){
                Session::put('loginAdmin', Hash::make($admin->username));
                Session::put('namaAdmin', $admin->nama_admin);
                return redirect('/admin/dashboard')->with('alert-success', 'Login berhasil!');
            }else{
                return redirect('/admin/login')->with('alert-danger', 'Password salah!');
            }
        }else{
            return redirect('/admin/login')->with('alert-danger', 'Username salah!');
        }
    }

    public function logout(){
        if(!Session::get('loginAdmin')){
            return redirect('/admin/login');
        }

        Session::forget('loginAdmin');
        Session::forget('username');
        Session::forget('namaAdmin');

        return redirect('/admin/login')->with('alert-success', 'Logout berhasil');
    }

    public function dashboard(){
        if(!Session::get('loginAdmin')){
            return redirect('/admin/login');
        }
        
        $datawisata = Wisata::count();
        $datauser = Users::count();
        $datarating = Rating::count();

        return view('admin.dashboard', compact('datawisata', 'datauser', 'datarating'));
    }

}
