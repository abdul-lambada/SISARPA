@extends('layouts.app')

@section('title', 'Kategori')
@section('header', 'Daftar Kategori')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('kategori.create') }}" class="btn btn-primary float-right">
                        <i class="fas fa-plus"></i> Tambah Kategori
                    </a>
                </div>
                <div class="card-body">
                    <table id="kategori-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="50px">No</th>
                                <th>Nama Kategori</th>
                                <th width="100px">Aksi</th>
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
            $('#kategori-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('kategori.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'nama_kategori', name: 'nama_kategori' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        });
    </script>
@endpush