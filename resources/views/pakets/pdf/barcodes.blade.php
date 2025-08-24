{{--
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Barcodes' }}</title>
    <style>
        @page {
            margin: 10mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10pt;
        }

        .grid {
            display: flex;
            flex-wrap: wrap;
        }

        .label {
            box-sizing: border-box;
            width: 33.333%;
            /* 3 kolom */
            height: 148px;
            /* ~ 6 baris per A4 (297mm - margin) */
            padding: 6px 8px;
            border: 0.4pt solid #ddd;
            page-break-inside: avoid;
        }

        .kode {
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 2px;
        }

        .tgl {
            color: #555;
            margin-bottom: 6px;
        }

        .img-wrap {
            text-align: center;
            margin-top: 4px;
        }

        .img-wrap img {
            width: 92%;
            height: 40px;
            object-fit: contain;
        }

        .foot {
            margin-top: 4px;
            font-size: 8pt;
            color: #444;
        }

        /* Force 6 baris per halaman => setiap 18 label (3x6) pecah halaman */
        .label:nth-of-type(18n+1) {
            page-break-before: auto;
        }
    </style>
</head>

<body>
    <div class="grid">
        @foreach($items as $it)
        <div class="label">
            <div class="kode">{{ $it['kode'] }}</div>
            <div class="tgl">Tgl: {{ $it['tanggal'] }}</div>
            <div class="img-wrap">
                <img src="{{ $it['img'] }}" alt="barcode {{ $it['kode'] }}">
            </div>
            <div class="foot">Status: Pending</div>
        </div>
        @endforeach
    </div>
</body>

</html> --}}



<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Barcodes' }}</title>
    <style>
        /* A4 dengan margin 10mm */
        @page {
            margin: 10mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10pt;
            background: #fff;
        }

        /* Lebar area cetak: 210-20 = 190mm */
        table.sheet {
            width: 190mm;
            border-collapse: collapse;
            table-layout: fixed;
        }

        /* 3 kolom => 190 / 3 = 63.33mm per sel; 6 baris => tinggi ~ 46mm per sel */
        td.cell {
            width: 63.33mm;
            height: 46mm;
            padding: 3mm;
            border: 0.3pt solid #ddd;
            vertical-align: top;
            overflow: hidden;
            background: #fff;
        }

        td.cell.empty {
            border: none;
        }

        .kode {
            font-weight: 700;
            font-size: 11pt;
            margin-bottom: 2mm;
        }

        .tgl {
            color: #555;
            margin-bottom: 3mm;
        }

        /* Quiet zone Wajib: padding kiri/kanan 6mm */
        .barcode-box {
            padding: 0 6mm;
            background: #fff;
            text-align: center;
            border: 0.25pt solid #eee;
        }

        /* Tetapkan ukuran absolut (mm) supaya DomPDF tidak salah scale */
        .barcode-img {
            display: block;
            margin: 1mm auto 1mm auto;
            width: 50mm;
            /* lebar fix */
            height: 20mm;
            /* tinggi fix (≈150 px @150dpi) */
        }

        .foot {
            margin-top: 2mm;
            font-size: 8pt;
            color: #444;
        }
    </style>
</head>

<body>

    <table class="sheet">
        <tbody>
            @php $chunks = collect($items)->chunk(3); @endphp
            @foreach ($chunks as $row)
            <tr>
                @foreach ($row as $it)
                <td class="cell">
                    <div class="kode">{{ $it['kode'] }}</div>
                    <div class="tgl">Tgl: {{ $it['tanggal'] }}</div>
                    <div class="barcode-box">
                        <img class="barcode-img" src="{{ $it['img'] }}" alt="barcode {{ $it['kode'] }}">
                    </div>
                    <div class="foot">Status: Pending</div>
                </td>
                @endforeach
                @for ($i = $row->count(); $i < 3; $i++) <td class="cell empty">
                    </td>
                    @endfor
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>