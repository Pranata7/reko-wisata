@extends('user.master2')
@section('active1', 'active')
@section('content')

<div class="hero-wrap js-fullheight" style="background-image: url('/usertemplate/images/landing.png');" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container-fluid px-md-5">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-start" data-scrollax-parent="true">
            <div class="col-md-6 ftco-animate">
                <h2 class="subheading" style="color:white; font-size: 30px;">SELAMAT DATANG</h2>
                <h1 class="mb-4">Saat ini anda berada di <b>
                        <div id="kota"></div>
                    </b></h1>
            </div>
        </div>
    </div>
</div>

<section class="ftco-section ftco-no-pt ftco-no-pb bg-primary">
    <div class="container py-4">
        <div class="row d-flex justify-content-center">
            <div class="col-md-7 ftco-animate d-flex align-items-center">
                <h2 class="mb-0" style="color:black; font-size: 30px;">Pilih wisata sesukamu</h2>
            </div>
            <div class="col-md-5 d-flex align-items-center">
                <div class="form-group d-flex">
                    <input type="text" class="form-control" id="search-wisata" placeholder="Cari Tempat Wisata">
                    <button onclick="window.location.href = '/user/login'" value="Search" class="submit px-3">Search</button>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section bg-light">
    <div class="container">
        <div class="row justify-content-center mb-5 pb-3">
            <div class="col-md-7 heading-section text-center ftco-animate">
                <h2>Rekomendasi Wisata terdekat 10 KM di sekitar Anda</h2>
            </div>
        </div>
        <div class="row d-flex" id="wisatas-in-city">
        </div>
    </div>
</section>

@push('script')
<script>
    wisataInCity();

    function wisataInCity() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function() {}, function() {}, {});

            navigator.geolocation.getCurrentPosition(function(location) {
                $.ajax({
                    url: "https://dataservice.accuweather.com/locations/v1/cities/geoposition/search",
                    type: "GET",
                    data: {
                        "apikey": "boCFKdy0IAYZh7p68IHY3VTZsB3oGoZG",
                        "q": location.coords.latitude + "," + location.coords.longitude,
                        "language": "en-us",
                        "details": false,
                        "toplevel": false
                    },
                    dataType: "json",
                    success: function(res) {
                        console.log(res)
                        location = res.SupplementalAdminAreas[0].LocalizedName;
                        lat = res.GeoPosition.Latitude;
                        lng = res.GeoPosition.Longitude;

                        document.getElementById('kota').innerHTML = location;
                        $.ajax({
                            url: "/api/wisata-in-city",
                            type: "POST",
                            data: {
                                "city": location,
                                "lat": lat,
                                "lng": lng
                            },
                            dataType: "json",
                            success: function(res) {
                                let wisatas = $('#wisatas-in-city');
                                let wisata = res;
                                console.log(wisata);
                                wisatas.empty();
                                for (let i = 0; i < wisata.length; i++) {
                                    $.ajax({
                                        url: "/api/showother",
                                        type: "POST",
                                        data: {
                                            "gambar_wisata": wisata[i].gambar_wisata,
                                            "id_wisata": wisata[i].id_wisata
                                        },
                                        dataType: "json",
                                        success: function(res) {
                                            console.log(res);
                                            let rating = res.rating.toFixed(2);
                                            let html = `
											<div class="col-md-4 d-flex">
												<div class="blog-entry">
												<img class="block-20" width="100px" height="50px" src="${res.gambar}">
												<div class="text p-4 float-right d-block">
													<div class="topper d-flex align-items-center">
														<div class="one py-2 pl-3 pr-1 align-self-stretch">
															<span style="color: white">${rating}</span>
														</div>
														<div class="two pl-0 pr-3 py-2 align-self-stretch">
															<img src="">
														</div>
													</div>
													<h3 class="heading mt-2"><a href="/user/wisata/${wisata[i].id_wisata}">${wisata[i].nama_wisata}</a></h3>
													<p>${wisata[i].deskripsi}</p>
													<small>${wisata[i].km.toFixed(2)} KM</small>
												</div>
												</div>
											</div>
											`
                                            wisatas.append(html);
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            }, function error(msg) {
                alert('Please enable your GPS position feature.');
            }, {
                timeout: 10000
            });
        } else {
            alert("Geolocation API is not supported in your browser.");
        }
    }

    function searchWisata() {
        let query = document.getElementById('search-wisata').value;
        $.ajax({
            url: "/api/search-wisata",
            type: "POST",
            data: {
                "search": query
            },
            dataType: "json",
            success: function(res) {
                let wisatas = $('#wisatas-search');
                let wisata = res;
                console.log(wisata);

                if (wisata.length < 1) {
                    searchResult.style.visibility = 'visible';
                    document.getElementById('alert').innerHTML = 'Tidak ada data';
                }

                wisatas.empty();
                for (let i = 0; i < wisata.length; i++) {
                    $.ajax({
                        url: "/api/showother",
                        type: "POST",
                        data: {
                            "gambar_wisata": wisata[i].gambar_wisata,
                            "id_wisata": wisata[i].id_wisata
                        },
                        dataType: "json",
                        success: function(res) {
                            console.log(res);
                            let rating = res.rating.toFixed(2);
                            let html = `
                            <div class="col-md-4 d-flex">
                                <div class="blog-entry">
                                <img class="block-20" width="100px" height="50px" src="${res.gambar}">
                                <div class="text p-4 float-right d-block">
                                    <div class="topper d-flex align-items-center">
                                        <div class="one py-2 pl-3 pr-1 align-self-stretch">
                                            <span style="color: white">${rating}</span>
                                        </div>
                                        <div class="two pl-0 pr-3 py-2 align-self-stretch">
                                            <img src="">
                                        </div>
                                    </div>
                                    <h3 class="heading mt-2"><a href="/user/wisata/${wisata[i].id_wisata}">${wisata[i].nama_wisata}</a></h3>
                                    <p>${wisata[i].deskripsi}</p>
                                </div>
                                </div>
                            </div>
                            `
                            wisatas.append(html);
                            document.getElementById('alert').innerHTML = 'Data ditemukan';
                            searchResult.style.visibility = 'visible';
                        }
                    });
                }
            }
        });
    }

    function searchTag(tag) {
        $.ajax({
            url: "/api/search-tag-wisata",
            type: "POST",
            data: {
                "jenis_wisata": tag
            },
            dataType: "json",
            success: function(res) {
                let wisatas = $('#wisatas-search');
                let wisata = res;
                console.log(wisata);

                if (wisata.length < 1) {
                    searchResult.style.visibility = 'visible';
                    document.getElementById('alert').innerHTML = 'Tidak ada data';
                }

                wisatas.empty();
                for (let i = 0; i < wisata.length; i++) {
                    $.ajax({
                        url: "/api/showother",
                        type: "POST",
                        data: {
                            "gambar_wisata": wisata[i].gambar_wisata,
                            "id_wisata": wisata[i].id_wisata
                        },
                        dataType: "json",
                        success: function(res) {
                            console.log(res);
                            let rating = res.rating.toFixed(2);
                            let html = `
                            <div class="col-md-4 d-flex">
                                <div class="blog-entry">
                                <img class="block-20" width="100px" height="50px" src="${res.gambar}">
                                <div class="text p-4 float-right d-block">
                                    <div class="topper d-flex align-items-center">
                                        <div class="one py-2 pl-3 pr-1 align-self-stretch">
                                            <span style="color: white">${rating}</span>
                                        </div>
                                        <div class="two pl-0 pr-3 py-2 align-self-stretch">
                                            <img src="">
                                        </div>
                                    </div>
                                    <h3 class="heading mt-2"><a href="/user/wisata/${wisata[i].id_wisata}">${wisata[i].nama_wisata}</a></h3>
                                    <p>${wisata[i].deskripsi}</p>
                                </div>
                                </div>
                            </div>
                            `
                            wisatas.append(html);
                            document.getElementById('alert').innerHTML = 'Data ditemukan';
                            searchResult.style.visibility = 'visible';
                        }
                    });
                }
            }
        });
    }
</script>
@endpush
@endsection