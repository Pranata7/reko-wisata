@extends('admin.master')
@section('title_bar', 'Dashboard')
@section('title', 'Dashboard')   
@section('title_breadcrumb', 'Dashboard')
@section('active1', 'active')
@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-4 col-3">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{ $datawisata }}</h3> 
{{-- ngelink ke data wisata cari di google --}}
          <p>Data Wisata</p>
        </div>
        <div class="icon">
          <i class="ion ion-bag"></i>
        </div>
        <a href="/datawisata" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 col-3">
      <!-- small box -->
      <div class="small-box bg-success">
        <div class="inner">
          <h3>{{ $datauser }}</h3>

          <p>Data User</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
        <a href="/datauser" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 col-3">
      <!-- small box -->
      <div class="small-box bg-warning">
        <div class="inner">
          <h3>{{ $datarating }}</h3>

          <p>Data Rating</p>
        </div>
        <div class="icon">
          <i class="ion ion-person-add"></i>
        </div>
        <a href="/datarating" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    
    <!-- ./col -->
  </div>
  <!-- /.row -->
  <!-- Main row -->
  <div class="row">
    <!-- Left col -->
    <section class="col-lg-7 connectedSortable">
    </section>

    <section class="col-lg-5 connectedSortable">

    </section>
    <!-- right col -->
  </div>
<!-- /.row (main row) -->

@push('style')
    
@endpush

@push('script') 
    <script>
        
    </script>    
@endpush

@endsection
    

