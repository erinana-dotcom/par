<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kendaraan</title>
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
        
        /* FORM & INPUT MULTI KOLOM */
        .form-inline { display: grid; grid-template-columns: repeat(3, 1fr) auto; gap: 12px; align-items: end; }
        input, select { background: var(--white-paper); border: 1px solid var(--border); border-radius: 4px; padding: 10px; color: var(--text-primary); font-size: 13.5px; width: 100%; outline: none; transition: border-color 0.2s; }
        input:focus, select:focus { border-color: var(--line-yellow); }
        label { display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; text-transform: uppercase; }
        
        /* TOMBOL UTAMA */
        button.btn-add { background: var(--line-yellow); color: #FAF6EE; border: none; padding: 10px 20px; font-weight: 600; border-radius: 4px; cursor: pointer; height: 38px; transition: background 0.2s; }
        button.btn-add:hover { background: #734d2a; }
        
        /* TABEL DATA */
        table { width: 100%; border-collapse: collapse; font-size: 14px; margin-top: 10px; }
        th { text-align: left; color: var(--text-muted); padding: 14px 12px; border-bottom: 2px solid var(--border); font-size: 12px; font-weight: 700; }
        td { padding: 14px 12px; border-bottom: 1px solid rgba(199, 184, 150, 0.5); vertical-align: middle; }
        tr:hover td { background: rgba(227, 213, 188, 0.2); }
        
        /* GAYA PLAT RETRO */
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
        
        /* NOTIFIKASI SYSTEM */
        .alert { padding: 12px; border-radius: 4px; margin-bottom: 16px; font-size: 13px; background: rgba(94,112,82,0.15); color: var(--ok); border: 1px solid rgba(94,112,82,0.3); }
        .alert.error { background: rgba(156,61,46,0.15); color: var(--danger); border-color: rgba(156,61,46,0.3); }
        
        /* TOMBOL AKSI */
        .btn-action { background: var(--white-paper); border: 1px solid var(--border); color: var(--text-primary); padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: 600; margin-right: 4px; transition: all 0.2s; }
        .btn-action:hover { border-color: var(--text-primary); background: var(--asphalt-light); }
        .btn-hapus { background: var(--white-paper); border: 1px solid rgba(156,61,46,0.3); color: var(--danger); cursor: pointer; padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; transition: all 0.2s; }
        .btn-hapus:hover { background: rgba(156,61,46,0.1); border-color: var(--danger); }
        
        /* FORM EDIT INSIDE TABEL */
        .edit-row { display: none; background: rgba(227, 213, 188, 0.3); }
        .edit-row.show { display: table-row; }
        .edit-row td { padding: 16px 12px; border-bottom: 1px solid var(--border); }
    </style>
</head>
<body>
<aside class="sidebar">
  <div class="brand">PARKIR<span>IN</span></div>
  <nav><a href="/dashboard/{{ auth()->user()->role }}">⬅️ Kembali Dashboard</a></nav>
</aside>
<div class="main">
    <h2>Data Kendaraan Terdaftar</h2>
    @if(session('success')) <div class="alert">{{ session('success') }}</div> @endif
    @if($errors->any())
        <div class="alert error">{{ $errors->first() }}</div>
    @endif

    <!-- Form Tambah Data Kendaraan -->
    <div class="panel">
        <form action="{{ route('kendaraan.store') }}" method="POST" class="form-inline">
            @csrf
            <div>
                <label>Plat Nomor</label>
                <input type="text" name="plat_nomor" placeholder="misal: N 1234 ABC" required style="text-transform: uppercase;">
            </div>
            <div>
                <label>Jenis Kendaraan</label>
                <select name="jenis_kendaraan" required>
                    <option value="Mobil">Mobil</option>
                    <option value="Motor">Motor</option>
                </select>
            </div>
            <div>
                <label>Nama Pemilik</label>
                <input type="text" name="nama_pemilik" placeholder="Opsional">
            </div>
            <button type="submit" class="btn-add">Tambah Kendaraan</button>
        </form>
    </div>

    <!-- Tabel List Kendaraan -->
    <div class="panel">
        <table style="width: 100%;">
            <thead>
                <tr><th>PLAT NOMOR</th><th>JENIS</th><th>NAMA PEMILIK</th><th>AKSI OPERASI</th></tr>
            </thead>
            <tbody>
                @forelse($kendaraans as $k)
                <tr>
                    <td><span class="plate">{{ $k->plat_nomor }}</span></td>
                    <td>{{ $k->jenis_kendaraan }}</td>
                    <td><strong>{{ $k->nama_pemilik ?? '-' }}</strong></td>
                    <td>
                        <button type="button" class="btn-action" onclick="toggleEdit({{ $k->id }})">✏️ Edit</button>
                        <form action="{{ route('kendaraan.destroy', $k->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-hapus" onclick="return confirm('Yakin hapus data ini?')">🗑️ Hapus</button>
                        </form>
                    </td>
                </tr>
                
                <!-- Baris Form Edit Kendaraan -->
                <tr class="edit-row" id="edit-{{ $k->id }}">
                    <td colspan="4">
                        <form action="{{ route('kendaraan.update', $k->id) }}" method="POST" class="form-inline">
                            @csrf @method('PUT')
                            <div>
                                <label>Plat Nomor</label>
                                <input type="text" name="plat_nomor" value="{{ $k->plat_nomor }}" required style="text-transform: uppercase;">
                            </div>
                            <div>
                                <label>Jenis Kendaraan</label>
                                <select name="jenis_kendaraan" required>
                                    <option value="Mobil" {{ $k->jenis_kendaraan == 'Mobil' ? 'selected' : '' }}>Mobil</option>
                                    <option value="Motor" {{ $k->jenis_kendaraan == 'Motor' ? 'selected' : '' }}>Motor</option>
                                </select>
                            </div>
                            <div>
                                <label>Nama Pemilik</label>
                                <input type="text" name="nama_pemilik" value="{{ $k->nama_pemilik }}">
                            </div>
                            <button type="submit" class="btn-add">Simpan</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center; color: var(--text-muted); padding: 24px;">Belum ada data kendaraan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleEdit(id) {
    document.getElementById('edit-' + id).classList.toggle('show');
}
</script>
</body>
</html>
