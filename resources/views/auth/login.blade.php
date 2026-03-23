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
                        <img src="{{ asset('storage/settings/' . $logo) }}" alt="Logo" class="h-16 w-auto">
                    @elseif(request('as') == 'siswa')
                        <i class="fas fa-user-graduate fa-3x text-primary"></i>
                    @elseif(request('as') == 'guru')
                        <i class="fas fa-chalkboard-teacher fa-3x text-emerald-500"></i>
                    @else
                        <i class="fas fa-school fa-3x text-primary"></i>
                    @endif
                </div>
                <a href="/" class="h4 d-block font-weight-bold">{{ $schoolName }}</a>
                <h5 class="text-bold mt-2">
                    @if(request('as') == 'siswa')
                        Masuk Area Siswa
                    @elseif(request('as') == 'guru')
                        Masuk Area Guru
                    @else
                        Masuk Sistem Sarpras
                    @endif
                </h5>
            </div>
            <div class="card-body">
                @php
                    $placeholder = "Email / Username";
                    if(request('as') == 'siswa') $placeholder = "Nomor Induk (NISN)";
                    if(request('as') == 'guru') $placeholder = "NUPTK / Email Sekolah";
                @endphp
                <p class="login-box-msg">Silahkan masuk ke akun Anda</p>

                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="login" class="form-control @error('login') is-invalid @enderror"
                            placeholder="{{ $placeholder }}" value="{{ old('login') }}" autofocus>
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
                            placeholder="Kata Sandi / Password">
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
                    <small class="text-muted d-block text-center font-weight-bold mb-2 uppercase">Informasi Login:</small>
                    <ul class="text-xs mb-0">
                        @if(request('as') == 'siswa')
                            <li>Gunakan **NISN** sebagai Username.</li>
                            <li>Password awal adalah **NISN** Anda.</li>
                        @elseif(request('as') == 'guru')
                            <li>Gunakan **NUPTK** atau Email Sekolah.</li>
                            <li>Password sesuai yang terdaftar di Buku Induk Digital.</li>
                        @else
                            <li>Masuk sebagai Petugas atau Administrator.</li>
                            <li>Contoh: <code>admin</code> / password: <code>admin</code></li>
                        @endif
                    </ul>
                </div>
                @if(request('as'))
                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="text-xs text-muted">Bukan {{ request('as') }}? Masuk sebagai admin/petugas</a>
                    </div>
                @endif
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