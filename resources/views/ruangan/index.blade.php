@extends('layouts.app')

@section('title', 'Data Ruangan & Fasilitas')
@section('header', 'Manajemen Fasilitas & Ruangan')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('ruangan.create') }}" class="btn btn-primary float-right">
                        <i class="fas fa-plus"></i> Tambah Ruangan
                    </a>
                </div>
                <div class="card-body">
                    <table id="ruangan-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="30px">No</th>
                                <th>Kode</th>
                                <th>Nama Ruangan</th>
                                <th>Kapasitas</th>
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
            $('#ruangan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('ruangan.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'kode_ruangan', name: 'kode_ruangan' },
                    { data: 'nama_ruangan', name: 'nama_ruangan' },
                    { data: 'kapasitas', name: 'kapasitas', render: function (data) { return data + ' Orang'; } },
                    { data: 'status_badge', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        });
    </script>
@endpush