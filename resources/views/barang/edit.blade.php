@extends('layouts.app')

@section('title', 'Edit Barang')
@section('header', 'Edit Nama Barang')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-warning">
                <form action="{{ route('barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipe">Jenis Inventaris</label>
                                    <select name="tipe" id="tipe" class="form-control @error('tipe') is-invalid @enderror">
                                        <option value="aset" {{ $barang->tipe == 'aset' ? 'selected' : '' }}>Aset Tetap (Dipinjam)</option>
                                        <option value="bhp" {{ $barang->tipe == 'bhp' ? 'selected' : '' }}>Barang Habis Pakai (BHP)</option>
                                    </select>
                                    @error('tipe')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kategori_id">Kategori</label>
                                    <select name="kategori_id" id="kategori_id"
                                        class="form-control select2 @error('kategori_id') is-invalid @enderror">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}" {{ $barang->kategori_id == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                    @error('kategori_id')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="kode_barang">Kode Barang</label>
                                    <input type="text" name="kode_barang"
                                        class="form-control @error('kode_barang') is-invalid @enderror" id="kode_barang"
                                        placeholder="Contoh: BRG-001" value="{{ $barang->kode_barang }}">
                                    @error('kode_barang')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="nama_barang">Nama Barang</label>
                                    <input type="text" name="nama_barang"
                                        class="form-control @error('nama_barang') is-invalid @enderror" id="nama_barang"
                                        placeholder="Contoh: Laptop Dell / Kertas A4" value="{{ $barang->nama_barang }}">
                                    @error('nama_barang')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="satuan">Satuan</label>
                                    <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror"
                                        id="satuan" placeholder="Pcs / Rim / Box" value="{{ $barang->satuan }}">
                                    @error('satuan')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="merk">Merk</label>
                                    <input type="text" name="merk" class="form-control @error('merk') is-invalid @enderror"
                                        id="merk" placeholder="Contoh: Dell / PaperOne" value="{{ $barang->merk }}">
                                    @error('merk')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="lokasi">Lokasi/Ruangan</label>
                                    <input type="text" name="lokasi"
                                        class="form-control @error('lokasi') is-invalid @enderror" id="lokasi"
                                        placeholder="Contoh: Lab Komputer 1 / Gudang" value="{{ $barang->lokasi }}">
                                    @error('lokasi')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="spesifikasi">Spesifikasi / Keterangan</label>
                            <textarea name="spesifikasi" class="form-control @error('spesifikasi') is-invalid @enderror"
                                id="spesifikasi" rows="2"
                                placeholder="Masukkan spesifikasi barang">{{ $barang->spesifikasi }}</textarea>
                            @error('spesifikasi')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="kondisi">Kondisi</label>
                                    <select name="kondisi" id="kondisi"
                                        class="form-control @error('kondisi') is-invalid @enderror">
                                        <option value="baik" {{ $barang->kondisi == 'baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="rusak" {{ $barang->kondisi == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                        <option value="servis" {{ $barang->kondisi == 'servis' ? 'selected' : '' }}>Servis</option>
                                    </select>
                                    @error('kondisi')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" id="maintenance_field">
                                    <label for="tgl_servis_berikutnya">Jadwal Servis</label>
                                    <input type="date" name="tgl_servis_berikutnya" class="form-control @error('tgl_servis_berikutnya') is-invalid @enderror" value="{{ $barang->tgl_servis_berikutnya }}">
                                    @error('tgl_servis_berikutnya')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="stok">Stok Saat Ini</label>
                                    <input type="number" name="stok"
                                        class="form-control @error('stok') is-invalid @enderror" id="stok"
                                        placeholder="Contoh: 10" value="{{ $barang->stok }}">
                                    @error('stok')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="min_stok">Minimal Stok</label>
                                    <input type="number" name="min_stok"
                                        class="form-control @error('min_stok') is-invalid @enderror" id="min_stok"
                                        placeholder="Alert jika stok < x" value="{{ $barang->min_stok }}">
                                    @error('min_stok')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="foto_barang">Ganti Foto Barang (Opsional)</label>
                            @if($barang->foto_barang)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $barang->foto_barang) }}" width="100" class="img-thumbnail">
                                </div>
                            @endif
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="foto_barang"
                                        class="custom-file-input @error('foto_barang') is-invalid @enderror"
                                        id="foto_barang">
                                    <label class="custom-file-label" for="foto_barang">Pilih file</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning text-bold"><i class="fas fa-save"></i> UPDATE DATA</button>
                        <a href="{{ route('barang.index') }}" class="btn btn-default">BATAL</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            function toggleMaintenance() {
                if($('#tipe').val() == 'bhp') {
                    $('#maintenance_field').hide();
                } else {
                    $('#maintenance_field').show();
                }
            }
            $('#tipe').on('change', toggleMaintenance);
            toggleMaintenance();
        });
    </script>
@endpush