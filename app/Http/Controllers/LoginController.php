<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $rawLogin = trim($validated['login']);

        $account = Akun::where(function ($query) use ($rawLogin) {
            $query
                ->where('id_akun', strtoupper($rawLogin))
                ->orWhere('username', $rawLogin)
                ->orWhere('email', $rawLogin);
        })->first();

        if (
            !$account
            || !Hash::check(
                $validated['password'],
                $account->password_hash
            )
        ) {
            return back()
                ->withErrors([
                    'login' =>
                        'ID akun, username/email, atau password salah.',
                ])
                ->onlyInput('login');
        }

        if ($account->status_akun !== 'AKTIF') {
            return back()
                ->withErrors([
                    'login' =>
                        'Akun sedang NONAKTIF. Hubungi Admin.',
                ])
                ->onlyInput('login');
        }

        Auth::login($account);
        $request->session()->regenerate();

        return match ($account->tipe_akun) {
            'CUSTOMER' => redirect()->route('customer.dashboard'),
            'KASIR' => redirect()->route('kasir.dashboard'),
            'ADMIN' => redirect()->route('admin.dashboard'),
            default => abort(403),
        };
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'username' =>
                'required|string|max:100|unique:akun,username',
            'email' =>
                'nullable|email|max:255|unique:akun,email',
            'no_telp' => 'required|string|max:30',
            'alamat' => 'nullable|string|max:1000',
            'password' =>
                'required|string|min:6|confirmed',
        ]);

        $account = DB::transaction(function () use ($validated) {
            $lastId = Akun::where('id_akun', 'like', 'CUST%')
                ->orderByDesc('id_akun')
                ->value('id_akun');

            $number = $lastId
                ? ((int) substr($lastId, 4)) + 1
                : 1;

            return Akun::create([
                'id_akun' =>
                    'CUST'.str_pad($number, 3, '0', STR_PAD_LEFT),
                'nama' => $validated['nama'],
                'username' => $validated['username'],
                'password_hash' => Hash::make($validated['password']),
                'email' => $validated['email'] ?? null,
                'no_telp' => $validated['no_telp'],
                'alamat' => $validated['alamat'] ?? null,
                'tipe_akun' => 'CUSTOMER',
                'status_akun' => 'AKTIF',
            ]);
        });

        Auth::login($account);
        $request->session()->regenerate();

        return redirect()
            ->route('customer.dashboard')
            ->with(
                'success',
                'Registrasi berhasil. Selamat datang di FORMA.'
            );
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
