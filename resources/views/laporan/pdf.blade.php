<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Hasil Seleksi Penerima PKH - Desa Thaibah Raya</title>
    <style>
        @page { margin: 1.5cm; }
        body {
            font-family: 'DejaVu Sans', Helvetica, Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.3;
            color: #111827;
        }
        .kop {
            text-align: center;
            border-bottom: 3px double #111827;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .kop h4 {
            margin: 0;
            font-size: 9pt;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .kop h2 {
            margin: 3px 0 0 0;
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop p {
            margin: 3px 0 0 0;
            font-size: 8pt;
            color: #4b5563;
        }
        .title {
            text-align: center;
            margin-bottom: 14px;
        }
        .title h3 {
            margin: 0;
            font-size: 11pt;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .title p {
            margin: 3px 0 0 0;
            font-size: 8.5pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        table, th, td {
            border: 1px solid #1f2937;
        }
        th {
            background-color: #f3f4f6;
            font-size: 8pt;
            padding: 5px 4px;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }
        td {
            font-size: 7.5pt;
            padding: 4px;
        }
        .text-center { text-align: center; }
        .font-mono { font-family: monospace; }
        .font-bold { font-weight: bold; }
        .layak { color: #047857; font-weight: bold; }
        .tidak-layak { color: #b91c1c; font-weight: bold; }
        .ttd-box {
            margin-top: 25px;
            float: right;
            width: 220px;
            text-align: center;
            font-size: 8.5pt;
        }
        .clear { clear: both; }
    </style>
</head>
<body>

    <div class="kop">
        <h4>Pemerintah Kabupaten Banjar</h4>
        <h4>Kecamatan Tatah Makmur</h4>
        <h2>Kantor Kepala Desa Thaibah Raya</h2>
        <p>Jl. Tatah Makmur, Desa Thaibah Raya, Kec. Tatah Makmur, Kab. Banjar, Prov. Kalimantan Selatan</p>
    </div>

    <div class="title">
        <h3>Laporan Hasil Seleksi Penerima Bantuan PKH</h3>
        <p>Periode: <strong>{{ $periode->nama_periode ?? '-' }} (Tahun {{ $periode->tahun ?? '-' }})</strong></p>
        <p style="font-size: 8pt; color: #4b5563;">Metode Perhitungan: Simple Additive Weighting (SAW)</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 20px;">No</th>
                <th>Nama Kepala Keluarga</th>
                <th>Nomor KK</th>
                <th>Wilayah</th>
                <th style="width: 60px;">Nilai (V_i)</th>
                <th style="width: 45px;">Ranking</th>
                <th style="width: 70px;">Keputusan</th>
            </tr>
        </thead>
        <tbody>
            @forelse(($rankings ?? $rankingList ?? []) as $item)
                @php
                    $kel = is_array($item) ? ($item['keluarga'] ?? null) : ($item->keluarga ?? null);
                    $vi = is_array($item) ? ($item['nilai_akhir'] ?? 0) : ($item->nilai_akhir ?? 0);
                    $rank = is_array($item) ? ($item['ranking'] ?? '-') : ($item->ranking ?? '-');
                    $status = is_array($item) ? ($item['status_kelayakan'] ?? '-') : ($item->status_kelayakan ?? '-');
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="font-bold">{{ $kel->nama_kepala_keluarga ?? '-' }}</td>
                    <td class="font-mono text-center">{{ $kel->no_kk ?? '-' }}</td>
                    <td class="text-center">RT {{ $kel->rt ?? '-' }} / RW {{ $kel->rw ?? '-' }}</td>
                    <td class="text-center font-mono font-bold">{{ number_format($vi, 4) }}</td>
                    <td class="text-center font-bold">#{{ $rank }}</td>
                    <td class="text-center {{ $status === 'Layak' ? 'layak' : 'tidak-layak' }}">
                        {{ $status }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 12px; color: #6b7280;">Tidak ada data hasil perhitungan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="ttd-box">
        <p>{{ $tanggalCetak ?? now()->translatedFormat('d F Y') }}</p>
        <p class="font-bold">Kepala Desa Thaibah Raya (Pambakal),</p>
        <div style="height: 55px;"></div>
        <p class="font-bold" style="text-decoration: underline; text-transform: uppercase;">{{ $pambakalNama ?? 'H. Ahmad Mawardi, S.Sos' }}</p>
        <p style="font-size: 8pt; color: #4b5563;">Pambakal Thaibah Raya</p>
    </div>
    <div class="clear"></div>

</body>
</html>
