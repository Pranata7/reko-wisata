@extends('user.master')
@section('active2', 'active')
@section('content')
<section class="hero-wrap hero-wrap-2" style="background-image: url('/usertemplate/images/landing.png');" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
            <div class="col-md-9 ftco-animate pb-5 text-center">
                <h1 class="mb-3 bread">Rating</h1>
                <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home <i class="ion-ios-arrow-forward"></i></a></span> <span class="mr-2"><a href="blog.html">Rating <i class="ion-ios-arrow-forward"></i></a></span></p>
            </div>
        </div>
    </div>
</section>
<section class="ftco-consultation ftco-section img" style="background-image: url(/usertemplate/images/vektor.jpg);">
    <div class="overlay"></div>
    <div class="container">
        <div class="row d-md-flex justify-content-end">
            <div class="col-md-6 half p-3 p-md-5 ftco-animate heading-section">
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
                            <div class="form-group">
                                <input type="text" class="form-control" name="username" placeholder="Username" value="{{$username}}" readonly>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="nama" placeholder="Nama" value="{{$nama}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Pilih Wisata</label>
                                <select class="form-control js-example-basic-single" name="wisata" id="exampleFormControlSelect1" required>
                                    <option value=" ">--- Pilih Wisata ---</option>
                                    @foreach ($wisata as $htl)
                                    <option value="{{$htl->id_wisata}}">{{$htl->nama_wisata}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="container">
                                <span id="rateMe1"></span>
                            </div>

                            <div class="container">
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
                            <div class="form-group">
                                <input type="submit" value="Selesai" class="btn btn-primary py-3 px-4">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('script')
<script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // In your Javascript (external .js resource or <script> tag)
    $('.js-example-basic-single').select2();
</script>
<!-- rating.js file -->
<script src="js/addons/rating.js"></script>
@endpush
</section>