@extends('layouts.app')

@section('title', 'Manajemen User')
@section('header', 'Daftar Pengguna (Siswa, Guru, Staf)')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-right">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#importModal">
                        <i class="fas fa-file-import"></i> Import DAPODIK
                    </button>
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah User
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="users-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="30px">No</th>
                            <th>Username/NISN</th>
                            <th>Nama Lengkap</th>
                            <th>Jenis</th>
                            <th>Kelas</th>
                            <th>Role Sistem</th>
                            <th width="80px">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data User (DAPODIK)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">Pilih File Excel/CSV</label>
                        <input type="file" name="file" class="form-control-file" accept=".xlsx, .xls, .csv" required>
                    </div>
                    <div class="alert alert-info">
                        <strong>Info Format:</strong>
                        <ul class="mb-0 small">
                            <li>Heading row harus ada di baris pertama.</li>
                            <li>Kolom wajib: <code>nama</code>, <code>email</code>.</li>
                            <li>Kolom opsional: <code>username</code> (NISN/NIP), <code>no_induk</code>, <code>jenis</code> (siswa/guru/staf), <code>kelas</code>, <code>password</code>.</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Mulai Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(function () {
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('users.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'username', name: 'username'},
                {data: 'name', name: 'name'},
                {
                    data: 'jenis_user', 
                    name: 'jenis_user',
                    render: function(data) {
                        return data ? data.toUpperCase() : '-';
                    }
                },
                {data: 'kelas', name: 'kelas'},
                {data: 'roles', name: 'roles'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });
</script>
@endpush
