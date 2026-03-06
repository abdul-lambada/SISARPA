@extends('layouts.app')

@section('title', 'Reservasi Ruangan')
@section('header', 'Jadwal & Reservasi Fasilitas')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('reservasi.create') }}" class="btn btn-primary float-right">
                        <i class="fas fa-calendar-plus"></i> Buat Reservasi Baru
                    </a>
                </div>
                <div class="card-body">
                    <table id="reservasi-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="30px">No</th>
                                <th>Peminjam</th>
                                <th>Ruangan</th>
                                <th>Jadwal (Waktu)</th>
                                <th>Keperluan</th>
                                <th>Status</th>
                                <th width="80px">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(function () {
            $('#reservasi-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('reservasi.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama_user', name: 'user.name' },
                    { data: 'nama_ruangan', name: 'ruangan.nama_ruangan' },
                    { data: 'waktu', name: 'tanggal' },
                    { data: 'keperluan', name: 'keperluan' },
                    { data: 'status_badge', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        });

        function updateStatus(id, status) {
            Swal.fire({
                title: 'Update Status Reservasi?',
                text: "Anda akan merubah status reservasi ini menjadi " + status.toUpperCase(),
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Update',
                input: status === 'ditolak' ? 'text' : null,
                inputPlaceholder: 'Masukan alasan ditolak...',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('reservasi/update-status') }}/" + id,
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            status: status,
                            catatan: result.value || ""
                        },
                        success: function (response) {
                            if (response.success) {
                                $('#reservasi-table').DataTable().ajax.reload();
                                Swal.fire('Berhasil', response.message, 'success');
                            }
                        }
                    });
                }
            });
        }
    </script>
@endpush