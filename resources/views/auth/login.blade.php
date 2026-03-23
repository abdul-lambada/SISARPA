<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | {{ \App\Models\Setting::get('school_name', 'SISARPA') }}</title>
@php $logo = \App\Models\Setting::get('school_logo'); @endphp
@php $schoolName = \App\Models\Setting::get('school_name', 'SISARPA'); @endphp

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <style>
        .login-page {
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .login-box {
            width: 400px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s;
        }
        .login-box:hover { transform: translateY(-5px); }
        .card { border: none !important; }
        .btn-primary { background: #2c5364; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        .btn-primary:hover { background: #0f2027; }
        .back-to-home { color: #fff; text-decoration: none; position: absolute; top: 20px; left: 20px; font-weight: 500; font-size: 0.9rem; }
        .back-to-home:hover { color: #00d2ff; }
    </style>
</head>

<body class="hold-transition login-page">
    <a href="{{ url('/') }}" class="back-to-home"><i class="fas fa-arrow-left mr-2"></i>Back to Homepage</a>
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <div class="mb-3">
                    @if($logo && $logo != 'default_logo.png')
                        <img src="{{ asset('storage/settings/' . $logo) }}" alt="Logo" class="img-fluid" style="max-height: 80px;">
                    @else
                        <i class="fas fa-school fa-3x text-primary"></i>
                    @endif
                </div>
                <a href="/" class="h1"><b>{{ substr($schoolName, 0, 2) }}</b>{{ substr($schoolName, 2) }}</a>
                <p class="mb-0 text-muted small text-bold text-uppercase">Sistem Inventaris & Sarpras</p>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Silahkan masuk untuk memulai sesi</p>

                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="login" class="form-control @error('login') is-invalid @enderror"
                            placeholder="Email / Username / NISN" value="{{ old('login') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user-circle"></span>
                            </div>
                        </div>
                        @error('login')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Password (password)">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">
                                    Ingat Saya
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Masuk</button>
                        </div>
                    </div>
                </form>

                <div class="mt-4 p-3 bg-light rounded border">
                    <small class="text-muted d-block text-center font-weight-bold mb-2">PANDUAN LOGIN</small>
                    <ul class="text-xs mb-0">
                        <li>Gunakan **Username** atau **Email** dari Buku Induk Digital.</li>
                        <li>Siswa login menggunakan **NISN** sebagai Username & Password.</li>
                        <li>Contoh Admin: <code>admin</code> / password: <code>admin</code></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
</body>

</html>