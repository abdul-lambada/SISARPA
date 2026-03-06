@extends('layouts.app')

@section('title', 'Scan Barcode')
@section('header', 'Proses Audit Stok: ' . $opname->ruangan)

@section('content')
    <div class="row">
        <div class="col-md-5">
            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-qrcode"></i> QR Scanner</h3>
                </div>
                <div class="card-body">
                    <div id="reader" style="width: 100%;"></div>
                    <div class="mt-3">
                        <div class="form-group">
                            <label>Jumlah Input (Khusus BHP)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                </div>
                                <input type="number" id="manual-qty" class="form-control" value="1" min="1">
                            </div>
                            <small class="text-muted">Biarkan "1" untuk scan aset satu per satu.</small>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Scan QR Code pada barang untuk memverifikasi keberadaan fisik.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-bold">Daftar Barang di Ruangan Ini</h3>
                    <div class="card-tools">
                        <form action="{{ route('stock-opname.finalize', $opname->id) }}" method="POST" id="finalize-form">
                            @csrf
                            <button type="button" onclick="confirmFinalize()" class="btn btn-success btn-sm">
                                <i class="fas fa-check-double"></i> Finalisasi & Selesai
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-valign-middle" id="item-list">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th class="text-center">Sistem</th>
                                <th class="text-center">Fisik</th>
                                <th class="text-center">Selisih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($opname->details as $d)
                                <tr id="row-{{ $d->barang->kode_barang }}">
                                    <td>{{ $d->barang->kode_barang }}</td>
                                    <td>{{ $d->barang->nama_barang }}</td>
                                    <td class="text-center">{{ $d->jumlah_sistem }}</td>
                                    <td class="text-center font-weight-bold fisik-val">{{ $d->jumlah_fisik }}</td>
                                    <td class="text-center selisih-val">
                                        <span class="badge {{ $d->selisih < 0 ? 'badge-danger' : 'badge-success' }}">
                                            {{ $d->selisih }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // Hentikan scanner sementara untuk memproses request
            // html5QrcodeScanner.clear(); 

            $.ajax({
                url: "{{ route('stock-opname.update-scan', $opname->id) }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_barang: decodedText,
                    jumlah: $('#manual-qty').val()
                },
                success: function (response) {
                    if (response.success) {
                        const row = $('#row-' + decodedText);
                        row.find('.fisik-val').text(response.data.fisik);

                        let badgeClass = response.data.selisih < 0 ? 'badge-danger' : 'badge-success';
                        row.find('.selisih-val').html('<span class="badge ' + badgeClass + '">' + response.data.selisih + '</span>');

                        row.addClass('table-success');
                        setTimeout(() => row.removeClass('table-success'), 2000);

                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                    } else {
                        Swal.fire('Gagal', response.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Gagal memproses QR Code', 'error');
                }
            });
        }

        const html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        function confirmFinalize() {
            Swal.fire({
                title: 'Finalisasi Audit?',
                text: "Setelah difinalisasi, Anda tidak dapat menambah atau mengubah hasil scan ini.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Selesai!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#finalize-form').submit();
                }
            });
        }
    </script>
@endpush