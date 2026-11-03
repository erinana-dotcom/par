<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Parkir - {{ $transaksi->plat_nomor }}</title>
    <style>
        /* Mengubah warna latar belakang menjadi krem vintage dan teks cokelat tua */
        body { 
            font-family: 'Courier New', monospace; 
            width: 280px; 
            margin: 20px auto; 
            font-size: 13px; 
            background-color: #f4ecd8; /* Warna kertas koran lama */
            color: #433422; /* Warna tulisan cokelat gelap jadul */
            padding: 15px;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        h2 { text-align: center; margin-bottom: 4px; color: #2d2216; }
        .center { text-align: center; }
        
        /* Mengubah garis putus-putus menjadi cokelat pudar */
        hr { border: none; border-top: 1px dashed #a89276; margin: 10px 0; }
        table { width: 100%; }
        td { padding: 2px 0; }
        .total { font-weight: bold; font-size: 15px; color: #1e150b; }
        
        /* Menghias tombol cetak agar senada dengan tema */
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
        button:hover {
            background-color: #6d563d;
        }

        /* Saat dicetak ke kertas thermal, warna latar belakang kembali putih agar hemat tinta */
        @media print { 
            body { background-color: #fff; color: #000; padding: 0; box-shadow: none; }
            hr { border-top: 1px dashed #000; }
            .no-print { display: none; } 
        }
    </style>
</head>
<body>
    <h2>PARKIROS</h2>
    <p class="center">Struk Karcis Parkir</p>
    <hr>
    <table>
        <tr><td>Plat Nomor</td><td>: {{ $transaksi->plat_nomor }}</td></tr>
        <tr><td>Jenis</td><td>: {{ $transaksi->jenis_kendaraan }}</td></tr>
        <tr><td>Zona</td><td>: {{ $transaksi->nama_zona }}</td></tr>
        <tr><td>Masuk</td><td>: {{ date('d M Y H:i', strtotime($transaksi->waktu_masuk)) }}</td></tr>
        <tr><td>Keluar</td><td>: {{ $transaksi->waktu_keluar ? date('d M Y H:i', strtotime($transaksi->waktu_keluar)) : '-' }}</td></tr>
        <tr><td>Durasi</td><td>: {{ $transaksi->durasi_jam ?? '-' }} jam</td></tr>
    </table>
    <hr>
    <table>
        <tr class="total"><td>TOTAL</td><td>: Rp {{ number_format($transaksi->total_biaya ?? 0) }}</td></tr>
    </table>
    <hr>
    <p class="center">Terima kasih!</p>

    <div class="no-print center" style="margin-top:16px;">
        <button onclick="window.print()">🖨️ Cetak</button>
    </div>
</body>
</html>
