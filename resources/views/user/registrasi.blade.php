<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registrasi</title>

    <!-- Font Icon -->
    <link rel="stylesheet" href="/regisform/fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="/regisform/css/style.css">
</head>
<body>

    <div class="main">

        <section class="signup">
            <!-- <img src="images/signup-bg.jpg" alt=""> -->
            <div class="container">
                <div class="signup-content">
                    <form method="POST" action="/user/simpanregistrasi" id="signup-form" class="signup-form">
                        <h2 class="form-title">Registrasi Akun</h2>

                            {{ csrf_field() }}

                        <div class="form-group">
                            <input type="text" class="form-input" name="nama_user" id="nama_user" placeholder="Nama Anda"/>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-input" name="email" id="email_user" placeholder="Email Anda"/>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-input" name="no_hp" id="no_hp" placeholder="Nomor HP"/>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-input" name="alamat" id="alamat" placeholder="Alamat"/>
                        </div>
                        <div class="form-check">
                            Jenis Kelamin <br>
                            <input class="form-check-input" type="radio" name="jk" id="jk">
                            <label class="form-check-label" for="jk">
                              Laki-Laki
                            </label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="jk" id="jk2" checked>
                            <label class="form-check-label" for="jk2">
                              Perempuan
                            </label>
                          </div>
                        <div class="form-group">
                            <input type="text" class="form-input" name="username" id="username" placeholder="Username"/>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-input" name="password" id="password" placeholder="Password"/>
                            <span toggle="#password" class="zmdi zmdi-eye field-icon toggle-password"></span>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-input" name="re_password" id="re_password" placeholder="Repeat your password"/>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" id="submit" class="form-submit" value="Sign up"/>
                        </div>
                    </form>
                    <p class="loginhere">
                        Sudah memiliki akun? <a href="/user/login" class="loginhere-link">Login disini</a>
                    </p>
                </div>
            </div>
        </section>

    </div>

    <!-- JS -->
    <script src="/regisform/vendor/jquery/jquery.min.js"></script>
    <script src="/regisform/js/main.js"></script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>