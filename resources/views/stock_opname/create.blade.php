@extends('layouts.app')

@section('title', 'Mulai Opname')
@section('header', 'Mulai Sesi Audit Stok')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <form action="{{ route('stock-opname.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="tanggal">Tanggal Opname</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="ruangan">Pilih Ruangan / Lokasi</label>
                            <select name="ruangan" id="ruangan" class="form-control select2" required>
                                <option value="">-- Pilih Lokasi --</option>
                                @foreach($ruangans as $r)
                                    <option value="{{ $r }}">{{ $r }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Sistem akan menarik data seluruh barang yang terdaftar di lokasi
                                ini.</small>
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan / Catatan</label>
                            <textarea name="keterangan" class="form-control" rows="3"
                                placeholder="Contoh: Audit semester ganjil 2024"></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-play"></i> Mulai Scan Barang</button>
                        <a href="{{ route('stock-opname.index') }}" class="btn btn-default">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection