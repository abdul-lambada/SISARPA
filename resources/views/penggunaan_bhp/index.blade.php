@extends('layouts.app')

@section('title', 'Distribusi BHP')
@section('header', 'Riwayat Penggunaan / Distribusi BHP')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('penggunaan-bhp.create') }}" class="btn btn-primary float-right">
                        <i class="fas fa-plus"></i> Catat Pengambilan Baru
                    </a>
                </div>
                <div class="card-body">
                    <table id="bhp-usage-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="30px">No</th>
                                <th>Tanggal</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Penerima</th>
                                <th>Keterangan</th>
                                <th width="50px">Aksi</th>
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
            $('#bhp-usage-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('penggunaan-bhp.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'tanggal', name: 'tanggal' },
                    { data: 'nama_barang', name: 'nama_barang' },
                    { data: 'jumlah', name: 'jumlah' },
                    { data: 'nama_penerima', name: 'nama_penerima' },
                    { data: 'catatan', name: 'catatan' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        });
    </script>
@endpush