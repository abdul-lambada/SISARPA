@extends('layouts.app')

@section('title', 'Pemeliharaan')
@section('header', 'Riwayat Pemeliharaan & Servis')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('pemeliharaan.create') }}" class="btn btn-primary float-right">
                        <i class="fas fa-plus"></i> Tambah Data Servis
                    </a>
                </div>
                <div class="card-body">
                    <table id="pemeliharaan-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="30px">No</th>
                                <th>Nama Barang</th>
                                <th>Tgl Servis</th>
                                <th>Biaya</th>
                                <th>Status</th>
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
            $('#pemeliharaan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('pemeliharaan.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'nama_barang', name: 'nama_barang' },
                    { data: 'tanggal_servis', name: 'tanggal_servis' },
                    { data: 'biaya_format', name: 'biaya_format' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        });
    </script>
@endpush