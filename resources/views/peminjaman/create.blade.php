@extends('layouts.app')

@section('title', 'Pinjam Barang')
@section('header', 'Form Peminjaman Barang')

@section('content')
    <div class="row">
        <div class="col-md-7">
            <div class="card card-primary">
                <form action="{{ route('peminjaman.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="barang_id">Pilih Barang</label>
                            <select name="barang_id" id="barang_id"
                                class="form-control select2 @error('barang_id') is-invalid @enderror">
                                <option value="">Pilih Barang (Hanya yang tersedia)</option>
                                @foreach($barangs as $barang)
                                    <option value="{{ $barang->id }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                        {{ $barang->kode_barang }} - {{ $barang->nama_barang }} (Stok: {{ $barang->stok }})
                                    </option>
                                @endforeach
                            </select>
                            @error('barang_id')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="user_id">Peminjam</label>
                            <select name="user_id" id="user_id"
                                class="form-control select2 @error('user_id') is-invalid @enderror" {{ auth()->user()->hasRole('User') ? 'disabled' : '' }}>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ (old('user_id') == $user->id || auth()->id() == $user->id) ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @if(auth()->user()->hasRole('User'))
                                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            @endif
                            @error('user_id')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="tanggal_pinjam">Tanggal Pinjam</label>
                            <input type="date" name="tanggal_pinjam"
                                class="form-control @error('tanggal_pinjam') is-invalid @enderror" id="tanggal_pinjam"
                                value="{{ old('tanggal_pinjam', date('Y-m-d')) }}">
                            @error('tanggal_pinjam')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="catatan">Catatan</label>
                            <textarea name="catatan" class="form-control" id="catatan" rows="3"
                                placeholder="Contoh: Digunakan untuk presentasi di aula">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-block text-bold">PROSES PEMINJAMAN</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-qrcode"></i> Scan QR Code Barang</h3>
                </div>
                <div class="card-body">
                    <div id="reader" style="width: 100%; min-height: 300px; border: 1px solid #ddd;"></div>
                    <div id="result" class="mt-2 text-center text-success font-weight-bold"></div>
                </div>
                <div class="card-footer text-center">
                    <small class="text-muted">Arahkan kamera ke QR Code pada barang untuk otomatis memilih barang.</small>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // decodedText is the barcode/qrcode content (kode_barang)
            $("#result").text("Scan Berhasil: " + decodedText);

            // Find option with that text and select it
            let found = false;
            $('#barang_id option').each(function () {
                if ($(this).text().indexOf(decodedText) !== -1) {
                    $('#barang_id').val($(this).val()).trigger('change');
                    found = true;
                    return false; // break
                }
            });

            if (!found) {
                Swal.fire({
                    icon: 'error',
                    title: 'Tidak ditemukan',
                    text: 'Barang dengan kode ' + decodedText + ' tidak ada atau stok sedang habis.',
                });
            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'Terdeteksi',
                    text: 'Barang berhasil dipilih otomatis.',
                    timer: 1500
                });
            }
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    </script>
@endpush