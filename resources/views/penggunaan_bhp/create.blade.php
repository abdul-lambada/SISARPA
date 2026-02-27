@extends('layouts.app')

@section('title', 'Catat Penggunaan BHP')
@section('header', 'Distribusi Barang Habis Pakai')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <form action="{{ route('penggunaan-bhp.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="barang_id">Pilih Barang (Stok Tersedia)</label>
                            <select name="barang_id" id="barang_id"
                                class="form-control select2 @error('barang_id') is-invalid @enderror">
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barangs as $b)
                                    <option value="{{ $b->id }}">{{ $b->nama_barang }} (Sisa: {{ $b->stok }} {{ $b->satuan }})
                                    </option>
                                @endforeach
                            </select>
                            @error('barang_id') <span class="error invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="user_id">Penerima / Peminta</label>
                            <select name="user_id" id="user_id"
                                class="form-control select2 @error('user_id') is-invalid @enderror">
                                <option value="">-- Pilih User --</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                                @endforeach
                            </select>
                            @error('user_id') <span class="error invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jumlah">Jumlah Pengambilan</label>
                                    <input type="number" name="jumlah"
                                        class="form-control @error('jumlah') is-invalid @enderror" value="1" min="1">
                                    @error('jumlah') <span class="error invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal">Tanggal</label>
                                    <input type="date" name="tanggal"
                                        class="form-control @error('tanggal') is-invalid @enderror"
                                        value="{{ date('Y-m-d') }}">
                                    @error('tanggal') <span class="error invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="catatan">Catatan / Keperluan</label>
                            <textarea name="catatan" class="form-control" rows="3"
                                placeholder="Contoh: Untuk operasional ujian semester"></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> SIMPAN</button>
                        <a href="{{ route('penggunaan-bhp.index') }}" class="btn btn-default">BATAL</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection