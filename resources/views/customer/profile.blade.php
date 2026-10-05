@extends('layouts.customer')
@section('title','Profil')
@section('content')
    <div class="page-head">
        <div>
            <h1>Profil</h1>
            <p class="muted">ID Customer: {{ $account->id_akun }}</p>
        </div>
    </div>
    <form class="card" method="POST" action="{{ route('customer.profile.update') }}">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div>
                <label>Nama</label>
                <input name="nama" value="{{ old('nama',$account->nama) }}">
            </div>
            <div>
                <label>Username</label>
                <input name="username" value="{{ old('username',$account->username) }}">
            </div>
            <div>
                <label>Email</label>
                <input name="email" value="{{ old('email',$account->email) }}">
            </div>
            <div>
                <label>No. Telp</label>
                <input name="no_telp" value="{{ old('no_telp',$account->no_telp) }}">
            </div>
            <div class="full">
                <label>Alamat</label>
                <textarea name="alamat">{{ old('alamat',$account->alamat) }}</textarea>
            </div>
            <div>
                <label>Password Baru (opsional)</label>
                <input type="password" name="password">
            </div>
            <div>
                <label>Konfirmasi</label>
                <input type="password" name="password_confirmation">
            </div>
        </div>
        <button class="btn" style="margin-top:18px">Simpan Profil</button>
    </form>
@endsection
