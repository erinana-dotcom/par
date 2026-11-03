<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Area Parkir</title>
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
            --danger: #9C3D2E;           /* Warna error/hapus (merah bata) */
            --ok: #5E7052;               /* Warna sukses (hijau zaitun pudar) */
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
        .panel { background: var(--card); border: 1px solid var(--border); border-radius: 6px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 6px rgba(62, 46, 31, 0.03); }
        h2 { font-family: 'Barlow Condensed', sans-serif; font-size: 22px; margin-bottom: 16px; }
        
        /* FORM & INPUT */
        .form-inline { display: grid; grid-template-columns: repeat(2, 1fr) auto; gap: 12px; align-items: end; }
        input { background: var(--white-paper); border: 1px solid var(--border); border-radius: 4px; padding: 10px; color: var(--text-primary); font-size: 13.5px; width: 100%; outline: none; transition: border-color 0.2s; }
        input:focus { border-color: var(--line-yellow); }
        label { display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; text-transform: uppercase; }
        
        /* BUTTONS */
        button.btn-add { background: var(--line-yellow); color: #FAF6EE; border: none; padding: 10px 20px; font-weight: 600; border-radius: 4px; cursor: pointer; height: 38px; transition: background 0.2s; }
        button.btn-add:hover { background: #734d2a; }
        
        /* TABEL DATA */
        table { width: 100%; border-collapse: collapse; font-size: 14px; margin-top: 10px; }
        th { text-align: left; color: var(--text-muted); padding: 14px 12px; border-bottom: 2px solid var(--border); font-size: 12px; font-weight: 700; }
        td { padding: 14px 12px; border-bottom: 1px solid rgba(199, 184, 150, 0.5); vertical-align: middle; }
        tr:hover td { background: rgba(227, 213, 188, 0.2); }
        
        /* NOTIFIKASI */
        .alert { padding: 12px; border-radius: 4px; margin-bottom: 16px; font-size: 13px; background: rgba(94,112,82,0.15); color: var(--ok); border: 1px solid rgba(94,112,82,0.3); }
        
        /* TOMBOL HAPUS RETRO */
        .btn-hapus { background: var(--white-paper); border: 1px solid rgba(156,61,46,0.3); color: var(--danger); cursor: pointer; padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; transition: all 0.2s; }
        .btn-hapus:hover { background: rgba(156,61,46,0.1); border-color: var(--danger); }
    </style>
</head>
<body>
<aside class="sidebar">
  <div class="brand">PARKIR<span>IN</span></div>
  <nav><a href="/dashboard/{{ auth()->user()->role }}">⬅️ Kembali Dashboard</a></nav>
</aside>
<div class="main">
    <h2>Kelola Area & Slot Parkir</h2>
    @if(session('success')) <div class="alert">{{ session('success') }}</div> @endif
    
    <!-- Form Tambah Zona -->
    <div class="panel">
        <form action="{{ route('area.store') }}" method="POST" class="form-inline">
            @csrf
            <div>
                <label>Nama Zona</label>
                <input type="text" name="nama_zona" placeholder="Contoh: Zona A" required>
            </div>
            <div>
                <label>Total Kapasitas Slot</label>
                <input type="number" name="total_slot" placeholder="Contoh: 60" required>
            </div>
            <button type="submit" class="btn-add">Simpan Zona</button>
        </form>
    </div>

    <!-- Tabel Daftar Zona -->
    <div class="panel">
        <table style="width: 100%;">
            <thead>
                <tr><th>NAMA ZONA</th><th>TOTAL SLOT KAPASITAS</th><th>AKSI OPERASI</th></tr>
            </thead>
            <tbody>
                @foreach($areas as $area)
                <tr>
                    <td><strong>{{ $area->nama_zona }}</strong></td>
                    <td><span style="color: var(--line-yellow); font-weight: 700;">{{ $area->total_slot }} Kendaraan</span></td>
                    <td>
                        <form action="{{ route('area.destroy', $area->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-hapus" onclick="return confirm('Yakin hapus area ini?')">🗑️ Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
