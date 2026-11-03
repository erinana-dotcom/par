<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // SESUAIKAN dengan hasil `php artisan route:list`
    private const HALAMAN_AWAL = [
    'admin'   => '/dashboard/admin',
    'petugas' => '/dashboard/petugas',
    'owner'   => '/dashboard/owner',
];

    public function index()
    {
        // Kalau sudah login, tidak perlu lihat halaman login lagi
        if (Auth::check()) {
            return $this->arahkanBerdasarkanRole();
        }

        return view('index');
    }

    public function loginProses(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'ID Petugas wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $kredensial = $request->only('username', 'password');

        if (Auth::attempt($kredensial, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Role tidak dikenal -> tolak, jangan biarkan nyangkut di halaman 404
            if (! array_key_exists($user->role, self::HALAMAN_AWAL)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()
                    ->with('error', 'Role akun tidak valid. Hubungi admin.')
                    ->withInput($request->only('username'));
            }

            $this->catatLog($user->id, $user->name, 'Login',
                "User {$user->name} ({$user->role}) berhasil login ke sistem");

            return $this->arahkanBerdasarkanRole();
        }

        $this->catatLog(null, $request->username, 'Login Gagal',
            "Percobaan login gagal dengan username: {$request->username}");

        // Hanya kirim ulang username, password tidak ikut dikembalikan
        return back()
            ->with('error', 'ID Petugas atau kata sandi salah. Coba lagi.')
            ->withInput($request->only('username'));
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user) {
            $this->catatLog($user->id, $user->name, 'Logout',
                "User {$user->name} ({$user->role}) logout dari sistem");
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function arahkanBerdasarkanRole(): RedirectResponse
    {
        // intended() = kembali ke halaman yang tadinya dituju sebelum diminta login
        return redirect()->intended(self::HALAMAN_AWAL[Auth::user()->role]);
    }

    private function catatLog(?int $userId, string $nama, string $aktivitas, string $keterangan): void
    {
        ActivityLog::create([
            'user_id'    => $userId,
            'nama_user'  => $nama,
            'aktivitas'  => $aktivitas,
            'keterangan' => $keterangan,
        ]);
    }
}