@extends('layouts.app')

@section('title', 'Pengaturan Sistem')
@section('header', 'Identitas Sekolah & Aplikasi')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title text-bold"><i class="fas fa-university mr-1"></i> Data Identitas Sekolah</h3>
            </div>
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pemerintah Kota/Yayasan</label>
                                <input type="text" name="school_city" class="form-control" value="{{ $settings['school_city'] ?? '' }}" placeholder="PEMERINTAH KOTA ADMINISTRASI SEKOLAH">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nama Sekolah</label>
                                <input type="text" name="school_name" class="form-control" value="{{ $settings['school_name'] ?? '' }}" placeholder="SMK NEGERI CONTOH SISARPA">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>NPSN Sekolah</label>
                                <input type="text" name="school_npsn" class="form-control" value="{{ $settings['school_npsn'] ?? '' }}" placeholder="12345678">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Alamat Sekolah</label>
                        <textarea name="school_address" class="form-control" rows="2">{{ $settings['school_address'] ?? '' }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Kecamatan</label>
                                <input type="text" name="school_district" class="form-control" value="{{ $settings['school_district'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Kabupaten/Kota</label>
                                <input type="text" name="school_regency" class="form-control" value="{{ $settings['school_regency'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Provinsi</label>
                                <input type="text" name="school_province" class="form-control" value="{{ $settings['school_province'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Kode Pos</label>
                                <input type="text" name="school_zip" class="form-control" value="{{ $settings['school_zip'] ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Telepon</label>
                                <input type="text" name="school_phone" class="form-control" value="{{ $settings['school_phone'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Website</label>
                                <input type="text" name="school_website" class="form-control" value="{{ $settings['school_website'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Email Sekolah</label>
                                <input type="email" name="school_email" class="form-control" value="{{ $settings['school_email'] ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Kepala Sekolah</label>
                                <input type="text" name="school_principal_name" class="form-control" value="{{ $settings['school_principal_name'] ?? '' }}" placeholder="Dr. Contoh Nama, M.Pd">
                                <small class="text-muted">Digunakan untuk tanda tangan laporan.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>NIP Kepala Sekolah</label>
                                <input type="text" name="school_principal_nip" class="form-control" value="{{ $settings['school_principal_nip'] ?? '' }}" placeholder="19800101 200501 1 001">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="form-group">
                        <label>Logo Sekolah</label>
                        @php $logo = $settings['school_logo'] ?? 'default_logo.png'; @endphp
                        <div class="mb-2">
                            @if($logo == 'default_logo.png')
                                <div class="p-3 bg-light border text-center" style="width: 100px;">No Logo</div>
                            @else
                                <img src="{{ asset('storage/settings/' . $logo) }}" width="100" class="img-thumbnail">
                            @endif
                        </div>
                        <div class="custom-file">
                            <input type="file" name="school_logo" class="custom-file-input" id="school_logo">
                            <label class="custom-file-label" for="school_logo">Ganti Logo...</label>
                        </div>
                        <small class="text-muted">Gunakan file PNG transparan untuk hasil terbaik di KOP surat.</small>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary text-bold">
                        <i class="fas fa-save mr-1"></i> SIMPAN PERUBAHAN
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title text-bold"><i class="fas fa-info-circle mr-1"></i> Petunjuk</h3>
            </div>
            <div class="card-body">
                <p>Data yang Anda masukkan di sini akan digunakan secara otomatis pada:</p>
                <ul>
                    <li><strong>KOP Surat</strong> Berita Acara (BAST).</li>
                    <li><strong>Label QR Code</strong> barang.</li>
                    <li><strong>Laporan Mutasi</strong> tahunan.</li>
                    <li>Halaman Login & Dashboard.</li>
                </ul>
                <div class="alert alert-warning small">
                    <i class="fas fa-exclamation-triangle"></i> Perubahan data identitas akan langsung berpengaruh pada dokumen PDF yang dicetak.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
