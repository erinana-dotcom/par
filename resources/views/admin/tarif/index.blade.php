<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Tarif</title>
    @vite('resources/css/app.css')
    <style>
        /* MENGUBAH VARIABEL WARNA MENJADI VINTAGE SEPIA */
        :root {
            --asphalt: #EDE4D3;          /* Latar belakang luar (krem) */
            --asphalt-light: #E3D5BC;    /* Sidebar (krem sedikit lebih gelap) */
            --card: #F5EEDD;             /* Kotak panel (krem terang) */
            --white-paper: #FAF6EE;      /* Latar input & tabel */
            --line-yellow: #8B5E34;      /* Warna utama / aksen (cokelat tanah) */
            --text-primary: #3E2E1F;     /* Tulisan utama (cokelat gelap) */
            --text-muted: #7A6852;       /* Tulisan sekunder (cokelat pudar) */
            --border: #C9B896;           /* Garis pembatas */
            --danger: #9C3D2E;           /* Warna tombol hapus/error (merah bata) */
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
        h2 { font-family: 'Barlow Condensed', sans-serif; font-size: 24px; margin-bottom: 16px; }
        
        /* FORM & INPUT */
        .form-inline { display: grid; grid-template-columns: repeat(2, 1fr) auto; gap: 12px; align-items: end; }
        input, select { background: var(--white-paper); border: 1px solid var(--border); border-radius: 4px; padding: 10px; color: var(--text-primary); font-size: 13.5px; width: 100%; outline: none; transition: border-color 0.2s; }
        input:focus, select:focus { border-color: var(--line-yellow); }
        label { display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; text-transform: uppercase; }
        
        /* BUTTONS */
        button.btn-add { background: var(--line-yellow); color: #FAF6EE; border: none; padding: 10px 20px; font-weight: 600; border-radius: 4px; cursor: pointer; height: 38px; transition: background 0.2s; }
        button.btn-add:hover { background: #734d2a; }
        
        /* TABLE STYLING */
        table { width: 100%; border-collapse: collapse; font-size: 14px; margin-top: 10px; }
        th { text-align: left; color: var(--text-muted); padding: 14px 12px; border-bottom: 2px solid var(--border); font-size: 12px; font-weight: 700; }
        td { padding: 14px 12px; border-bottom: 1px solid rgba(199, 184, 150, 0.5); vertical-align: middle; }
        tr:hover td { background: rgba(227, 213, 188, 0.2); }
        
        /* ACTIONS & ALERTS */
        .alert { padding: 12px; border-radius: 4px; margin-bottom: 16px; font-size: 13px; background: rgba(94,112,82,0.15); color: var(--ok); border: 1px solid rgba(94,112,82,0.3); }
        .btn-action { background: var(--white-paper); border: 1px solid var(--border); color: var(--text-primary); padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: 600; margin-right: 4px; transition: all 0.2s; }
        .btn-action:hover { border-color: var(--text-primary); background: var(--asphalt-light); }
        .btn-hapus { background: var(--white-paper); border: 1px solid rgba(156,61,46,0.3); color: var(--danger); cursor: pointer; padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; transition: all 0.2s; }
        .btn-hapus:hover { background: rgba(156,61,46,0.1); border-color: var(--danger); }
        
        /* ROW EDIT MODE */
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
    <h2>Kelola Tarif Parkir</h2>
    @if(session('success')) <div class="alert">{{ session('success') }}</div> @endif
    
    <!-- Form Tambah Tarif -->
    <div class="panel">
        <form action="{{ route('tarif.store') }}" method="POST" class="form-inline">
            @csrf
            <div>
                <label>Jenis Kendaraan</label>
                <select name="jenis_kendaraan" required>
                    <option value="Mobil">Mobil</option>
                    <option value="Motor">Motor</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            <div>
                <label>Tarif per Jam (Rp)</label>
                <input type="number" name="tarif_per_jam" placeholder="Contoh: 3000" required>
            </div>
            <button type="submit" class="btn-add">Simpan Tarif</button>
        </form>
    </div>

    <!-- Tabel Daftar Tarif -->
    <div class="panel">
        <table>
            <thead>
                <tr><th>JENIS KENDARAAN</th><th>TARIF PER JAM</th><th>AKSI OPERASI</th></tr>
            </thead>
            <tbody>
                @foreach($tarifs as $tarif)
                <tr id="row-{{ $tarif->id }}">
                    <td><strong>{{ $tarif->jenis_kendaraan }}</strong></td>
                    <td><span style="color: var(--line-yellow); font-weight: 700;">Rp {{ number_format($tarif->tarif_per_jam, 0, ',', '.') }}</span></td>
                    <td>
                        <button type="button" class="btn-action" onclick="toggleEdit({{ $tarif->id }})">✏️ Edit</button>
                        <form action="{{ route('tarif.destroy', $tarif->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-hapus" onclick="return confirm('Yakin hapus tarif ini?')">🗑️ Hapus</button>
                        </form>
                    </td>
                </tr>
                
                <!-- Baris Form Edit (Muncul saat tombol Edit diklik) -->
                <tr class="edit-row" id="edit-{{ $tarif->id }}">
                    <td colspan="3">
                        <form action="{{ route('tarif.update', $tarif->id) }}" method="POST" class="form-inline">
                            @csrf
                            @method('PUT')
                            <div>
                                <label>Jenis Kendaraan</label>
                                <select name="jenis_kendaraan" required>
                                        <option value="Mobil" {{ $tarif->jenis_kendaraan == 'Mobil' ? 'selected' : '' }}>Mobil</option>
                                        <option value="Motor" {{ $tarif->jenis_kendaraan == 'Motor' ? 'selected' : '' }}>Motor</option>
                                        <option value="Lainnya" {{ $tarif->jenis_kendaraan == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label>Tarif per Jam (Rp)</label>
                                <input type="number" name="tarif_per_jam" value="{{ $tarif->tarif_per_jam }}" required>
                            </div>
                            <button type="submit" class="btn-add">Simpan Perubahan</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleEdit(id) {
    const row = document.getElementById('edit-' + id);
    row.classList.toggle('show');
}
</script>
</body>
</html>
