<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas</title>
    @vite('resources/css/app.css')
    <style>
        /* MENGUBAH VARIABEL WARNA MENJADI VINTAGE SEPIA */
        :root {
            --asphalt: #EDE4D3;          /* Latar belakang luar (krem) */
            --asphalt-light: #E3D5BC;    /* Sidebar (krem sedikit lebih gelap) */
            --card: #F5EEDD;             /* Kotak panel utama (krem terang) */
            --white-paper: #FAF6EE;      /* Latar input & tabel */
            --line-yellow: #8B5E34;      /* Warna aksen utama (cokelat tanah) */
            --text-primary: #3E2E1F;     /* Tulisan utama (cokelat gelap) */
            --text-muted: #7A6852;       /* Tulisan sekunder (cokelat pudar) */
            --border: #C9B896;           /* Garis pembatas */
            --danger: #9C3D2E;           /* Warna error/gagal (merah bata) */
            --ok: #5E7052;               /* Warna sukses/login (hijau zaitun pudar) */
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--asphalt); font-family: 'Inter', sans-serif; color: var(--text-primary); display: flex; min-height: 100vh; }
        
        /* SIDEBAR */
        .sidebar { width: 220px; background: var(--asphalt-light); border-right: 1px solid var(--border); padding: 24px 18px; }
        .brand { font-family: 'Barlow Condensed', sans-serif; font-weight: 700; font-size: 21px; padding-bottom: 24px; border-bottom: 2px dashed var(--border); margin-bottom: 18px; }
        .brand span { color: var(--line-yellow); }
        nav a { display: flex; align-items: center; gap: 10px; color: var(--text-primary); font-weight: 600; text-decoration: none; font-size: 14px; padding: 10px 12px; border-radius: 4px; transition: all 0.2s; }
        nav a:hover { background: var(--asphalt); color: var(--line-yellow); }
        
        /* MAIN CONTENT & PANEL */
        .main { flex: 1; padding: 32px; }
        .panel { background: var(--card); border: 1px solid var(--border); border-radius: 6px; padding: 24px; box-shadow: 0 4px 6px rgba(62, 46, 31, 0.03); }
        h2 { font-family: 'Barlow Condensed', sans-serif; font-size: 22px; margin-bottom: 16px; }
        
        /* TABLE STYLING */
        table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        th { text-align: left; color: var(--text-muted); padding: 14px 12px; border-bottom: 2px solid var(--border); font-size: 12px; font-weight: 700; }
        td { padding: 14px 12px; border-bottom: 1px solid rgba(199, 184, 150, 0.5); vertical-align: middle; }
        tr:hover td { background: rgba(227, 213, 188, 0.2); }
        
        /* RETRO TAGS SYSTEM */
        .tag { font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 4px; display: inline-block; text-transform: uppercase; letter-spacing: 0.5px; }
        .tag.login { background: rgba(94, 112, 82, 0.15); color: var(--ok); border: 1px solid rgba(94, 112, 82, 0.3); }
        .tag.logout { background: rgba(122, 104, 82, 0.15); color: var(--text-muted); border: 1px solid rgba(122, 104, 82, 0.3); }
        .tag.gagal { background: rgba(156, 61, 46, 0.15); color: var(--danger); border: 1px solid rgba(156, 61, 46, 0.3); }
        .tag.checkin { background: rgba(139, 94, 52, 0.12); color: var(--line-yellow); border: 1px solid rgba(139, 94, 52, 0.25); }
        .tag.checkout { background: rgba(139, 94, 52, 0.12); color: var(--line-yellow); border: 1px solid rgba(139, 94, 52, 0.25); }
        
        /* PAGINATION RETRO STYLE */
        .pagination { margin-top: 20px; padding-top: 15px; border-top: 1px dashed var(--border); display: flex; justify-content: flex-end; }
        .pagination a, .pagination span { color: var(--text-primary); font-weight: 600; text-decoration: none; padding: 6px 12px; font-size: 13px; background: var(--white-paper); border: 1px solid var(--border); border-radius: 4px; margin-left: 4px; transition: all 0.2s; }
        .pagination a:hover { border-color: var(--text-primary); background: var(--asphalt-light); color: var(--line-yellow); }
    </style>
</head>
<body>
<aside class="sidebar">
  <div class="brand">PARKIR<span>IN</span></div>
  <nav><a href="/dashboard/{{ auth()->user()->role }}">⬅️ Kembali Dashboard</a></nav>
</aside>
<div class="main">
    <h2>Log Aktivitas Sistem</h2>
    <div class="panel">
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th>WAKTU</th>
                    <th>USER</th>
                    <th>AKTIVITAS</th>
                    <th>KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td><span style="font-family: monospace;">{{ $log->created_at->format('d M Y, H:i:s') }}</span></td>
                    <td><strong>{{ $log->nama_user ?? '-' }}</strong></td>
                    <td>
                        @php
                            $cls = match(true) {
                                str_contains($log->aktivitas, 'Login Gagal') => 'gagal',
                                str_contains($log->aktivitas, 'Login') => 'login',
                                str_contains($log->aktivitas, 'Logout') => 'logout',
                                str_contains($log->aktivitas, 'Check-In') => 'checkin',
                                str_contains($log->aktivitas, 'Check-Out') => 'checkout',
                                default => 'logout',
                            };
                        @endphp
                        <span class="tag {{ $cls }}">{{ $log->aktivitas }}</span>
                    </td>
                    <td>{{ $log->keterangan }}</td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center; color: var(--text-muted); padding: 24px;">Belum ada aktivitas tercatat.</td></tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Wadah Navigasi Halaman -->
        <div class="pagination">
            {{ $logs->links() }}
        </div>
    </div>
</div>
</body>
</html>
