@extends('user.master')
@section('content')

<section class="hero-wrap hero-wrap-2" style="background-image: url('/usertemplate/images/landing.png')" data-stellar-background-ratio="0.5">
  <div class="overlay"></div>
  <div class="container">
    <div class="row no-gutters slider-text align-items-end justify-content-center">
      <div class="col-md-9 ftco-animate pb-5 text-center">
        <h1 class="mb-3 bread">{{$wisata->nama_wisata}}</h1>
        <p class="breadcrumbs"><span class="mr-2"><a href="/user/home">Home <i class="ion-ios-arrow-forward"></i></a></span> <span class="mr-2"><a href="#">Detail wisata <i class="ion-ios-arrow-forward"></i></a></span> <span>{{$wisata->nama_wisata}}<i class="ion-ios-arrow-forward"></i></span></p>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section ftco-degree-bg">
  <div class="container ">
    <div class="row">
      <div class="col-lg-8 ftco-animate border">
        <p>
          <img src="{{Storage::url($wisata->gambar_wisata)}}" alt="" class="img-fluid">
        </p>
        <h2 class="mb-3">{{$wisata->nama_wisata}}</h2>
        <span class="badge badge-pill badge-primary">{{$wisata->jenis_wisata}}</span>
        <p>
          {!!$wisata->deskripsi!!}
        </p>
        <p>
          Alamat: {{$wisata->alamat}}
        </p>
        <iframe src="https://maps.google.com/maps?q={{$wisata->lat}}, {{$wisata->lng}}&z=15&output=embed" width="360" height="270" frameborder="0" style="border:0"></iframe>
      </div> <!-- .col-md-8 -->
      <div class="col-lg-4 sidebar ftco-animate">
        <div class="sidebar-box">
          <div class="container">
            <div class="card">
              <div class="card-body">
                <h2 class="mb-4">Rating Wisata</h2>
                <span class="subheading">Silahkan merating wisata!</span>
                @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                  <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                    @endforeach
                  </ul>
                </div>
                @endif
                @if(Session::has('alert-danger'))
                <div class="alert alert-danger">
                  <div>{{Session::get('alert-danger')}}</div>
                </div>
                @endif
                @if(Session::has('alert-success'))
                <div class="alert alert-success">
                  <div>{{Session::get('alert-success')}}</div>
                </div>
                @endif
                <form action="/user/rating/add" method="POST" class="consultation">
                  {{ csrf_field() }}
                  <input type="hidden" name="wisata" value="{{$wisata->id_wisata}}">
                  <div class="form-group">
                    <input type="text" class="form-control" name="username" placeholder="Username" value="{{$username}}" readonly>
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control" name="nama" placeholder="Nama" value="{{$nama}}" readonly>
                  </div>

                  <div class="container ">
                    <span id="rateMe1"></span>
                  </div>

                  <div class="container ">
                    <div class="rate">
                      <input type="radio" id="star5" name="angka_rating" value="5" />
                      <label for="star5" title="Sangat Baik">5 stars</label>
                      <input type="radio" id="star4" name="angka_rating" value="4" />
                      <label for="star4" title="Baik">4 stars</label>
                      <input type="radio" id="star3" name="angka_rating" value="3" />
                      <label for="star3" title="Cukup">3 stars</label>
                      <input type="radio" id="star2" name="angka_rating" value="2" />
                      <label for="star2" title="Buruk">2 stars</label>
                      <input type="radio" id="star1" name="angka_rating" value="1" />
                      <label for="star1" title="Sangat Buruk">1 star</label>
                    </div>
                  </div>
                  <div class="comment-area mb-3">

                    <textarea class="form-control" placeholder="Masukkan komentar Anda" rows="4" name="komentar"></textarea>

                  </div>
                  <div class="form-group">
                    <input type="submit" value="Selesai" class="btn btn-primary py-3 px-4">
                  </div>
                </form>
              </div>
            </div>
            <div class="card">
              <div class="card-body">
                <h2>Komentar</h2>
                <hr>
                @foreach ($wisata->rating as $rating)
                @if ($rating->komentar != "")
                <div class="mb-2">
                  <p>{{$rating->username}}</p>
                  "{{$rating->komentar}}"
                </div>
                @endif
                
                @endforeach

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section> <!-- .section -->