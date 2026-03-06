@extends('layouts.app')

@section('title', 'Bukti Peminjaman')
@section('header', 'Bukti Serah Terima Barang')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-lg no-print-card">
                <div class="card-header d-print-none">
                    <h3 class="card-title">Detail Peminjaman #{{ $peminjaman->id }}</h3>
                    <div class="card-tools">
                        <button onclick="window.print()" class="btn btn-primary btn-sm">
                            <i class="fas fa-print"></i> Cetak Bukti Pinjam
                        </button>
                        <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                    </div>
                </div>

                <div class="card-body p-5">
                    <!-- KOP SURAT SEKOLAH -->
                    <div class="print-header d-none d-print-block">
                        <div class="row border-bottom border-dark pb-3 mb-4">
                            <div class="col-2 text-center">
                                <i class="fas fa-school fa-4x text-dark"></i>
                            </div>
                            <div class="col-10 text-center">
                                <h3 class="mb-0 font-weight-bold">SMK NEGERI CONTOH SISARPA</h3>
                                <p class="mb-0">Bidang Sarana dan Prasarana Inventaris Sekolah</p>
                                <p class="mb-0 small">Jl. Pendidikan No. 123, Telp: (021) 123456</p>
                            </div>
                        </div>
                    </div>

                    <h4 class="text-center font-weight-bold text-uppercase underline mb-4">SURAT BUKTI PEMINJAMAN BARANG</h4>
                    
                    <div class="mb-4">
                        <p>Telah diserahkan barang inventaris sekolah kepada pihak pertama (peminjam) dengan rincian sebagai berikut:</p>
                        
                        <table class="table table-sm no-border-table">
                            <tr>
                                <th width="200">Nama Peminjam</th>
                                <td>: {{ $peminjaman->user->name }} ({{ $peminjaman->user->username ?? '-' }})</td>
                            </tr>
                            <tr>
                                <th>Jabatan/Kelas</th>
                                <td>: {{ strtoupper($peminjaman->user->jenis_user ?? 'User') }} {{ $peminjaman->user->kelas ? '- ' . $peminjaman->user->kelas : '' }}</td>
                            </tr>
                            <tr>
                                <th>Barang yang Dipinjam</th>
                                <td>: <strong>{{ $peminjaman->barang->nama_barang }}</strong></td>
                            </tr>
                            <tr>
                                <th>Kode Barang</th>
                                <td>: <code>{{ $peminjaman->barang->kode_barang }}</code></td>
                            </tr>
                            <tr>
                                <th>Tanggal Peminjaman</th>
                                <td>: {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('l, d F Y') }}</td>
                            </tr>
                            <tr>
                                <th>Status Saat Ini</th>
                                <td>: <span class="badge {{ $peminjaman->status == 'dipinjam' ? 'badge-warning' : 'badge-success' }}">{{ strtoupper($peminjaman->status) }}</span></td>
                            </tr>
                            <tr>
                                <th>Catatan Keperluan</th>
                                <td>: {{ $peminjaman->catatan ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>

                    <p class="mt-4">Peminjam bertanggung jawab penuh atas keamanan dan keutuhan barang tersebut. Jika terjadi kehilangan atau kerusakan akibat kelalaian, peminjam bersedia mengganti sesuai ketentuan sekolah.</p>

                    <!-- SIGNATURE SECTION -->
                    <div class="row mt-5 pt-3">
                        <div class="col-6 text-center">
                            <p class="mb-5">Petugas Inventaris,</p>
                            <div class="mt-5">
                                <p class="mb-0 font-weight-bold underline">__________________________</p>
                                <p class="small text-muted">(Cap & Tanda Tangan)</p>
                            </div>
                        </div>
                        <div class="col-6 text-center">
                            <p class="mb-1">Peminjam,</p>
                            <div class="signature-display mb-2">
                                @if($peminjaman->tanda_tangan)
                                    <img src="{{ $peminjaman->tanda_tangan }}" alt="Tanda Tangan Digital" style="max-width: 200px; border-bottom: 2px solid #000;">
                                @else
                                    <div class="py-4 text-muted font-italic">Tanda tangan tidak tersedia</div>
                                @endif
                            </div>
                            <p class="mb-0 font-weight-bold">{{ $peminjaman->user->name }}</p>
                            <p class="small">NISN/NIP: {{ $peminjaman->user->username ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .no-border-table td, .no-border-table th {
            border: none !important;
            padding: 8px 0;
        }
        .underline { text-decoration: underline; }
        
        @media print {
            .main-footer, .card-tools, .btn, .main-header, .main-sidebar, .content-header {
                display: none !important;
            }
            .content-wrapper {
                margin-left: 0 !important;
                background: white !important;
            }
            .no-print-card {
                border: none !important;
                box-shadow: none !important;
            }
            body { font-size: 14pt; }
            .badge { border: 1px solid #000; color: #000 !important; }
        }
    </style>
@endpush
