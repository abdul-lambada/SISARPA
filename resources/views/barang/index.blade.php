@extends('layouts.app')

@section('title', 'Data Barang')
@section('header', 'Daftar Barang Inventaris')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="float-right">
                        <a href="{{ route('barang.export') }}" class="btn btn-success mr-2">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                        <a href="{{ route('barang.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Barang
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="barang-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="30px">No</th>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Lokasi</th>
                                <th>Kondisi</th>
                                <th>Stok</th>
                                <th width="120px">Aksi</th>
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
            $('#barang-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('barang.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'kode_barang', name: 'kode_barang' },
                    { data: 'nama_barang', name: 'nama_barang' },
                    { data: 'nama_kategori', name: 'nama_kategori' },
                    { data: 'lokasi', name: 'lokasi' },
                    {
                        data: 'kondisi', name: 'kondisi', render: function (data) {
                            let badge = 'badge-success';
                            if (data == 'rusak') badge = 'badge-danger';
                            if (data == 'servis') badge = 'badge-warning';
                            return '<span class="badge ' + badge + '">' + data.toUpperCase() + '</span>';
                        }
                    },
                    { data: 'stok', name: 'stok' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        });
    </script>
@endpush