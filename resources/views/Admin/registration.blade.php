<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sedap Malam Registrasi</title>
    <link href="assets\logo-posyandu.png" rel="icon">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo-posyandu.png') }}">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('/adminlte//plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('/adminlte/dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition register-page">
    <div class="register-box">
        <div class="register-logo">
            <a href="/" class="logo"><img src="{{ asset('assets/logo-posyandu.png') }}"
                    alt="Medismart"class="logo" style="max-width: 200px;" /></a>
        </div>

        <div class="card">
            <div class="card-body register-card-body">
                <p class="login-box-msg">Registrasi Admin Posyandu Sedap Malam</p>
                <form action="/admin-registrasi-only" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="nama_kader"
                            class="form-control @error('nama_kader') is-invalid @enderror" id="nama_kader"
                            placeholder="Nama Kader" value="{{ old('nama_kader') }}">
                        <div class="input-group-append">
                            <div class="input-group-text"></div>
                        </div>
                        @error('nama_kader')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" name="jabatan"
                            class="form-control @error('jabatan') is-invalid @enderror" id="jabatan"
                            placeholder="Jabatan" value="{{ old('jabatan') }}">
                        <div class="input-group-append">
                            <div class="input-group-text"></div>
                        </div>
                        @error('jabatan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" name="alamat"
                            class="form-control @error('alamat') is-invalid @enderror" id="alamat"
                            placeholder="Alamat" value="{{ old('alamat') }}">
                        <div class="input-group-append">
                            <div class="input-group-text"></div>
                        </div>
                        @error('alamat')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" name="no_telpon"
                            class="form-control @error('no_telpon') is-invalid @enderror" id="no_telpon"
                            placeholder="Nomor Telpon" value="{{ old('no_telpon') }}">
                        <div class="input-group-append">
                            <div class="input-group-text"></div>
                        </div>
                        @error('no_telpon')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" name="username"
                            class="form-control @error('username') is-invalid @enderror" id="username"
                            placeholder="Username" value="{{ old('username') }}">
                        <div class="input-group-append">
                            <div class="input-group-text"></div>
                        </div>
                        @error('username')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" id="password"
                            placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text"></div>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-sm">
                        <button href="/login" type="submit" class="btn btn-primary btn-block">Register</button>
                    </div>
                </form>
            </div>
            <a href="/login" class="text-center mb-3">Sudah punya akun?</a>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('/adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('/adminlte/dist/js/adminlte.min.js') }}"></script>
</body>

</html>
