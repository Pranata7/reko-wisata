@extends('admin.master')
@section('title_bar', 'Data Wisata')
@section('title', 'Data Wisata') 
@section('title_breadcrumb', 'Data Wisata')
@section('active3', 'active')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                @foreach($wisata as $p)
                <form action="/datawisata/update" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_wisata" value="{{ $p->id_wisata }}"> <br/>
                    <div class="card-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Nama Wisata</label>
                        <input type="text" class="form-control" name="nama_wisata" value="{{ $p->nama_wisata }}" placeholder="Nama Wisata">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Kategori Wisata</label>
                        <select class="custom-select" name="jenis_wisata">
                          @if ($p->jenis_wisata == 'Religi')
                          <option value="Religi" selected>Religi</option>
                          <option value="Belanja">Belanja</option>
                          <option value="Edukasi">Edukasi</option>
                          <option value="Rekreasi">Rekreasi</option>
                          @elseif($p->jenis_wisata == 'Belanja')
                          <option value="Belanja" selected>Belanja</option>
                          <option value="Religi">Religi</option>
                          <option value="Edukasi">Edukasi</option>
                          <option value="Rekreasi">Rekreasi</option>
                          @elseif($p->jenis_wisata == 'Edukasi')
                          <option value="Edukasi" selected>Edukasi</option>
                          <option value="Religi">Religi</option>
                          <option value="Belanja">Belanja</option>
                          <option value="Rekreasi">Rekreasi</option>
                          @elseif($p->jenis_wisata == 'Rekreasi')
                          <option value="Rekreasi" selected>Rekreasi</option>
                          <option value="Religi">Religi</option>
                          <option value="Belanja">Belanja</option>
                          <option value="Edukasi">Edukasi</option>
                          @else
                          <option value="Religi">Religi</option>
                          <option value="Belanja">Belanja</option>
                          <option value="Edukasi">Edukasi</option>
                          <option value="Rekreasi">Rekreasi</option>
                          @endif

                        </select>
                        </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Alamat</label>
                        <input type="text" class="form-control" name="alamat" value="{{ $p->alamat }}" placeholder="Alamat">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Kota</label>
                        <input type="text" class="form-control" name="kota" value="{{ $p->kota }}" placeholder="Kota">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Gambar Wisata</label>
                        <input type="file" class="form-control" name="gambar_wisata" placeholder="Gambar Wisata">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Deskripsi Wisata</label>
                        <input type="text" class="form-control" name="deskripsi" value="{{ $p->deskripsi }}" placeholder="Deskripsi Wisata">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Kontak</label>
                        <input type="text" class="form-control" name="kontak" value="{{ $p->kontak }}" placeholder="Kontak">
                      </div>
                    <div class="card-footer">
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                  </form>
                  @endforeach

              <!-- /.card -->
                <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
      </section>
      <!-- /.content -->
            @push('style')
    
            @endpush
            
            @push('script') 

           
            @endpush
@endsection