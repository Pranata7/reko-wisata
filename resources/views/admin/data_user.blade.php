@extends('admin.master')
@section('title_bar', 'DataUser')
@section('title', 'Data User') 
@section('title_breadcrumb', 'Data User')
@section('active3', 'active')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <form action="/datauser/search" method="GET" role="search">
                    {{ csrf_field() }}

                  <table id="example2" class="table table-bordered table-hover">
                    <div class="row">
                    <div class="col-6">
                    <input type="search" class="form-control form-control-md" name="cari" placeholder="Ketik nama user yang ingin dicari..." >
                    </div>
                    <div class="col-6">
                    <button type="submit" class="btn btn-md btn-default">
                      <i class="fa fa-search"></i>
                  </button>
                </div>
                  </div><br>  </form>          
                    {{-- <a href="/datawisata/tambah"><button type="button"  style="width: 10%" class="btn btn-block btn-success">Tambah</button></a> --}}
                    {{-- <br> --}}
                    <thead>
                    <tr>
                      <th style="width: 5px">No</th>
                      <th style="width: 250px">Email</th>
                      <th style="width: 250px">Username</th>
                      <th style="width: 250px">Nama User</th>
                      <th style="width: 100px">JK</th>
                      <th style="width: 250px">No Handphone</th>
                      <th style="width: 400px">Alamat</th>
                    </tr>
                    </thead>
                    <tbody>
                      @foreach($users as $key => $h)
                    <tr>
                      <td>{{ ++$key }}</td>
                      <td>{{ $h->email }}</td>
                      <td>{{ $h->username }}</td>
                      {{-- <td>{{ $h->password }}</td> --}}
                      <td>{{ $h->nama_user }}</td>
                      <td>{{ $h->jk }}</td>
                      <td>{{ $h->no_hp }}</td>
                      <td>{{ $h->alamat }}</td>
                      {{-- <td>
                        <a href="/datawisata/edit/{{ $h->id_wisata }}"><button type="button" class="btn btn-block btn-success">Edit</button></a>
                        <a href="/datawisata/hapus/{{ $h->id_wisata }}"><button style="margin-top: 5%" type="button"  class="btn btn-block btn-danger">Hapus</button></a>
                      </td> --}}
                    </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
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
                <script>
                    
                </script>    
            @endpush
@endsection