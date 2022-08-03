 @extends('admin.master')
@section('title_bar', 'Data Wisata')
@section('title', 'Data WIsata') 
@section('title_breadcrumb', 'Data Wisata')
@section('active3', 'active')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">

                <form action="/datawisata/store" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="card-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Nama Wisata</label>
                        <input type="text" class="form-control" name="nama_wisata" placeholder="Nama Wisata">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Kategori Wisata</label>
                        <select class="custom-select" name="jenis_wisata">
                          <option selected>Kategori</option>
                          <option value="Religi">Religi</option>
                          <option value="Belanja">Belanja</option>
                          <option value="Edukasi">Edukasi</option>
                          <option value="Rekreasi">Reksiasi</option>
                        </select>
                        </div>
                        <div class="form-group">
                          <label for="exampleInputEmail1">Alamat</label>
                          <input type="text" class="form-control" name="alamat" placeholder="Alamat">
                        </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Kota</label>
                        <input type="text" class="form-control" name="kota" placeholder="Kota">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Gambar Wisata</label>
                        <input type="file" class="form-control" name="gambar_wisata" placeholder="Gambar Wisata">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Deskripsi Wisata</label>
                        <input type="text" class="form-control" name="deskripsi" placeholder="Deskripsi Wisata">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Kontak</label>
                        <input type="text" class="form-control" name="kontak" placeholder="Kontak">
                      </div>
                    <div class="card-footer">
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                  </form>
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