<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operasional Transaksi Parkir</title>
    @vite('resources/css/app.css')
    <style>
        :root {
            --asphalt: #EDE4D3; --asphalt-light: #E3D5BC; --card: #F5EEDD;
            --line-yellow: #8B5E34; --text-primary: #3E2E1F; --text-muted: #7A6852;
            --border: #C9B896; --danger: #9C3D2E; --ok: #5E7052;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--asphalt); font-family: 'Inter', sans-serif; color: var(--text-primary); display: flex; min-height: 100vh; }
        .sidebar { width: 220px; background: var(--asphalt-light); border-right: 1px solid var(--border); padding: 24px 18px; }
        .brand { font-family: 'Barlow Condensed', sans-serif; font-weight: 700; font-size: 21px; padding-bottom: 24px; border-bottom: 1px solid var(--border); margin-bottom: 18px; }
        .brand span { color: var(--line-yellow); }
        nav a { display: flex; align-items: center; gap: 10px; color: var(--text-muted); text-decoration: none; font-size: 14px; padding: 10px 12px; }
        .main { flex: 1; padding: 32px; }
        .panel { background: var(--card); border: 1px solid var(--border); border-radius: 4px; padding: 24px; margin-bottom: 24px; }
        h2 { font-family: 'Barlow Condensed', sans-serif; font-size: 22px; margin-bottom: 16px; }
        .form-inline { display: grid; grid-template-columns: repeat(3, 1fr) auto; gap: 12px; align-items: end; }
        input, select { background: var(--asphalt); border: 1px solid var(--border); border-radius: 3px; padding: 10px; color: var(--text-primary); font-size: 13px; width: 100%; }
        label { display: block; font-size: 12px; color: var(--text-muted); margin-bottom: 6px; }
        button.btn-add { background: var(--line-yellow); color: #F5EEDD; border: none; padding: 10px 20px; font-weight: 600; border-radius: 3px; cursor: pointer; height: 38px; }
        table { width: 100%; border-collapse: collapse; font-size: 13.5px; margin-top: 10px; }
        th { text-align: left; color: var(--text-muted); padding: 12px; border-bottom: 1px solid var(--border); font-size: 12px; }
        td { padding: 12px; border-bottom: 1px solid var(--border); }
        .plate { font-family: 'Barlow Condensed', sans-serif; font-weight: 700; background: var(--asphalt); border: 1px solid var(--border); border-radius: 3px; padding: 3px 8px; display: inline-block; font-size: 13px; }
        .status-pill { font-size: 11px; font-weight: 600; padding: 3px 9px; border-radius: 20px; display: inline-block; }
        .status-pill.parked { background: rgba(94,112,82,0.16); color: var(--ok); }
        .status-pill.exited { background: rgba(122,104,82,0.16); color: var(--text-muted); }
        .alert { padding: 12px; border-radius: 3px; margin-bottom: 16px; font-size: 13px; background: rgba(94,112,82,0.15); color: var(--ok); border: 1px solid rgba(94,112,82,0.3); }
        .btn-action { background: none; border: 1px solid var(--border); color: var(--text-primary); padding: 5px 10px; border-radius: 3px; cursor: pointer; font-size: 12px; margin-right: 4px; }
        .btn-action:hover { border-color: var(--line-yellow); color: var(--line-yellow); }
    </style>
</head>
<body>
<aside class="sidebar">
  <div class="brand">PARKIR<span>IN</span></div>
  <nav><a href="/dashboard/{{ auth()->user()->role }}">⬅️ Kembali</a></nav>
</aside>
<div class="main">
    <h2>Operasional Transaksi & Kendaraan</h2>
   @if(session('error')) <div class="alert" style="background: rgba(156,61,46,0.15); color: var(--danger); border-color: rgba(156,61,46,0.3);">{{ session('error') }}</div> @endif

    <!-- Form Input Kendaraan Masuk -->
    <div class="panel">
        <h3 style="font-size: 14px; color: var(--text-muted); margin-bottom: 12px;">📥 Catat Kendaraan Masuk (Check-In)</h3>
        <form action="{{ route('transaksi.store') }}" method="POST" class="form-inline">
            @csrf
            <div>
                <label>Nomor Plat Kendaraan</label>
                <input type="text" name="plat_nomor" placeholder="misal: N 1234 ABC" required style="text-transform: uppercase;">
            </div>
            <div>
                <label>Jenis Kendaraan</label>
                <select name="jenis_kendaraan" required>
                    @foreach($tarifs as $tarif)
                        <option value="{{ $tarif->jenis_kendaraan }}">{{ $tarif->jenis_kendaraan }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Pilih Zona Tempat Parkir</label>
                <select name="nama_zona" required>
                    @foreach($areas as $area)
                        <option value="{{ $area->nama_zona }}">{{ $area->nama_zona }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-add">Check-In Masuk</button>
        </form>
    </div>

    <!-- Tabel Daftar Kendaraan Aktif -->
    <div class="panel">
        <table>
            <thead>
                <tr>
                    <th>PLAT NOMOR</th>
                    <th>JENIS</th>
                    <th>ZONA AREA</th>
                    <th>WAKTU MASUK</th>
                    <th>WAKTU KELUAR</th>
                    <th>TOTAL BIAYA</th>
                    <th>STATUS</th>
                    <th>AKSI OPERASI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksis as $trx)
                <tr>
                    <td><span class="plate">{{ $trx->plat_nomor }}</span></td>
                    <td>{{ $trx->jenis_kendaraan }}</td>
                    <td>{{ $trx->nama_zona }}</td>
                    <td>{{ date('H:i:s / d M', strtotime($trx->waktu_masuk)) }}</td>
                    <td>{{ $trx->waktu_keluar ? date('H:i:s / d M', strtotime($trx->waktu_keluar)) : '-' }}</td>
                    <td><span style="color: var(--line-yellow)">{{ $trx->total_biaya ? 'Rp '.number_format($trx->total_biaya) : '-' }}</span></td>
                    <td><span class="status-pill {{ $trx->status == 'Terparkir' ? 'parked' : 'exited' }}">{{ $trx->status }}</span></td>
                    <td>
                        @if($trx->status == 'Terparkir')
                            <form action="{{ route('transaksi.checkout', $trx->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-action" style="color: var(--danger);">🚗 Out / Bayar</button>
                            </form>
                        @endif
                        <a href="{{ route('transaksi.struk', $trx->id) }}" target="_blank" class="btn-action">🖨️ Karcis</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>