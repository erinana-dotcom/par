<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Parkir - {{ $transaksi->plat_nomor }}</title>
    <style>
        /* Latar krem vintage dan teks cokelat tua */
        body {
            font-family: 'Courier New', monospace;
            width: 280px;
            margin: 20px auto;
            font-size: 13px;
            background-color: #f4ecd8; /* Warna kertas koran lama */
            color: #433422;            /* Warna tulisan cokelat gelap jadul */
            padding: 15px;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        h2 { text-align: center; margin-bottom: 4px; color: #2d2216; }
        .center { text-align: center; }

        /* Garis putus-putus cokelat pudar */
        hr { border: none; border-top: 1px dashed #a89276; margin: 10px 0; }
        table { width: 100%; }
        td { padding: 2px 0; vertical-align: top; }
        td:first-child { white-space: nowrap; padding-right: 6px; }
        .total { font-weight: bold; font-size: 15px; color: #1e150b; }

        /* Tombol cetak senada dengan tema */
        button {
            background-color: #8c7355;
            color: #f4ecd8;
            border: none;
            padding: 6px 12px;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            cursor: pointer;
            border-radius: 3px;
        }
        button:hover { background-color: #6d563d; }

        /* Saat dicetak ke kertas thermal: latar putih agar hemat tinta, tombol disembunyikan */
        @media print {
            body { background-color: #fff; color: #000; padding: 0; box-shadow: none; margin: 0 auto; }
            hr { border-top: 1px dashed #000; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <h2>PARKIRIN</h2>
    <p class="center">Struk Karcis Parkir</p>
    <hr>
    <table>
        <tr><td>No. Struk</td><td>: #{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</td></tr>
        <tr><td>Plat Nomor</td><td>: {{ $transaksi->plat_nomor }}</td></tr>
        <tr><td>Jenis</td><td>: {{ $transaksi->jenis_kendaraan }}</td></tr>
        <tr><td>Zona</td><td>: {{ ucwords($transaksi->nama_zona) }}</td></tr>
        <tr><td>Masuk</td><td>: {{ \Carbon\Carbon::parse($transaksi->waktu_masuk)->locale('id')->translatedFormat('d M Y H:i') }}</td></tr>
        <tr><td>Keluar</td><td>: {{ $transaksi->waktu_keluar ? \Carbon\Carbon::parse($transaksi->waktu_keluar)->locale('id')->translatedFormat('d M Y H:i') : '-' }}</td></tr>
        <tr><td>Durasi</td><td>: {{ $transaksi->durasi_jam ?? '-' }} jam</td></tr>
    </table>
    <hr>
    <table>
        <tr class="total"><td>TOTAL</td><td>: Rp {{ number_format($transaksi->total_biaya ?? 0, 0, ',', '.') }}</td></tr>
    </table>
    <hr>
    <p class="center">Terima kasih!</p>

    <div class="no-print center" style="margin-top:16px;">
        <button onclick="window.print()">🖨️ Cetak</button>
    </div>
</body>
</html>