@extends('layouts.app')

@section('title', 'Lapor Kerusakan')
@section('header', 'Buat Laporan Kerusakan Baru')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title text-bold">Pilih Barang & Detail Kerusakan</h3>
            </div>
            <form action="{{ route('laporan-kerusakan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Barang / Aset</label>
                        <select name="barang_id" id="barang_id" class="form-control select2" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach(\App\Models\Barang::all() as $b)
                                <option value="{{ $b->id }}" {{ ($barang && $barang->id == $b->id) ? 'selected' : '' }}>
                                    {{ $b->nama_barang }} ({{ $b->kode_barang }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi Kerusakan</label>
                        <textarea name="deskripsi_kerusakan" class="form-control" rows="4" placeholder="Jelaskan bagian mana yang rusak dan gejalanya..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Foto Kerusakan (Opsional)</label>
                        <input type="file" name="foto_kerusakan" class="form-control-file" accept="image/*">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-paper-plane"></i> Kirim Laporan
                    </button>
                    <a href="{{ route('laporan-kerusakan.index') }}" class="btn btn-default btn-block">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-dark card-outline">
            <div class="card-header">
                <h3 class="card-title text-bold"><i class="fas fa-qrcode"></i> Scan QR Barang</h3>
            </div>
            <div class="card-body text-center">
                <div id="reader" style="width: 100%;"></div>
                <div class="alert alert-info mt-3 p-2 small">
                    <i class="fas fa-info-circle"></i> Scan QR pada aset untuk memilih barang secara otomatis.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    $(function () {
        // Initialize Select2 if available
        if($('.select2').length > 0) {
            $('.select2').select2({ theme: 'bootstrap4' });
        }

        function onScanSuccess(decodedText, decodedResult) {
            // Find option by text (code)
            let found = false;
            $('#barang_id option').each(function() {
                if($(this).text().includes(decodedText)) {
                    $('#barang_id').val($(this).val()).trigger('change');
                    found = true;
                    return false;
                }
            });

            if(found) {
                Swal.fire({
                    icon: 'success',
                    title: 'Barang Terdeteksi',
                    text: 'Berhasil memilih barang dari QR Code.',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Tidak Dikenali',
                    text: 'Kode barang ' + decodedText + ' tidak ditemukan di sistem.',
                });
            }
        }

        const html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    });
</script>
@endpush
