@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard Utama')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $total_barang }}</h3>
                    <p>Total Barang</p>
                </div>
                <div class="icon">
                    <i class="fas fa-box"></i>
                </div>
                <a href="{{ route('barang.index') }}" class="small-box-footer">Selengkapnya <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $barang_rusak }}</h3>
                    <p>Barang Rusak</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <a href="{{ route('barang.index') }}" class="small-box-footer">Cek Segera <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $sedang_dipinjam }}</h3>
                    <p>Sedang Dipinjam</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hand-holding"></i>
                </div>
                <a href="{{ route('peminjaman.index') }}" class="small-box-footer">Lihat Daftar <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stok_menipis }}</h3>
                    <p>Stok Menipis (< 5)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <a href="{{ route('barang.index') }}" class="small-box-footer">Restock Barang <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    @hasanyrole('Super Admin|Petugas Sarpras')
    <div class="row">
        <!-- Reservasi Menunggu Persetujuan -->
        <div class="col-md-6">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title text-bold text-primary"><i class="fas fa-calendar-check mr-1"></i> Reservasi Menunggu Persetujuan</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Peminjam</th>
                                <th>Ruangan</th>
                                <th>Jadwal</th>
                                <th width="50px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservasi_pending as $res)
                            <tr>
                                <td>{{ $res->user->name }}</td>
                                <td>{{ $res->ruangan->nama_ruangan }}</td>
                                <td class="small">{{ $res->tanggal }}<br>({{ substr($res->jam_mulai,0,5) }} - {{ substr($res->jam_selesai,0,5) }})</td>
                                <td><a href="{{ route('reservasi.index') }}" class="btn btn-xs btn-primary">Detail</a></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-3 text-muted">Tidak ada permintaan pending.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Peminjaman Jatuh Tempo (> 3 Hari) -->
        <div class="col-md-6">
            <div class="card card-outline card-danger">
                <div class="card-header">
                    <h3 class="card-title text-bold text-danger"><i class="fas fa-clock mr-1"></i> Pinjaman Belum Kembali (> 3 Hari)</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Barang</th>
                                <th>Peminjam</th>
                                <th>Tgl Pinjam</th>
                                <th width="50px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jatuh_tempo as $jt)
                            <tr class="table-danger">
                                <td>{{ $jt->barang->nama_barang }}</td>
                                <td>{{ $jt->user->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($jt->tanggal_pinjam)->diffForHumans() }}</td>
                                <td><a href="{{ route('peminjaman.index') }}" class="btn btn-xs btn-danger">Nagih</a></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-3 text-muted">Semua pinjaman masih dalam batas waktu.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endhasanyrole

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title text-bold">Peminjaman Terbaru</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Peminjam</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($peminjaman_terbaru as $p)
                                    <tr>
                                        <td>{{ $p->barang->nama_barang }}</td>
                                        <td>{{ $p->user->name }}</td>
                                        <td>{{ $p->tanggal_pinjam }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $p->status == 'dipinjam' ? 'badge-warning' : 'badge-success' }}">
                                                {{ strtoupper($p->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada transaksi peminjaman.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <a href="{{ route('peminjaman.create') }}" class="btn btn-sm btn-info float-left">Buat Peminjaman
                        Baru</a>
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-secondary float-right">Lihat Semua
                        Riwayat</a>
                </div>
            </div>
        </div>
    </div>
@endsection