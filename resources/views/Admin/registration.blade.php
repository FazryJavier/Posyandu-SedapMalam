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
                        <input type="text" name="name"
                            class="form-control @error('name') is-invalid @enderror" id="name"
                            placeholder="Nama Kader" value="{{ old('name') }}">
                        <div class="input-group-append">
                            <div class="input-group-text"></div>
                        </div>
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" name="position"
                            class="form-control @error('position') is-invalid @enderror" id="position"
                            placeholder="Jabatan" value="{{ old('position') }}">
                        <div class="input-group-append">
                            <div class="input-group-text"></div>
                        </div>
                        @error('position')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" name="address"
                            class="form-control @error('address') is-invalid @enderror" id="address"
                            placeholder="Alamat" value="{{ old('address') }}">
                        <div class="input-group-append">
                            <div class="input-group-text"></div>
                        </div>
                        @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" name="phone"
                            class="form-control @error('phone') is-invalid @enderror" id="phone"
                            placeholder="Nomor Telpon" value="{{ old('phone') }}">
                        <div class="input-group-append">
                            <div class="input-group-text"></div>
                        </div>
                        @error('phone')
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
