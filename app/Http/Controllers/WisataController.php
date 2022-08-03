<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\Wisata;

class WisataController extends Controller
{
    public function index()
    {
		if(!Session::get('loginAdmin')){
            return redirect('/admin/login');
        }
    	$wisata = DB::table('wisata')->get();

    	// mengirim data wisata ke view index
    	return view('admin/data_wisata',['wisata' => $wisata]);

    }

    public function tambah()
    {
		if(!Session::get('loginAdmin')){
            return redirect('/admin/login');
        }
		//jenis wisata
		$wisata = Wisata::get();
    	// mengirim data pegawai ke view index
    	return view('admin/tambah');

    }

    public function store(Request $request)
	{
		if(!Session::get('loginAdmin')){
            return redirect('/admin/login');
        }
		// validasi upload gambar
		if($this->validate($request, [
			'gambar_wisata' => 'required|file'
		]))
		{$path = Storage::putfile('public/images', $request->file('gambar_wisata'));

		// insert data ke table pegawai
		DB::table('wisata')->insert([
			'nama_wisata' => $request->nama_wisata,
			'jenis_wisata' => $request->jenis_wisata,
			'alamat' => $request->alamat,
			'kota' => $request->kota,
			'gambar_wisata' => $path,
			'deskripsi' => $request->deskripsi,
			'kontak' => $request->kontak
		]);
		// alihkan halaman ke halaman pegawai
		return redirect('/datawisata')->with('alert-success', 'Wisata berhasil ditambahkan!');
	} else { 
		return redirect('/datawisata')->with('alert-danger', 'Wisata gagal ditambahkan!');
	}
}

	public function edit($id_wisata)
	{
		if(!Session::get('loginAdmin')){
            return redirect('/admin/login');
        }
		// mengambil data pegawai berdasarkan id yang dipilih
		$wisata = DB::table('wisata')->where('id_wisata',$id_wisata)->get();
		// passing data pegawai yang didapat ke view edit.blade.php
		return view('admin/edit',['wisata' => $wisata]);
	}

	public function update(Request $request)
	{
		if(!Session::get('loginAdmin')){
            return redirect('/admin/login');
        }

		$path = '';

		if($request->gambar_wisata){
			$this->validate($request, [
				'gambar_wisata' => 'required|file|max:7000'
			]);
			$path = Storage::putfile('public/images', $request->file('gambar_wisata'));
		}else {
			$wisata = Wisata::find($request->id_wisata);
			$path = $wisata->gambar_wisata;
		}
		// update data wisata
		DB::table('wisata')->where('id_wisata',$request->id_wisata)->update([
			'nama_wisata' => $request->nama_wisata,
			'jenis_wisata' => $request->jenis_wisata,
			'alamat' => $request->alamat,
			'kota' => $request->kota,
			'gambar_wisata' => $path,
			'deskripsi' => $request->deskripsi,
			'kontak' => $request->kontak
		]);
		// alihkan halaman ke halaman data wisata
		return redirect('/datawisata')->with('alert-success', 'Wisata berhasil diubah!');;
	}

	public function hapus($id_wisata)
	{
		if(!Session::get('loginAdmin')){
            return redirect('/admin/login');
        }
		// update data pegawai
		DB::table('wisata')->where('id_wisata',$id_wisata)->delete();
		// alihkan halaman ke halaman pegawai
		return redirect('/datawisata')->with('alert-success', 'Wisata berhasil dihapus!');;
	}

	public function search(Request $request)
	{
		if(!Session::get('loginAdmin')){
            return redirect('/admin/login');
        }
		// menangkap data pencarian
		$cari = $request->cari;

    		// mengambil data dari table pegawai sesuai pencarian data
		$wisata = DB::table('wisata')
		->where('nama_wisata','like',"%".$cari."%")
		->paginate();

    		// mengirim data pegawai ke view index
		return view('admin/data_wisata',['wisata' => $wisata]);
	}
}
