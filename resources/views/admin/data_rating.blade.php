@extends('admin.master')
@section('title_bar', 'Data Rating')
@section('title', 'Data Rating') 
@section('title_breadcrumb', 'Data Rating')
@section('active3', 'active')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <form action="/datarating/search" method="GET" role="search">
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
                    
                    <thead>
                    <tr>
                      <th style="width: 5px">No</th>
                      <th style="width: 200px">Username</th>
                      <th style="width: 200px">Nama Wisata</th>
                      <th style="width: 100px">Rating</th>
                    </tr>
                    </thead>
                    <tbody>
                      @foreach($rating as $key => $r)
                    <tr>
                      <td>{{ ++$key }}</td>
                      <td>{{ $r->username }}</td>
                      {{-- <td>{{ $h->password }}</td> --}}
                      <td>{{ $r->id_wisata }}</td>
                      <td>{{ $r->angka_rating }}</td>
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