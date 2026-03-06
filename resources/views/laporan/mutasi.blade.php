@extends('layouts.app')

@section('title', 'Laporan Mutasi')
@section('header', 'Laporan Mutasi Barang Tahunan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <form action="{{ route('laporan.mutasi') }}" method="GET" class="form-inline float-left">
                    <label class="mr-2">Tahun:</label>
                    <select name="year" class="form-control mr-2" onchange="this.form.submit()">
                        @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </form>
                <div class="float-right">
                    <a href="{{ route('laporan.mutasi.print', ['year' => $year]) }}" target="_blank" class="btn btn-danger">
                        <i class="fas fa-file-pdf"></i> Cetak Laporan PDF
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="mutasi-table">
                        <thead>
                            <tr class="bg-light text-center">
                                <th width="30px">No</th>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th class="bg-success text-white">Masuk (+)</th>
                                <th class="bg-warning">Keluar (BHP)</th>
                                <th class="bg-danger text-white">Rusak (-)</th>
                                <th class="bg-primary text-white">Stok Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barangs as $index => $b)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td><code>{{ $b['kode'] }}</code></td>
                                <td>{{ $b['nama'] }}</td>
                                <td>{{ $b['kategori'] }}</td>
                                <td class="text-center">{{ $b['masuk'] }}</td>
                                <td class="text-center">{{ $b['keluar'] }}</td>
                                <td class="text-center">{{ $b['rusak'] }}</td>
                                <td class="text-center font-weight-bold">{{ $b['stok_akhir'] }} {{ $b['satuan'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#mutasi-table').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
@endpush
