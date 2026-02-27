@extends('layouts.app')

@section('title', 'Tambah Kategori')
@section('header', 'Tambah Kategori')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <form action="{{ route('kategori.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama_kategori">Nama Kategori</label>
                            <input type="text" name="nama_kategori"
                                class="form-control @error('nama_kategori') is-invalid @enderror" id="nama_kategori"
                                placeholder="Masukkan nama kategori" value="{{ old('nama_kategori') }}">
                            @error('nama_kategori')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('kategori.index') }}" class="btn btn-default">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection