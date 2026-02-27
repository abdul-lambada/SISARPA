@extends('layouts.app')

@section('title', 'Tambah Barang')
@section('header', 'Tambah Barang Baru')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kategori_id">Kategori</label>
                                    <select name="kategori_id" id="kategori_id"
                                        class="form-control select2 @error('kategori_id') is-invalid @enderror">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                    @error('kategori_id')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode_barang">Kode Barang</label>
                                    <input type="text" name="kode_barang"
                                        class="form-control @error('kode_barang') is-invalid @enderror" id="kode_barang"
                                        placeholder="Contoh: BRG-001" value="{{ old('kode_barang') }}">
                                    @error('kode_barang')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="nama_barang">Nama Barang</label>
                            <input type="text" name="nama_barang"
                                class="form-control @error('nama_barang') is-invalid @enderror" id="nama_barang"
                                placeholder="Contoh: Laptop Dell Latitude" value="{{ old('nama_barang') }}">
                            @error('nama_barang')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="merk">Merk</label>
                                    <input type="text" name="merk" class="form-control @error('merk') is-invalid @enderror"
                                        id="merk" placeholder="Contoh: Dell" value="{{ old('merk') }}">
                                    @error('merk')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lokasi">Lokasi/Ruangan</label>
                                    <input type="text" name="lokasi"
                                        class="form-control @error('lokasi') is-invalid @enderror" id="lokasi"
                                        placeholder="Contoh: Lab Komputer 1" value="{{ old('lokasi') }}">
                                    @error('lokasi')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="spesifikasi">Spesifikasi</label>
                            <textarea name="spesifikasi" class="form-control @error('spesifikasi') is-invalid @enderror"
                                id="spesifikasi" rows="3"
                                placeholder="Masukkan spesifikasi barang">{{ old('spesifikasi') }}</textarea>
                            @error('spesifikasi')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kondisi">Kondisi</label>
                                    <select name="kondisi" id="kondisi"
                                        class="form-control @error('kondisi') is-invalid @enderror">
                                        <option value="baik" {{ old('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="rusak" {{ old('kondisi') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                        <option value="servis" {{ old('kondisi') == 'servis' ? 'selected' : '' }}>Servis
                                        </option>
                                    </select>
                                    @error('kondisi')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stok">Stok</label>
                                    <input type="number" name="stok"
                                        class="form-control @error('stok') is-invalid @enderror" id="stok"
                                        placeholder="Contoh: 10" value="{{ old('stok', 0) }}">
                                    @error('stok')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="foto_barang">Foto Barang</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="foto_barang"
                                        class="custom-file-input @error('foto_barang') is-invalid @enderror"
                                        id="foto_barang">
                                    <label class="custom-file-label" for="foto_barang">Pilih file</label>
                                </div>
                            </div>
                            @error('foto_barang')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary text-bold"><i class="fas fa-save"></i> SIMPAN
                            DATA</button>
                        <a href="{{ route('barang.index') }}" class="btn btn-default">KEMBALI</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection