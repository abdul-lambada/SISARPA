@extends('layouts.app')

@section('title', 'Tambah Ruangan')
@section('header', 'Tambah Ruangan Baru')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <form action="{{ route('ruangan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode_ruangan">Kode Ruangan</label>
                                    <input type="text" name="kode_ruangan" class="form-control" placeholder="LAB-01"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_ruangan">Nama Ruangan</label>
                                    <input type="text" name="nama_ruangan" class="form-control" placeholder="Lab Komputer 1"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kapasitas">Kapasitas (Orang)</label>
                                    <input type="number" name="kapasitas" class="form-control" placeholder="40" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="tersedia">Tersedia</option>
                                        <option value="perbaikan">Perbaikan</option>
                                        <option value="tidak_tersedia">Tidak Tersedia</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fasilitas">Fasilitas (Deskripsi)</label>
                            <textarea name="fasilitas" class="form-control" rows="3"
                                placeholder="AC, Proyektor, 40 PC..."></textarea>
                        </div>

                        <div class="form-group">
                            <label for="foto_ruangan">Foto Ruangan</label>
                            <input type="file" name="foto_ruangan" class="form-control-file">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> SIMPAN</button>
                        <a href="{{ route('ruangan.index') }}" class="btn btn-default">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection