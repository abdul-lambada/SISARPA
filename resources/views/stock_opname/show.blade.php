@extends('layouts.app')

@section('title', 'Laporan Stock Opname')
@section('header', 'Hasil Audit Stok: ' . $opname->ruangan)

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card no-print-card">
                <div class="card-header d-print-none">
                    <h3 class="card-title">Informasi Audit</h3>
                    <div class="card-tools">
                        <button onclick="window.print()" class="btn btn-primary btn-sm">
                            <i class="fas fa-print"></i> Cetak Laporan Resmi
                        </button>
                        <a href="{{ route('stock-opname.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                    </div>
                </div>

                <div class="card-body print-container">
                    <!-- KOP SURAT (Hanya tampil saat print) -->
                    <div class="print-header d-none d-print-block">
                        <div class="row items-center border-bottom border-dark pb-2 mb-4">
                            <div class="col-2 text-center">
                                <i class="fas fa-school fa-4x text-dark"></i>
                            </div>
                            <div class="col-10 text-center">
                                <h2 class="mb-0 font-weight-bold">PEMERINTAH KOTA ADMINISTRASI SEKOLAH</h2>
                                <h3 class="mb-0 font-weight-bold">SMK NEGERI CONTOH SISARPA</h3>
                                <p class="mb-0">Jl. Pendidikan No. 123, Telp: (021) 123456, Email: info@smkncontoh.sch.id
                                </p>
                                <p class="mb-0 font-italic">Website: www.smkncontoh.sch.id - INDONESIA</p>
                            </div>
                        </div>
                        <h4 class="text-center font-weight-bold mt-4 mb-2 text-uppercase underline">LAPORAN HASIL
                            INVENTARISASI BARANG (STOCK OPNAME)</h4>
                        <p class="text-center mb-4">NOMOR: {{ date('Y') }}/SO/SARPRAS/{{ $opname->id }}</p>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 col-sm-6">
                            <table class="table table-sm no-border-table">
                                <tr>
                                    <th width="150">Ruangan / Lokasi</th>
                                    <td>: {{ $opname->ruangan }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Audit</th>
                                    <td>: {{ \Carbon\Carbon::parse($opname->tanggal)->translatedFormat('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Pelaksana Audit</th>
                                    <td>: {{ $opname->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Status Dokumen</th>
                                    <td>: <span
                                            class="badge {{ $opname->status == 'selesai' ? 'badge-success' : 'badge-warning' }} d-print-none">{{ strtoupper($opname->status) }}</span>
                                        <span class="d-none d-print-inline text-bold text-success">ASLI (FINAL)</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered mt-2 custom-print-table">
                            <thead class="text-center">
                                <tr>
                                    <th width="40">No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang / Deskripsi</th>
                                    <th width="100">Satuan Sistem</th>
                                    <th width="100">Fisik Ditemukan</th>
                                    <th width="80">Selisih</th>
                                    <th>Keterangan / Analisis</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total_selisih = 0; @endphp
                                @foreach($opname->details as $index => $d)
                                    @php $total_selisih += $d->selisih; @endphp
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td><code>{{ $d->barang->kode_barang }}</code></td>
                                        <td>{{ $d->barang->nama_barang }}</td>
                                        <td class="text-center">{{ $d->jumlah_sistem }}</td>
                                        <td class="text-center">{{ $d->jumlah_fisik }}</td>
                                        <td
                                            class="text-center font-weight-bold {{ $d->selisih < 0 ? 'text-danger' : ($d->selisih > 0 ? 'text-success' : '') }}">
                                            {{ $d->selisih }}
                                        </td>
                                        <td>
                                            @if($d->selisih < 0)
                                                <span class="text-danger small font-weight-bold">SELISIH KURANG (HILANG)</span>
                                            @elseif($d->selisih > 0)
                                                <span class="text-success small font-weight-bold">SELISIH LEBIH</span>
                                            @else
                                                <span class="text-dark small">TIDAK ADA SELISIH (SESUAI)</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-light font-weight-bold">
                                    <th colspan="5" class="text-right">TOTAL AKURASI (%) / TOTAL SELISIH:</th>
                                    <th class="text-center {{ $total_selisih < 0 ? 'text-danger' : 'text-success' }}">
                                        {{ $total_selisih }}</th>
                                    <th class="text-center">
                                        @if($total_selisih == 0)
                                            <span class="badge badge-success">STOK AKURAT 100%</span>
                                        @endif
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- TANDA TANGAN (Hanya tampil saat print) -->
                    <div class="mt-5 d-none d-print-block">
                        <div class="row">
                            <div class="col-6 text-center">
                                <p class="mb-5">Mengetahui,<br>Kepala Sekolah SMKN Contoh</p>
                                <div class="mt-5 pt-3">
                                    <p class="mb-0 font-weight-bold underline">__________________________</p>
                                    <p>NIP. 12345678 1234 1 001</p>
                                </div>
                            </div>
                            <div class="col-6 text-center">
                                <p class="mb-5">Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>Petugas
                                    Sarana Prasarana</p>
                                <div class="mt-5 pt-3">
                                    <p class="mb-0 font-weight-bold underline">{{ $opname->user->name }}</p>
                                    <p>Username: {{ $opname->user->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .no-border-table td,
        .no-border-table th {
            border: none !important;
            padding: 4px 8px;
        }

        .underline {
            text-decoration: underline;
        }

        @media print {
            body {
                background-color: #fff !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }

            .main-footer,
            .card-tools,
            .btn,
            .main-header,
            .main-sidebar,
            .breadcrumb,
            .nav-item {
                display: none !important;
            }

            .content-wrapper {
                margin-left: 0 !important;
                padding-top: 0 !important;
                background: none !important;
            }

            .content-header {
                display: none !important;
            }

            .no-print-card {
                border: none !important;
                box-shadow: none !important;
            }

            .card-body {
                padding: 0 !important;
            }

            .custom-print-table thead th {
                background-color: #f4f6f9 !important;
                color: #000 !important;
                border: 1px solid #000 !important;
            }

            .custom-print-table td,
            .custom-print-table th {
                border: 1px solid #000 !important;
            }

            .print-container {
                width: 100%;
            }

            .text-danger {
                color: #dc3545 !important;
            }

            .text-success {
                color: #28a745 !important;
            }
        }
    </style>
@endpush