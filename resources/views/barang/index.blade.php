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
                                <th>Jenis</th>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Satuan</th>
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
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'tipe_badge', name: 'tipe' },
                    { data: 'kode_barang', name: 'kode_barang' },
                    { data: 'nama_barang', name: 'nama_barang' },
                    { data: 'nama_kategori', name: 'nama_kategori' },
                    {
                        data: 'stok', name: 'stok', render: function (data, type, row) {
                            if (row.tipe == 'bhp' && data <= row.min_stok) {
                                return '<span class="text-danger font-weight-bold">' + data + ' (Kritis)</span>';
                            }
                            return data;
                        }
                    },
                    { data: 'satuan', name: 'satuan' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        });
    </script>
@endpush