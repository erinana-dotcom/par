<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Transaksi - PARKIRIN</title>
    @vite('resources/css/app.css')
    <style>
        :root {
            --asphalt: #EDE4D3;
            --asphalt-light: #E3D5BC;
            --card: #F5EEDD;
            --white-paper: #FAF6EE;
            --line-yellow: #8B5E34;
            --text-primary: #3E2E1F;
            --text-muted: #7A6852;
            --border: #C9B896;
            --danger: #9C3D2E;
            --ok: #5E7052;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--asphalt); font-family: 'Inter', sans-serif; color: var(--text-primary); display: flex; min-height: 100vh; }

        /* SIDEBAR */
        .sidebar { width: 220px; flex-shrink: 0; background: var(--asphalt-light); border-right: 1px solid var(--border); padding: 24px 18px; }
        .brand { font-family: 'Barlow Condensed', sans-serif; font-weight: 700; font-size: 21px; padding-bottom: 24px; border-bottom: 2px dashed var(--border); margin-bottom: 18px; }
        .brand span { color: var(--line-yellow); }
        nav a { display: flex; align-items: center; gap: 10px; color: var(--text-primary); font-weight: 600; text-decoration: none; font-size: 14px; padding: 10px 12px; border-radius: 4px; transition: all 0.2s; }
        nav a:hover { background: var(--asphalt); color: var(--line-yellow); }

        /* MAIN */
        .main { flex: 1; min-width: 0; padding: 32px; }
        .head { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 16px; }
        h2 { font-family: 'Barlow Condensed', sans-serif; font-size: 24px; }
        .panel { background: var(--card); border: 1px solid var(--border); border-radius: 6px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 6px rgba(62, 46, 31, 0.03); }

        /* STATS */
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 8px; }
        .stat-card { background: var(--card); border: 1px solid var(--border); border-radius: 6px; padding: 18px 20px; box-shadow: 0 4px 6px rgba(62, 46, 31, 0.03); position: relative; }
        .stat-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: var(--border); border-radius: 6px 0 0 6px; }
        .stat-card.income::before { background: var(--line-yellow); }
        .stat-card .label { font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-card .value { font-family: 'Barlow Condensed', sans-serif; font-weight: 700; font-size: 32px; margin-top: 6px; }
        .stat-card.income .value { color: var(--line-yellow); }
        .note { font-size: 12px; color: var(--text-muted); margin-bottom: 24px; }

        /* FILTER */
        .filter-form { display: grid; grid-template-columns: 1fr 1fr auto auto; gap: 12px; align-items: end; }
        input { background: var(--white-paper); border: 1px solid var(--border); border-radius: 4px; padding: 10px; color: var(--text-primary); font-size: 13.5px; width: 100%; outline: none; font-family: inherit; transition: border-color 0.2s; }
        input:focus { border-color: var(--line-yellow); }
        label { display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; text-transform: uppercase; }

        /* BUTTONS */
        .btn-filter { background: var(--line-yellow); color: #FAF6EE; border: none; padding: 10px 24px; font-weight: 600; border-radius: 4px; cursor: pointer; height: 38px; font-family: inherit; font-size: 13.5px; transition: background 0.2s; }
        .btn-filter:hover { background: #734d2a; }
        .btn-ghost { display: inline-flex; align-items: center; height: 38px; padding: 0 16px; border: 1px solid var(--line-yellow); border-radius: 4px; color: var(--line-yellow); background: transparent; font-weight: 600; font-size: 13.5px; text-decoration: none; cursor: pointer; font-family: inherit; }
        .btn-ghost:hover { background: var(--line-yellow); color: #FAF6EE; }

        /* ALERT */
        .alert-error { padding: 12px; border-radius: 4px; margin-bottom: 16px; font-size: 13px; background: rgba(156,61,46,0.12); color: var(--danger); border: 1px solid rgba(156,61,46,0.3); }

        /* TABLE */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        th { text-align: left; color: var(--text-muted); padding: 14px 12px; border-bottom: 2px solid var(--border); font-size: 12px; font-weight: 700; white-space: nowrap; }
        td { padding: 14px 12px; border-bottom: 1px solid rgba(199, 184, 150, 0.5); vertical-align: middle; white-space: nowrap; }
        tr:hover td { background: rgba(227, 213, 188, 0.2); }

        .plate { font-family: 'Barlow Condensed', sans-serif; font-weight: 700; background: var(--white-paper); border: 2px solid var(--text-primary); border-radius: 4px; padding: 3px 8px; display: inline-block; font-size: 13.5px; box-shadow: 1px 1px 0px var(--text-primary); }
        .status-pill { font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 4px; display: inline-block; text-transform: uppercase; }
        .status-pill.parked { background: rgba(94,112,82,0.15); color: var(--ok); border: 1px solid rgba(94,112,82,0.3); }
        .status-pill.exited { background: rgba(122,104,82,0.15); color: var(--text-muted); border: 1px solid rgba(122,104,82,0.3); }
        .empty { text-align: center; color: var(--text-muted); padding: 24px; }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .stats { grid-template-columns: repeat(2, 1fr); }
            .filter-form { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 640px) {
            body { flex-direction: column; }
            .sidebar { width: 100%; border-right: none; border-bottom: 1px solid var(--border); padding: 14px 16px; }
            .brand { padding-bottom: 12px; margin-bottom: 8px; }
            .main { padding: 18px; }
            .stats, .filter-form { grid-template-columns: 1fr; }
        }

        /* CETAK */
        @media print {
            .sidebar, .no-print { display: none !important; }
            body { background: #fff; display: block; }
            .main { padding: 0; }
            .panel, .stat-card { box-shadow: none; }
        }
    </style>
</head>
<body>
<aside class="sidebar">
    <div class="brand">PARKIR<span>IN</span></div>
    <nav><a href="/dashboard/{{ auth()->user()->role }}">⬅️ Kembali Dashboard</a></nav>
</aside>

<div class="main">
    <div class="head">
        <h2>Rekap Transaksi Parkir</h2>
        <button type="button" class="btn-ghost no-print" onclick="window.print()">🖨️ Cetak Rekap</button>
    </div>

    @if($errors->any())
        <div class="alert-error">
            @foreach($errors->all() as $pesan)
                <div>{{ $pesan }}</div>
            @endforeach
        </div>
    @endif

    <!-- Ringkasan Angka -->
    <div class="stats">
        <div class="stat-card">
            <div class="label">Total Transaksi</div>
            <div class="value">{{ number_format($totalTransaksi, 0, ',', '.') }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Selesai (Keluar)</div>
            <div class="value">{{ number_format($totalSelesai, 0, ',', '.') }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Masih Terparkir</div>
            <div class="value">{{ number_format($totalTerparkir, 0, ',', '.') }}</div>
        </div>
        <div class="stat-card income">
            <div class="label">Total Pendapatan</div>
            <div class="value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
    </div>
    <p class="note">
        Periode {{ \Carbon\Carbon::parse($tanggalMulai)->locale('id')->translatedFormat('d M Y') }}
        – {{ \Carbon\Carbon::parse($tanggalSelesai)->locale('id')->translatedFormat('d M Y') }}.
        Pendapatan dihitung dari kendaraan yang keluar pada periode ini.
    </p>

    <!-- Filter Tanggal -->
    <div class="panel no-print">
        <form method="GET" action="{{ route('rekap.index') }}" class="filter-form">
            <div>
                <label for="tanggal_mulai">Dari Tanggal</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ $tanggalMulai }}">
            </div>
            <div>
                <label for="tanggal_selesai">Sampai Tanggal</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ $tanggalSelesai }}">
            </div>
            <button type="submit" class="btn-filter">Tampilkan</button>
            <a href="{{ route('rekap.index') }}" class="btn-ghost">Bulan Ini</a>
        </form>
    </div>

    <!-- Daftar Detail Transaksi -->
    <div class="panel">
        <div class="table-wrap">
            <table>
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
                        <td>{{ ucwords($trx->nama_zona) }}</td>
                        <td>{{ \Carbon\Carbon::parse($trx->waktu_masuk)->locale('id')->translatedFormat('d M Y H:i') }}</td>
                        <td>{{ $trx->waktu_keluar ? \Carbon\Carbon::parse($trx->waktu_keluar)->locale('id')->translatedFormat('d M Y H:i') : '-' }}</td>
                        <td>{{ $trx->durasi_jam ? $trx->durasi_jam . ' jam' : '-' }}</td>
                        <td><span style="color: var(--line-yellow); font-weight: 700;">{{ $trx->total_biaya ? 'Rp ' . number_format($trx->total_biaya, 0, ',', '.') : '-' }}</span></td>
                        <td><span class="status-pill {{ $trx->status == 'Terparkir' ? 'parked' : 'exited' }}">{{ $trx->status }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="empty">Tidak ada transaksi pada rentang tanggal ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="no-print" style="margin-top: 16px;">
            {{ $transaksis->links() }}
        </div>
    </div>
</div>
</body>
</html>