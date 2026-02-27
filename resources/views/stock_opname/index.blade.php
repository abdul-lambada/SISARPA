@extends('layouts.app')

@section('title', 'Stock Opname')
@section('header', 'Audit Stok (Stock Opname)')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('stock-opname.create') }}" class="btn btn-primary float-right">
                        <i class="fas fa-plus"></i> Mulai Opname Baru
                    </a>
                </div>
                <div class="card-body">
                    <table id="opname-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="30px">No</th>
                                <th>Tanggal</th>
                                <th>Ruangan</th>
                                <th>Petugas</th>
                                <th>Status</th>
                                <th width="200px">Aksi</th>
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
            $('#opname-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('stock-opname.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'tanggal', name: 'tanggal' },
                    { data: 'ruangan', name: 'ruangan' },
                    { data: 'user.name', name: 'user.name' },
                    {
                        data: 'status', name: 'status', render: function (data) {
                            let badge = data == 'draft' ? 'badge-warning' : 'badge-success';
                            return '<span class="badge ' + badge + '">' + data.toUpperCase() + '</span>';
                        }
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        });
    </script>
@endpush