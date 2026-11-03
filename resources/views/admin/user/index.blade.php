<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Petugas</title>
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
        nav a:hover { color: var(--line-yellow); }
        .main { flex: 1; padding: 32px; }
        .panel { background: var(--card); border: 1px solid var(--border); border-radius: 4px; padding: 24px; margin-bottom: 24px; }
        h2 { font-family: 'Barlow Condensed', sans-serif; font-size: 22px; margin-bottom: 16px; }
        .form-inline { display: grid; grid-template-columns: repeat(5, 1fr) auto; gap: 12px; align-items: end; }
        input, select { background: var(--asphalt); border: 1px solid var(--border); border-radius: 3px; padding: 10px; color: var(--text-primary); font-size: 13px; width: 100%; }
        label { display: block; font-size: 12px; color: var(--text-muted); margin-bottom: 6px; }
        button.btn-add { background: var(--line-yellow); color: #F5EEDD; border: none; padding: 10px 20px; font-weight: 600; border-radius: 3px; cursor: pointer; height: 38px; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; margin-top: 10px; }
        th { text-align: left; color: var(--text-muted); padding: 12px; border-bottom: 1px solid var(--border); font-size: 12px; }
        td { padding: 12px; border-bottom: 1px solid var(--border); }
        button.btn-del { background: none; border: none; color: var(--danger); cursor: pointer; font-size: 13px; }
        button.btn-edit { background: none; border: none; color: var(--line-yellow); cursor: pointer; font-size: 13px; margin-right: 10px; }
        .aksi-box { display: flex; align-items: center; }
        .edit-row { display: none; background: rgba(227, 213, 188, 0.3); }
        .edit-row.show { display: table-row; }
        .alert { padding: 12px; border-radius: 3px; margin-bottom: 16px; font-size: 13px; }
        .alert-success { background: rgba(94,112,82,0.15); color: var(--ok); border: 1px solid rgba(94,112,82,0.3); }
        .alert-error { background: rgba(156,61,46,0.15); color: var(--danger); border: 1px solid rgba(156,61,46,0.3); }
    </style>
</head>
<body>
<aside class="sidebar">
  <div class="brand">PARKIR<span>IN</span></div>
  <nav>
    <a href="/dashboard/{{ auth()->user()->role }}">⬅️ Kembali</a>
  </nav>
</aside>

<div class="main">
    <h2>Kelola Data User & Petugas</h2>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-error">{{ session('error') }}</div> @endif
    @if($errors->any()) <div class="alert alert-error">{{ $errors->first() }}</div> @endif

    <div class="panel">
        <h3 style="font-size: 15px; color: var(--text-muted); margin-bottom: 14px;">+ Tambah Akun Baru</h3>
        <form action="{{ route('user.store') }}" method="POST" class="form-inline">
            @csrf
            <div>
                <label>Nama Lengkap</label>
                <input type="text" name="name" placeholder="Nama Lengkap" required>
            </div>
            <div>
                <label>ID / Username</label>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" placeholder="nama@email.com" required>
            </div>
            <div>
                <label>Kata Sandi</label>
                <input type="password" name="password" placeholder="Minimal 6 karakter" required>
            </div>
            <div>
                <label>Role</label>
                <select name="role" required>
                    <option value="petugas">Petugas</option>
                    <option value="admin">Admin</option>
                    <option value="owner">Owner</option>
                </select>
            </div>
            <button type="submit" class="btn-add">Simpan Akun</button>
        </form>
    </div>

    <div class="panel">
        <table>
            <thead>
                <tr>
                    <th>NAMA</th>
                    <th>USERNAME</th>
                    <th>EMAIL</th>
                    <th>ROLE</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td><strong>{{ $user->name }}</strong></td>
                    <td><span style="background: var(--asphalt); padding: 3px 8px; border: 1px solid var(--border); border-radius: 3px;">{{ $user->username }}</span></td>
                    <td>{{ $user->email }}</td>
                    <td><span style="color: var(--line-yellow)">{{ ucfirst($user->role) }}</span></td>
                    <td>
                        <div class="aksi-box">
                            <button type="button" class="btn-edit" onclick="toggleEdit({{ $user->id }})">✏️ Edit</button>
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-del">🗑️ Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>

                <tr class="edit-row" id="edit-{{ $user->id }}">
                    <td colspan="5">
                        <form action="{{ route('user.update', $user->id) }}" method="POST" class="form-inline">
                            @csrf
                            @method('PUT')
                            <div>
                                <label>Nama Lengkap</label>
                                <input type="text" name="name" value="{{ $user->name }}" required>
                            </div>
                            <div>
                                <label>Username</label>
                                <input type="text" name="username" value="{{ $user->username }}" required>
                            </div>
                            <div>
                                <label>Email</label>
                                <input type="email" name="email" value="{{ $user->email }}" required>
                            </div>
                            <div>
                                <label>Sandi Baru (kosongkan jika tidak diganti)</label>
                                <input type="password" name="password" placeholder="Minimal 6 karakter">
                            </div>
                            <div>
                                <label>Role</label>
                                <select name="role" required>
                                    <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="owner" {{ $user->role == 'owner' ? 'selected' : '' }}>Owner</option>
                                </select>
                            </div>
                            <button type="submit" class="btn-add">Simpan</button>
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
    document.getElementById('edit-' + id).classList.toggle('show');
}
</script>
</body>
</html>