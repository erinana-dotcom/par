<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard {{ ucfirst($role) }} - PARKIRIN</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')

    <style>
        :root {
            --asphalt: #EDE4D3;
            --asphalt-light: #E3D5BC;
            --card: #F5EEDD;
            --line-yellow: #8B5E34;
            --text-primary: #3E2E1F;
            --text-muted: #7A6852;
            --border: #C9B896;
            --danger: #9C3D2E;
            --warn: #B7791F;
            --ok: #5E7052;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--asphalt);
            font-family: 'Inter', sans-serif;
            color: var(--text-primary);
            display: flex;
            min-height: 100vh;
        }

        /* ---------- Sidebar ---------- */
        .sidebar {
            width: 220px;
            flex-shrink: 0;
            background: var(--asphalt-light);
            border-right: 1px solid var(--border);
            padding: 24px 18px;
            display: flex;
            flex-direction: column;
        }

        .brand {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 700;
            font-size: 21px;
            padding: 0 6px 24px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 18px;
        }
        .brand span { color: var(--line-yellow); }

        nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            padding: 10px 12px;
            border-radius: 3px;
            margin-bottom: 2px;
        }
        nav a .dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; opacity: .5; }
        nav a.active { background: rgba(139,94,52,.12); color: var(--line-yellow); }
        nav a.active .dot { background: var(--line-yellow); opacity: 1; }
        nav a:hover:not(.active) { color: var(--line-yellow); }

        .sidebar-foot {
            margin-top: auto;
            font-size: 12px;
            color: var(--text-muted);
            padding: 12px 6px 0;
            border-top: 1px solid var(--border);
        }
        .btn-logout {
            background: none; border: none; cursor: pointer; padding: 0;
            color: var(--danger); font: inherit; font-size: 12px; font-weight: 600;
        }
        .btn-logout:hover { text-decoration: underline; }

        /* ---------- Main ---------- */
        .main { flex: 1; min-width: 0; }

        .topbar {
            display: flex; align-items: center; justify-content: space-between;
            padding: 20px 32px;
            border-bottom: 1px solid var(--border);
        }
        .topbar h1 { font-family: 'Barlow Condensed', sans-serif; font-weight: 700; font-size: 24px; }
        .topbar .date { font-size: 13px; color: var(--text-muted); margin-top: 2px; }

        .officer { display: flex; align-items: center; gap: 10px; font-size: 13.5px; }
        .avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--line-yellow); color: #F5EEDD;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 13px;
        }

        .content { padding: 28px 32px 40px; }

        /* ---------- Stat cards ---------- */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }
        .stats.three { grid-template-columns: repeat(3, 1fr); }

        .stat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 18px 20px;
        }
        .stat-card .label { font-size: 12.5px; color: var(--text-muted); }
        .stat-card .value {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 700; font-size: 34px; margin-top: 10px;
        }
        .stat-card .delta { font-size: 12px; margin-top: 6px; color: var(--text-muted); }
        .delta.up { color: var(--ok); }
        .delta.down { color: var(--danger); }

        /* ---------- Panels ---------- */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1.6fr;
            gap: 16px;
            align-items: start;
        }

        .panel {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 20px 22px;
        }
        .panel h2 { font-family: 'Barlow Condensed', sans-serif; font-weight: 700; font-size: 17px; margin-bottom: 4px; }
        .panel .sub { font-size: 12.5px; color: var(--text-muted); margin-bottom: 18px; }

        .zone-row {
            display: grid;
            grid-template-columns: 70px 1fr 74px;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            margin-bottom: 14px;
        }
        .zone-row .name { color: var(--text-muted); font-weight: 500; overflow: hidden; text-overflow: ellipsis; }
        .bar-track {
            height: 8px; background: var(--asphalt);
            border-radius: 4px; overflow: hidden; border: 1px solid var(--border);
        }
        .bar-fill { height: 100%; background: var(--ok); border-radius: 4px 0 0 4px; }
        .bar-fill.warn   { background: var(--warn); }
        .bar-fill.danger { background: var(--danger); }
        .zone-row .pct { text-align: right; color: var(--text-muted); font-variant-numeric: tabular-nums; font-size: 12px; }

        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        thead th {
            text-align: left; font-weight: 500; color: var(--text-muted);
            font-size: 12px; padding: 0 10px 10px; border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        tbody td { padding: 12px 10px; border-bottom: 1px solid var(--border); white-space: nowrap; }
        tbody tr:last-child td { border-bottom: none; }

        .plate {
            font-family: 'Barlow Condensed', sans-serif; font-weight: 700; letter-spacing: .03em;
            background: var(--asphalt); border: 1px solid var(--border);
            border-radius: 3px; padding: 3px 8px; display: inline-block; font-size: 13px;
        }
        .status-pill { font-size: 11.5px; font-weight: 600; padding: 3px 9px; border-radius: 20px; display: inline-block; }
        .status-pill.parked { background: rgba(94,112,82,.16); color: var(--ok); }
        .status-pill.exited { background: rgba(122,104,82,.16); color: var(--text-muted); }

        .empty { font-size: 13px; color: var(--text-muted); padding: 8px 0; }
        .link-more { display: inline-block; margin-top: 14px; font-size: 13px; color: var(--line-yellow); text-decoration: none; font-weight: 600; }
        .link-more:hover { text-decoration: underline; }

        /* ---------- Responsive ---------- */
        @media (max-width: 900px) {
            .stats, .stats.three { grid-template-columns: repeat(2, 1fr); }
            .grid-2 { grid-template-columns: 1fr; }
        }
        @media (max-width: 640px) {
            body { flex-direction: column; }
            .sidebar { width: 100%; border-right: none; border-bottom: 1px solid var(--border); padding: 14px 16px; }
            .brand { padding-bottom: 12px; margin-bottom: 10px; }
            nav { display: flex; flex-wrap: wrap; gap: 4px; }
            nav a { margin: 0; padding: 8px 10px; }
            .sidebar-foot { margin-top: 12px; display: flex; justify-content: space-between; align-items: center; }
            .topbar, .content { padding-left: 18px; padding-right: 18px; }
            .stats, .stats.three { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    @php
        $bisaLihatPendapatan = in_array($role, ['admin', 'owner']);
    @endphp

    <aside class="sidebar">
        <div class="brand">PARKIR<span>IN</span></div>

        <nav aria-label="Menu utama">
            <a href="{{ url('/dashboard/' . $role) }}" class="active" aria-current="page">
                <span class="dot"></span>Ringkasan
            </a>

            @if(in_array($role, ['admin', 'petugas']))
                <a href="{{ route('transaksi.index') }}"><span class="dot"></span>Transaksi</a>
            @endif

            @if(in_array($role, ['admin', 'owner']))
                <a href="{{ route('rekap.index') }}"><span class="dot"></span>Rekap Transaksi</a>
            @endif

            @if($role === 'admin')
                <a href="{{ route('log.index') }}"><span class="dot"></span>Log Aktivitas</a>
                <a href="{{ route('kendaraan.index') }}"><span class="dot"></span>Kendaraan</a>
                <a href="{{ route('area.index') }}"><span class="dot"></span>Zona &amp; Slot</a>
                <a href="{{ route('user.index') }}"><span class="dot"></span>Petugas</a>
                <a href="{{ route('tarif.index') }}"><span class="dot"></span>Tarif Parkir</a>
            @endif
        </nav>

        <div class="sidebar-foot">
            <div style="margin-bottom: 8px;">Login sebagai {{ ucfirst($role) }}</div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">Keluar Aplikasi</button>
            </form>
        </div>
    </aside>

    <div class="main">
        <div class="topbar">
            <div>
                <h1>Ringkasan Parkir</h1>
                <div class="date">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</div>
            </div>
            <div class="officer">
                <div class="avatar">{{ strtoupper(mb_substr(auth()->user()->name, 0, 2)) }}</div>
                <div>
                    <div>{{ auth()->user()->name }}</div>
                    <div style="font-size: 11px; color: var(--text-muted);">Peran: {{ ucfirst($role) }}</div>
                </div>
            </div>
        </div>

        <div class="content">

            {{-- Kartu statistik --}}
            <div class="stats {{ $bisaLihatPendapatan ? '' : 'three' }}">
                <div class="stat-card">
                    <div class="label">Total Slot</div>
                    <div class="value">{{ number_format($totalSlot, 0, ',', '.') }}</div>
                    <div class="delta">{{ $jumlahZona }} zona aktif</div>
                </div>
                <div class="stat-card">
                    <div class="label">Terisi</div>
                    <div class="value">{{ number_format($terisi, 0, ',', '.') }}</div>
                    <div class="delta">kendaraan sedang parkir</div>
                </div>
                <div class="stat-card">
                    <div class="label">Tersedia</div>
                    <div class="value">{{ number_format($tersedia, 0, ',', '.') }}</div>
                    <div class="delta">slot masih kosong</div>
                </div>

                @if($bisaLihatPendapatan)
                    <div class="stat-card">
                        <div class="label">Pendapatan Hari Ini</div>
                        <div class="value">Rp {{ number_format($pendapatan ?? 0, 0, ',', '.') }}</div>
                        @if(!is_null($persenPendapatan))
                            <div class="delta {{ $persenPendapatan >= 0 ? 'up' : 'down' }}">
                                {{ $persenPendapatan >= 0 ? '↑' : '↓' }} {{ abs($persenPendapatan) }}% dari kemarin
                            </div>
                        @else
                            <div class="delta">Belum ada data kemarin</div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="grid-2">
                {{-- Okupansi per zona --}}
                <div class="panel">
                    <h2>Okupansi per Zona</h2>
                    <div class="sub">Berdasarkan kendaraan yang sedang terparkir</div>

                    @forelse($zonaOkupansi as $z)
                        <div class="zone-row">
                            <div class="name" title="{{ $z['nama'] }}">{{ $z['nama'] }}</div>
                            <div class="bar-track"
                                 role="progressbar"
                                 aria-valuenow="{{ $z['persen'] }}" aria-valuemin="0" aria-valuemax="100"
                                 aria-label="Okupansi {{ $z['nama'] }}">
                                <div class="bar-fill {{ $z['persen'] >= 90 ? 'danger' : ($z['persen'] >= 70 ? 'warn' : '') }}"
                                     style="width: {{ $z['persen'] }}%"></div>
                            </div>
                            <div class="pct">{{ $z['isi'] }}/{{ $z['total'] }}</div>
                        </div>
                    @empty
                        <div class="empty">
                            Belum ada zona.
                            @if($role === 'admin') Tambahkan di menu Zona &amp; Slot. @endif
                        </div>
                    @endforelse
                </div>

                {{-- Transaksi terbaru --}}
                <div class="panel">
                    <h2>Transaksi Terbaru</h2>
                    <div class="sub">5 aktivitas parkir terakhir</div>

                    @if(isset($transaksiTerbaru) && $transaksiTerbaru->isNotEmpty())
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Plat</th>
                                        <th>Zona</th>
                                        <th>Masuk</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaksiTerbaru as $t)
                                        <tr>
                                            <td><span class="plate">{{ $t->plat_nomor }}</span></td>
                                            <td>{{ $t->nama_zona }}</td>
                                            <td>{{ \Carbon\Carbon::parse($t->waktu_masuk)->format('d/m H:i') }}</td>
                                            <td>
                                                <span class="status-pill {{ $t->status === 'Terparkir' ? 'parked' : 'exited' }}">
                                                    {{ $t->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty">Belum ada transaksi.</div>
                    @endif

                    @if(in_array($role, ['admin', 'petugas']))
                        <a class="link-more" href="{{ route('transaksi.index') }}">Lihat semua transaksi →</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>