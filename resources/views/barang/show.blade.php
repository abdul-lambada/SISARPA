@extends('layouts.app')

@section('title', 'Detail Barang')
@section('header', 'Detail Barang: ' . $barang->nama_barang)

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center mb-3">
                        @if($barang->foto_barang)
                            <img class="img-fluid rounded" src="{{ asset('storage/' . $barang->foto_barang) }}"
                                alt="{{ $barang->nama_barang }}" style="max-height: 300px;">
                        @else
                            <img class="img-fluid rounded" src="{{ asset('adminlte/dist/img/boxed-bg.jpg') }}" alt="No Photo"
                                style="max-height: 200px;">
                        @endif
                    </div>
                    <h3 class="profile-username text-center font-weight-bold">{{ $barang->nama_barang }}</h3>
                    <p class="text-muted text-center">{{ $barang->kode_barang }}</p>

                    <div class="text-center mt-4">
                        <p class="mb-2 text-bold">Scan QR Code untuk Peminjaman</p>
                        {!! $qrcode !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#info" data-toggle="tab">Informasi Umum</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#spec" data-toggle="tab">Spesifikasi</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="info">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="200px">Kategori</th>
                                    <td>{{ $barang->kategori->nama_kategori }}</td>
                                </tr>
                                <tr>
                                    <th>Merk</th>
                                    <td>{{ $barang->merk ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Lokasi/Ruangan</th>
                                    <td>{{ $barang->lokasi }}</td>
                                </tr>
                                <tr>
                                    <th>Kondisi</th>
                                    <td>
                                        @php
                                            $badge = 'badge-success';
                                            if ($barang->kondisi == 'rusak')
                                                $badge = 'badge-danger';
                                            if ($barang->kondisi == 'servis')
                                                $badge = 'badge-warning';
                                        @endphp
                                        <span class="badge {{ $badge }}">{{ strtoupper($barang->kondisi) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Stok Tersedia</th>
                                    <td>{{ $barang->stok }}</td>
                                </tr>
                                <tr>
                                    <th>Ditambahkan Pada</th>
                                    <td>{{ $barang->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="tab-pane" id="spec">
                            <div class="p-3 bg-light rounded">
                                {!! nl2br(e($barang->spesifikasi)) ?? 'Tidak ada spesifikasi tambahan.' !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-warning"><i class="fas fa-edit"></i>
                        Edit</a>
                    <a href="{{ route('barang.index') }}" class="btn btn-default">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection