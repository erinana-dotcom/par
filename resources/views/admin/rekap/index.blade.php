<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Transaksi</title>
    @vite('resources/css/app.css')
    <style>
        /* MENGUBAH VARIABEL WARNA MENJADI VINTAGE SEPIA */
        :root {
            --asphalt: #EDE4D3;          /* Latar belakang luar (krem) */
            --asphalt-light: #E3D5BC;    /* Sidebar (krem sedikit lebih gelap) */
            --card: #F5EEDD;             /* Kotak panel & stat card (krem terang) */
            --white-paper: #FAF6EE;      /* Latar input & tabel */
            --line-yellow: #8B5E34;      /* Warna utama / aksen (cokelat tanah) */
            --text-primary: #3E2E1F;     /* Tulisan utama (cokelat gelap) */
            --text-muted: #7A6852;       /* Tulisan sekunder (cokelat pudar) */
            --border: #C9B896;           /* Garis pembatas */
            --danger: #9C3D2E;           /* Warna error/danger (merah bata) */
            --ok: #5E7052;               /* Warna sukses/parked (hijau zaitun pudar) */
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--asphalt); font-family: 'Inter', sans-serif; color: var(--text-primary); display: flex; min-height: 100vh; }
        
        /* SIDEBAR */
        .sidebar { width: 220px; background: var(--asphalt-light); border-right: 1px solid var(--border); padding: 24px 18px; }
        .brand { font-family: 'Barlow Condensed', sans-serif; font-weight: 700; font-size: 21px; padding-bottom: 24px; border-bottom: 2px dashed var(--border); margin-bottom: 18px; }
        .brand span { color: var(--line-yellow); }
        nav a { display: flex; align-items: center; gap: 10px; color: var(--text-primary); font-weight: 600; text-decoration: none; font-size: 14px; padding: 10px 12px; border-radius: 4px; transition: all 0.2s; }
        nav a:hover { background: var(--asphalt); color: var(--line-yellow); }
        
        /* MAIN CONTENT */
        .main { flex: 1; padding: 32px; }
        h2 { font-family: 'Barlow Condensed', sans-serif; font-size: 24px; margin-bottom: 16px; }
        .panel { background: var(--card); border: 1px solid var(--border); border-radius: 6px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 6px rgba(62, 46, 31, 0.03); }
        
        /* STATS (RINGKASAN ANGKA) */
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
        .stat-card { background: var(--card); border: 1px solid var(--border); border-radius: 6px; padding: 18px 20px; box-shadow: 0 4px 6px rgba(62, 46, 31, 0.03); position: relative; }
        .stat-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: var(--border); border-radius: 6px 0 0 6px; }
        .stat-card:last-child::before { background: var(--line-yellow); } /* Khusus total pendapatan dikasih aksen cokelat */
        .stat-card .label { font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-card .value { font-family: 'Barlow Condensed', sans-serif; font-weight: 700; font-size: 32px; margin-top: 6px; color: var(--text-primary); }
        
        /* FILTER FORM */
        .filter-form { display: grid; grid-template-columns: 1fr 1fr auto; gap: 12px; align-items: end; }
        input, select { background: var(--white-paper); border: 1px solid var(--border); border-radius: 4px; padding: 10px; color: var(--text-primary); font-size: 13.5px; width: 100%; outline: none; transition: border-color 0.2s; }
        input:focus { border-color: var(--line-yellow); }
        label { display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; text-transform: uppercase; }
        
        /* BUTTONS */
        button.btn-filter { background: var(--line-yellow); color: #FAF6EE; border: none; padding: 10px 24px; font-weight: 600; border-radius: 4px; cursor: pointer; height: 38px; transition: background 0.2s; }
        button.btn-filter:hover { background: #734d2a; }
        
        /* TABLE STYLING */
        table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        th { text-align: left; color: var(--text-muted); padding: 14px 12px; border-bottom: 2px solid var(--border); font-size: 12px; font-weight: 700; }
        td { padding: 14px 12px; border-bottom: 1px solid rgba(199, 184, 150, 0.5); vertical-align: middle; }
        tr:hover td { background: rgba(227, 213, 188, 0.2); }
        
        /* ACCENTS & PILLS */
        .plate { 
            font-family: 'Barlow Condensed', sans-serif; 
            font-weight: 700; 
            background: var(--white-paper); 
            border: 2px solid var(--text-primary); 
            border-radius: 4px; 
            padding: 3px 8px; 
            display: inline-block; 
            font-size: 13.5px; 
            box-shadow: 1px 1px 0px var(--text-primary);
        }
        .status-pill { font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 4px; display: inline-block; text-transform: uppercase; }
        .status-pill.parked { background: rgba(94,112,82,0.15); color: var(--ok); border: 1px solid rgba(94,112,82,0.3); }
        .status-pill.exited { background: rgba(122,104,82,0.15); color: var(--text-muted); border: 1px solid rgba(122,104,82,0.3); }
    </style>
</head>
<body>
<aside class="sidebar">
  <div class="brand">PARKIR<span>IN</span></div>
  <nav><a href="/dashboard/{{ auth()->user()->role }}">⬅️ Kembali Dashboard</a></nav>
</aside>
<div class="main">
    <h2>Rekap Transaksi Parkir</h2>

    <!-- Ringkasan Angka (Stats) -->
    <div class="stats">
        <div class="stat-card">
            <div class="label">Total Transaksi</div>
            <div class="value">{{ $totalTransaksi }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Selesai (Keluar)</div>
            <div class="value">{{ $totalSelesai }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Masih Terparkir</div>
            <div class="value">{{ $totalTerparkir }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Total Pendapatan</div>
           <div class="value" style="color: var(--line-yellow);">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Filter Tanggal -->
    <div class="panel">
        <form method="GET" action="{{ route('rekap.index') }}" class="filter-form">
            <div>
                <label>Dari Tanggal</label>
                <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}">
            </div>
            <div>
                <label>Sampai Tanggal</label>
                <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}">
            </div>
            <button type="submit" class="btn-filter">Tampilkan</button>
        </form>
    </div>

    <!-- Daftar Detail Transaksi -->
    <div class="panel">
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th>PLAT NOMOR</th>
                    <th>JENIS</th>
                    <th>ZONA</th>
                    <th>MASUK</th>
                    <th>KELUAR</th>
                    <th>DURASI</th>
                    <th>BIAYA</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $trx)
                <tr>
                    <td><span class="plate">{{ $trx->plat_nomor }}</span></td>
                    <td>{{ $trx->jenis_kendaraan }}</td>
                    <td>{{ $trx->nama_zona }}</td>
                    <td><span style="font-family: monospace;">{{ date('d M Y H:i', strtotime($trx->waktu_masuk)) }}</span></td>
                    <td><span style="font-family: monospace;">{{ $trx->waktu_keluar ? date('d M Y H:i', strtotime($trx->waktu_keluar)) : '-' }}</span></td>
                    <td>{{ $trx->durasi_jam ? $trx->durasi_jam.' jam' : '-' }}</td>
                    <td><span style="color: var(--line-yellow); font-weight: 700;">{{ $trx->total_biaya ? 'Rp '.number_format($trx->total_biaya, 0, ',', '.') : '-' }}</span></td>
                    <td><span class="status-pill {{ $trx->status == 'Terparkir' ? 'parked' : 'exited' }}">{{ $trx->status }}</span></td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center; color: var(--text-muted); padding: 24px;">Tidak ada transaksi pada rentang tanggal ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
