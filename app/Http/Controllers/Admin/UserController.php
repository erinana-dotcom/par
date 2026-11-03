<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private const ROLES = ['admin', 'petugas', 'owner'];

    // 1. Menampilkan halaman daftar user (urut dari role lalu nama)
    public function index()
    {
        $users = User::orderBy('role')->orderBy('name')->get();
        return view('admin.user.index', compact('users'));
    }

    // 2. Menyimpan akun baru dari formulir input
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|alpha_dash|max:255|unique:users,username',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => ['required', Rule::in(self::ROLES)],
        ], $this->pesan());

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan!');
    }

    // 3. Mengubah data akun (password hanya diganti kalau diisi)
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => ['required', 'string', 'alpha_dash', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'role'     => ['required', Rule::in(self::ROLES)],
        ], $this->pesan());

        // Admin tidak boleh menurunkan role akunnya sendiri (supaya tidak terkunci)
        if (auth()->id() == $user->id && $data['role'] !== 'admin') {
            return redirect()->route('user.index')
                ->with('error', 'Anda tidak bisa mengubah role akun Anda sendiri!');
        }

        // Password hanya diganti kalau diisi
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui!');
    }

    // 4. Menghapus akun
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Mencegah admin menghapus akunnya sendiri yang sedang dipakai login
        if (auth()->id() == $user->id) {
            return redirect()->route('user.index')
                ->with('error', 'Anda tidak bisa menghapus akun Anda sendiri!');
        }

        // Pastikan selalu ada minimal satu admin
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return redirect()->route('user.index')
                ->with('error', 'Admin terakhir tidak boleh dihapus!');
        }

        try {
            $user->delete();
        } catch (QueryException $e) {
            // Terjadi kalau akun sudah punya data transaksi/log yang terhubung
            return redirect()->route('user.index')
                ->with('error', 'Akun ini tidak bisa dihapus karena sudah punya data terkait.');
        }

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus!');
    }

    // Pesan error dalam Bahasa Indonesia
    private function pesan(): array
    {
        return [
            'name.required'     => 'Nama wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username sudah dipakai.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, strip, dan garis bawah.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email sudah dipakai.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
            'role.required'     => 'Role wajib dipilih.',
            'role.in'           => 'Role tidak valid.',
        ];
    }
}