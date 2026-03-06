@extends('layouts.app')

@section('title', 'Laporan Kerusakan')
@section('header', 'Pengaduan Kerusakan Barang')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('laporan-kerusakan.create') }}" class="btn btn-primary float-right">
                    <i class="fas fa-plus-circle"></i> Lapor Kerusakan Baru
                </a>
            </div>
            <div class="card-body">
                <table id="laporan-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="30px">No</th>
                            <th>Tanggal</th>
                            <th>Barang</th>
                            @hasanyrole('Super Admin|Petugas Sarpras')
                            <th>Pelapor</th>
                            @endhasanyrole
                            <th>Kerusakan</th>
                            <th>Status</th>
                            <th width="100px">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@hasanyrole('Super Admin|Petugas Sarpras')
<!-- Modal Respon -->
<div class="modal fade" id="responModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Respon Laporan Kerusakan</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="report_id">
                <div class="form-group">
                    <label>Update Status</label>
                    <select id="update_status" class="form-control">
                        <option value="diproses">Diproses (Sedang Dicek)</option>
                        <option value="selesai">Selesai (Sudah Diperbaiki)</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Catatan Admin</label>
                    <textarea id="catatan_admin" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" onclick="saveRespon()" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>
@endhasanyrole
@endsection

@push('js')
<script>
    $(function () {
        $('#laporan-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('laporan-kerusakan.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'created_at', name: 'created_at', render: function(data) {
                    return moment(data).format('DD/MM/YYYY HH:mm');
                }},
                {data: 'nama_barang', name: 'barang.nama_barang'},
                @hasanyrole('Super Admin|Petugas Sarpras')
                {data: 'pelapor', name: 'user.name'},
                @endhasanyrole
                {data: 'deskripsi_kerusakan', name: 'deskripsi_kerusakan'},
                {data: 'status_badge', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });

    function updateStatus(id) {
        $('#report_id').val(id);
        $('#responModal').modal('show');
    }

    function saveRespon() {
        let id = $('#report_id').val();
        let status = $('#update_status').val();
        let catatan = $('#catatan_admin').val();

        $.ajax({
            url: "{{ url('laporan-kerusakan/update-status') }}/" + id,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                status: status,
                catatan: catatan
            },
            success: function(response) {
                $('#responModal').modal('hide');
                $('#laporan-table').DataTable().ajax.reload();
                Swal.fire('Berhasil', response.message, 'success');
            }
        });
    }

    function deleteReport(id) {
        Swal.fire({
            title: 'Batalkan Laporan?',
            text: "Laporan yang belum diproses bisa dibatalkan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Batalkan!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('laporan-kerusakan') }}/" + id,
                    type: "DELETE",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function() {
                        $('#laporan-table').DataTable().ajax.reload();
                        Swal.fire('Terhapus', 'Laporan berhasil dibatalkan.', 'success');
                    }
                });
            }
        });
    }
</script>
@endpush
