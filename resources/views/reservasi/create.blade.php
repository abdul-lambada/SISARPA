@extends('layouts.app')

@section('title', 'Booking Ruangan')
@section('header', 'Form Reservasi Fasilitas Sekolah')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <form action="{{ route('reservasi.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="ruangan_id">Pilih Ruangan</label>
                            <select name="ruangan_id" id="ruangan_id" class="form-control select2" required>
                                <option value="">-- Pilih Fasilitas --</option>
                                @foreach($ruangans as $r)
                                    <option value="{{ $r->id }}">{{ $r->nama_ruangan }} (Kapasitas: {{ $r->kapasitas }} Orang)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tanggal">Tanggal Penggunaan</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}"
                                min="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jam_mulai">Jam Mulai</label>
                                    <input type="time" name="jam_mulai" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jam_selesai">Jam Selesai</label>
                                    <input type="time" name="jam_selesai" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="keperluan">Keperluan / Agenda Kegiatan</label>
                            <textarea name="keperluan" class="form-control" rows="3"
                                placeholder="Contoh: Rapat Koordinasi Kurikulum / Praktikum TIK Kelas X"
                                required></textarea>
                            <small class="text-muted">Jelaskan kegiatan apa yang akan dilakukan di ruangan tersebut.</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-check-circle"></i> AJUKAN
                            RESERVASI</button>
                        <a href="{{ route('reservasi.index') }}" class="btn btn-default">Kembali</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Ketentuan Reservasi</h3>
                </div>
                <div class="card-body">
                    <ul>
                        <li>Reservasi harus dilakukan minimal 1 hari sebelumnya.</li>
                        <li>Sistem akan otomatis menolak jika jam yang dipilih bentrok dengan reservasi yang sudah
                            disetujui.</li>
                        <li>Pastikan meninggalkan ruangan dalam keadaan bersih dan rapi.</li>
                        <li>Matikan AC dan Lampu setelah selesai digunakan.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection