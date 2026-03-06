<!DOCTYPE html>
<html>
<head>
    <title>Cetak Label Barang</title>
    <style>
        @page { margin: 1cm; }
        body { font-family: 'Helvetica', sans-serif; }
        .label-container {
            width: 100%;
            border-collapse: collapse;
        }
        .label-box {
            width: 33.33%;
            border: 1px dashed #ccc;
            padding: 10px;
            text-align: center;
            vertical-align: top;
            height: 180px;
        }
        .nama {
            font-size: 10pt;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            height: 30px;
            overflow: hidden;
        }
        .kode {
            font-size: 9pt;
            color: #333;
            margin-bottom: 5px;
        }
        .qr-code {
            margin: 5px auto;
        }
        .footer-label {
            font-size: 7pt;
            color: #777;
            margin-top: 5px;
            border-top: 1px solid #eee;
            padding-top: 3px;
        }
    </style>
</head>
<body>
    <table class="label-container">
        @foreach($barangs->chunk(3) as $chunk)
        <tr>
            @foreach($chunk as $barang)
            <td class="label-box">
                <div class="nama">{{ $barang->nama_barang }}</div>
                <div class="kode"><code>{{ $barang->kode_barang }}</code></div>
                <div class="qr-code">
                    <img src="data:image/png;base64, {!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(80)->generate($barang->kode_barang)) !!} ">
                </div>
                <div class="footer-label">SISARPA - Inventaris Sekolah</div>
            </td>
            @endforeach
            @if($chunk->count() < 3)
                @for($i = 0; $i < (3 - $chunk->count()); $i++)
                    <td class="label-box" style="border:none;"></td>
                @endfor
            @endif
        </tr>
        @endforeach
    </table>
</body>
</html>
