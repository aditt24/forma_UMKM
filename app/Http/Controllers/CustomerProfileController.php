<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerProfileController extends Controller
{
    public function edit()
    {
        return view('customer.profile', [
            'account' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $customerId = Auth::id();

        $validated = $request->validate([
            'nama' => 'required|max:255',
            'username' =>
                'required|max:100|unique:akun,username,'
                .$customerId.',id_akun',
            'email' =>
                'nullable|email|unique:akun,email,'
                .$customerId.',id_akun',
            'no_telp' => 'required|max:30',
            'alamat' => 'nullable|max:1000',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $data = [
            'nama' => $validated['nama'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'no_telp' => $validated['no_telp'],
            'alamat' => $validated['alamat'] ?? null,
            'updated_at' => now(),
        ];

        if (!empty($validated['password'])) {
            $data['password_hash'] = Hash::make(
                $validated['password']
            );
        }

        DB::table('akun')
            ->where('id_akun', $customerId)
            ->update($data);

        return back()->with('success', 'Profil diperbarui.');
    }
}
