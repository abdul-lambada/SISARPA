<!DOCTYPE html>
<html>
<head>
    <title>Berita Acara Serah Terima</title>
    <style>
        body { font-family: 'Times New Roman', serif; padding: 20px; line-height: 1.6; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header p { margin: 0; font-size: 11pt; }
        .title { text-align: center; text-decoration: underline; font-weight: bold; font-size: 14pt; margin-top: 10px; }
        .content { margin-top: 30px; }
        .table-data { width: 100%; margin-top: 10px; border-collapse: collapse; }
        .table-data td { padding: 5px; vertical-align: top; }
        .terms { margin-top: 30px; font-size: 11pt; }
        .signature-section { margin-top: 60px; width: 100%; }
        .signature-box { width: 45%; text-align: center; display: inline-block; vertical-align: top; }
        .signature-img { height: 80px; margin: 10px 0; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9pt; color: #777; font-style: italic; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ strtoupper(\App\Models\Setting::get('school_city')) }}</h2>
        <h2>{{ strtoupper(\App\Models\Setting::get('school_name')) }}</h2>
        <p>{{ \App\Models\Setting::get('school_address') }}, Website: {{ \App\Models\Setting::get('school_website') }}</p>
    </div>

    <div class="title">BERITA ACARA SERAH TERIMA BARANG (BAST)</div>
    <p style="text-align: center;">Nomor: {{ $peminjaman->id }}/BAST/SARPRAS/{{ date('Y') }}</p>

    <div class="content">
        <p>Pada hari ini, <strong>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('l, d F Y') }}</strong>, kami yang bertanda tangan di bawah ini:</p>
        
        <table class="table-data">
            <tr>
                <td width="30%">Nama Peminjam</td>
                <td width="3%">:</td>
                <td>{{ $peminjaman->user->name }}</td>
            </tr>
            <tr>
                <td>NIS/NIP</td>
                <td>:</td>
                <td>{{ $peminjaman->user->no_induk ?? '-' }}</td>
            </tr>
            <tr>
                <td>Jabatan / Kelas</td>
                <td>:</td>
                <td>{{ strtoupper($peminjaman->user->jenis_user) }} {{ $peminjaman->user->kelas ? '- ' . $peminjaman->user->kelas : '' }}</td>
            </tr>
        </table>

        <p>Telah menerima barang/aset sekolah dari bagian Sarpras dengan rincian sebagai berikut:</p>

        <table class="table-data" border="1" style="border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th width="5%" style="padding: 10px;">No</th>
                    <th width="30%">Kode Barang</th>
                    <th>Nama Barang / Merk</th>
                    <th width="15%">Kondisi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center; padding: 10px;">1</td>
                    <td style="padding: 10px;">{{ $peminjaman->barang->kode_barang }}</td>
                    <td style="padding: 10px;">{{ $peminjaman->barang->nama_barang }} {{ $peminjaman->barang->merk ? '(' . $peminjaman->barang->merk . ')' : '' }}</td>
                    <td style="text-align: center; padding: 10px;">{{ strtoupper($peminjaman->barang->kondisi) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="terms">
            <strong>Syarat dan Ketentuan:</strong>
            <ol>
                <li>Peminjam wajib menjaga kondisi barang tetap baik dan bersih.</li>
                <li>Barang hanya boleh digunakan untuk kepentingan sekolah.</li>
                <li>Segala bentuk kerusakan akibat kelalaian peminjam menjadi tanggung jawab penuh peminjam untuk mengganti biaya servis atau barang baru.</li>
                <li>Barang wajib dikembalikan tepat waktu sesuai jadwal perizinan.</li>
            </ol>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <p>Petugas Sarpras,</p>
                <div style="height: 80px;"></div>
                <p>( _______________________ )</p>
                <p>NIP: ............................</p>
            </div>
            <div class="signature-box" style="float: right;">
                <p>Peminjam,</p>
                <div class="signature-img">
                    @if($peminjaman->tanda_tangan)
                        <img src="{{ $peminjaman->tanda_tangan }}" style="height: 80px;">
                    @else
                        <div style="height: 80px;"></div>
                    @endif
                </div>
                <p>( <strong>{{ $peminjaman->user->name }}</strong> )</p>
                <p>{{ strtoupper($peminjaman->user->jenis_user) }}</p>
            </div>
        </div>
    </div>

    <div class="footer">
        Dicetak otomatis oleh Sistem Informasi Sarana Prasarana (SISARPA) pada {{ date('d/m/Y H:i') }}
    </div>
</body>
</html>
