@extends('layouts.app')

@section('title', 'Peminjaman')
@section('header', 'Riwayat Peminjaman Barang')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('peminjaman.create') }}" class="btn btn-primary float-right">
                        <i class="fas fa-plus"></i> Pinjam Barang
                    </a>
                </div>
                <div class="card-body">
                    <table id="peminjaman-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="30px">No</th>
                                <th>Nama Barang</th>
                                <th>Peminjam</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali</th>
                                <th>Status</th>
                                <th width="150px">Aksi</th>
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
            $('#peminjaman-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('peminjaman.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'nama_barang', name: 'nama_barang' },
                    { data: 'nama_peminjam', name: 'nama_peminjam' },
                    { data: 'tanggal_pinjam', name: 'tanggal_pinjam' },
                    {
                        data: 'tanggal_kembali', name: 'tanggal_kembali', render: function (data) {
                            return data ? data : '-';
                        }
                    },
                    {
                        data: 'status', name: 'status', render: function (data) {
                            let badge = data == 'dipinjam' ? 'badge-warning' : 'badge-success';
                            return '<span class="badge ' + badge + '">' + data.toUpperCase() + '</span>';
                        }
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        });

        function kembalikanBarang(id) {
            Swal.fire({
                title: 'Konfirmasi Pengembalian',
                text: "Apakah barang sudah dikembalikan dalam kondisi baik?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, sudah kembali!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('peminjaman/kembalikan') }}/" + id,
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            Swal.fire('Berhasil!', response.success, 'success');
                            $('#peminjaman-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            Swal.fire('Gagal!', xhr.responseJSON.error, 'error');
                        }
                    });
                }
            })
        }
    </script>
@endpush