<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    /**
     * Cek peran pengguna. Contoh pemakaian di route: CekRole::class.':admin,petugas'
     *
     * Kalau peran tidak cocok, pengguna dikembalikan ke dashboard miliknya sendiri
     * (bukan ditolak dengan halaman 403), dengan pesan pemberitahuan.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // Belum login: arahkan ke halaman masuk
        if (! $user) {
            return redirect()->route('login');
        }

        // Peran cocok: lanjutkan
        if (in_array($user->role, $roles, true)) {
            return $next($request);
        }

        // Peran dikenal tapi bukan haknya: kembalikan ke dashboard sendiri
        if (in_array($user->role, ['admin', 'petugas', 'owner'], true)) {
            return redirect('/dashboard/' . $user->role)
                ->with('error', 'Anda tidak punya akses ke halaman tersebut, jadi dikembalikan ke dashboard Anda.');
        }

        // Peran tidak dikenal: tolak
        abort(403, 'Anda tidak punya akses ke halaman ini.');
    }
}