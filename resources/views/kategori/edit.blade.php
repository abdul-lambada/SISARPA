@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('header', 'Edit Kategori')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-warning">
                <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama_kategori">Nama Kategori</label>
                            <input type="text" name="nama_kategori"
                                class="form-control @error('nama_kategori') is-invalid @enderror" id="nama_kategori"
                                placeholder="Masukkan nama kategori"
                                value="{{ old('nama_kategori', $kategori->nama_kategori) }}">
                            @error('nama_kategori')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">Update</button>
                        <a href="{{ route('kategori.index') }}" class="btn btn-default">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection