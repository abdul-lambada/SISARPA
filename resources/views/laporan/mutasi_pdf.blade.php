<!DOCTYPE html>
<html>
<head>
    <title>Laporan Mutasi Barang {{ $year }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h3 { margin: 0; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background-color: #f2f2f2; text-align: center; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; }
        .sign { float: right; width: 250px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h3>REKAPITULASI MUTASI BARANG INVENTARIS</h3>
        <h3>SMK NEGERI CONTOH SISARPA</h3>
        <p>LAPORAN TAHUN ANGGARAN: {{ $year }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" width="30px">No</th>
                <th rowspan="2">Kode Barang</th>
                <th rowspan="2">Nama Barang</th>
                <th rowspan="2">Kategori</th>
                <th colspan="3">Mutasi</th>
                <th rowspan="2">Stok Akhir</th>
                <th rowspan="2">Satuan</th>
            </tr>
            <tr>
                <th>Masuk (+)</th>
                <th>BHP (-)</th>
                <th>Rusak (-)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barangs as $index => $b)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $b->kode_barang }}</td>
                <td>{{ $b->nama_barang }}</td>
                <td>{{ $b->nama_kategori }}</td>
                <td class="text-center">{{ $b->masuk }}</td>
                <td class="text-center">{{ $b->keluar }}</td>
                <td class="text-center">{{ $b->rusak }}</td>
                <td class="text-center"><strong>{{ $b->stok }}</strong></td>
                <td class="text-center">{{ $b->satuan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div style="float: left; width: 250px; text-align: center;">
            <p>Mengetahui,</p>
            <p>Kepala Sekolah</p>
            <div style="height: 60px;"></div>
            <p>( _______________________ )</p>
            <p>NIP: ............................</p>
        </div>
        <div class="sign">
            <p>Dicetak Pada: {{ date('d F Y') }}</p>
            <p>Pengelola Sarpras</p>
            <div style="height: 60px;"></div>
            <p>( _______________________ )</p>
            <p>NIP: ............................</p>
        </div>
    </div>
</body>
</html>
