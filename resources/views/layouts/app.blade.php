<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ \App\Models\Setting::get('school_name', 'SISARPA') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <style>
        .main-sidebar { background: linear-gradient(180deg, #1c2b36 0%, #343a40 100%) !important; }
        .nav-sidebar .nav-item .active { border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        .card { border-radius: 12px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.05) !important; transition: transform .2s ease; }
        .card:hover { transform: translateY(-2px); }
        .btn { border-radius: 8px; font-weight: 500; transition: all 0.3s; }
        .btn:hover { box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .content-header h1 { font-size: 1.5rem; letter-spacing: -0.5px; }
        .breadcrumb { background: transparent; padding: 0; }
        .main-footer { font-size: 0.85rem; border-top: 1px solid #eee; }
        .badge { padding: 0.5em 0.8em; border-radius: 6px; }
    </style>
    @stack('css')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('dashboard') }}" class="brand-link">
                @php $logo = \App\Models\Setting::get('school_logo'); @endphp
                @if($logo && $logo != 'default_logo.png')
                    <img src="{{ asset('storage/settings/' . $logo) }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                @else
                    <i class="fas fa-school brand-image elevation-3 p-1 mt-1"></i>
                @endif
                <span class="brand-text font-weight-light">{{ \App\Models\Setting::get('school_name', 'SISARPA') }}</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="info">
                        <a href="#" class="d-block">{{ auth()->user()->name ?? 'Guest' }}</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        @hasanyrole('Super Admin|Petugas Sarpras')
                        <li class="nav-header">DATA UTAMA</li>
                        <li class="nav-item">
                            <a href="{{ route('kategori.index') }}"
                                class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>Kelompok Barang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('barang.index') }}"
                                class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-boxes"></i>
                                <p>Inventaris Barang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('ruangan.index') }}"
                                class="nav-link {{ request()->routeIs('ruangan.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-warehouse"></i>
                                <p>Daftar Ruangan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}"
                                class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-friends"></i>
                                <p>Data Guru & Siswa</p>
                            </a>
                        </li>
                        @endhasanyrole

                        <li class="nav-header">PELAYANAN SEKOLAH</li>
                        <li class="nav-item">
                            <a href="{{ route('peminjaman.index') }}"
                                class="nav-link {{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-hand-holding"></i>
                                <p>Pinjam Barang (Aset)</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('reservasi.index') }}"
                                class="nav-link {{ request()->routeIs('reservasi.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-door-closed"></i>
                                <p>Pakai Ruangan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('laporan-kerusakan.index') }}"
                                class="nav-link {{ request()->routeIs('laporan-kerusakan.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tools"></i>
                                <p>Lapor Kerusakan</p>
                            </a>
                        </li>

                        @hasanyrole('Super Admin|Petugas Sarpras')
                        <li class="nav-header">MONITORING & LAPORAN</li>
                        <li class="nav-item">
                            <a href="{{ route('stock-opname.index') }}"
                                class="nav-link {{ request()->routeIs('stock-opname.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-clipboard-check"></i>
                                <p>Cek Stok Fisik (Opname)</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('penggunaan-bhp.index') }}"
                                class="nav-link {{ request()->routeIs('penggunaan-bhp.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>Pemakaian Barang (BHP)</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pemeliharaan.index') }}"
                                class="nav-link {{ request()->routeIs('pemeliharaan.*') && !request()->routeIs('pemeliharaan.analysis') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-screwdriver"></i>
                                <p>Riwayat Servis</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pemeliharaan.analysis') }}"
                                class="nav-link {{ request()->routeIs('pemeliharaan.analysis') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-pie"></i>
                                <p>Analisis Biaya (Ka.Sek)</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('laporan.mutasi') }}"
                                class="nav-link {{ request()->routeIs('laporan.mutasi*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-pdf"></i>
                                <p>Buku Inventaris (Mutasi)</p>
                            </a>
                        </li>

                        <li class="nav-header">KEAMANAN & SISTEM</li>
                        <li class="nav-item">
                            <a href="{{ route('settings.backup') }}" class="nav-link">
                                <i class="nav-icon fas fa-cloud-download-alt"></i>
                                <p>Simpan Cadangan (Backup)</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('activity-logs.index') }}"
                                class="nav-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-history"></i>
                                <p>Riwayat Aktivitas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('settings.index') }}"
                                class="nav-link {{ request()->routeIs('settings.index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>Pengaturan Identitas</p>
                            </a>
                        </li>
                        @endhasanyrole
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header shadow-sm mb-3" style="background: #fff;">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-bold text-dark"><i class="fas fa-chevron-right text-primary mr-2 small"></i> @yield('header')</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Beranda</a></li>
                                <li class="breadcrumb-item active">@yield('title')</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; {{ date('Y') }} {{ \App\Models\Setting::get('school_name', 'SISARPA') }}.</strong>
            All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

    <script>
        $(function () {
            $('.select2').select2({
                theme: 'bootstrap4'
            })
        });

        function confirmDelete(id, formId) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data yang dihapus mungkin tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            })
        }
    </script>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
            })
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
            })
        </script>
    @endif

    @stack('js')
</body>

</html>