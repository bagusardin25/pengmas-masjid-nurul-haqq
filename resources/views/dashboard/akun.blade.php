@extends('layouts.dashboard')

@section('title', 'Akun & Admin')
@section('breadcrumb', 'Akun & Admin')

@php
    $sections = [
        'profil' => 'Profil saya',
        'password' => 'Ganti password',
        'admin' => 'Daftar admin',
    ];
@endphp

@section('content')
    <div class="dash-page">
        <header class="dash-page-header">
            <div>
                <p class="dash-eyebrow">Pengaturan</p>
                <h1>Akun &amp; Admin</h1>
                <p>Ubah nama, email, dan password Anda, serta kelola siapa saja yang bisa masuk ke dashboard. Pendaftaran umum ditutup: admin baru hanya bisa ditambahkan dari halaman ini.</p>
            </div>
        </header>

        <x-dashboard.status-toast />

        <nav class="dash-section-nav" aria-label="Bagian halaman akun">
            @foreach ($sections as $anchor => $sectionLabel)
                <a href="#{{ $anchor }}">{{ $sectionLabel }}</a>
            @endforeach
        </nav>

        {{-- Profil saya --}}
        <section id="profil" class="dash-panel" aria-labelledby="profil-title">
            <header class="dash-panel-header">
                <h2 id="profil-title">Profil saya</h2>
                <p>Email ini dipakai untuk masuk ke dashboard.</p>
            </header>

            <form method="POST" action="{{ route('profile.update') }}" class="dash-form">
                @csrf
                @method('PATCH')
                <div class="dash-field-row">
                    <x-dashboard.field bag="profile" name="name" label="Nama" :value="$user->name" required />
                    <x-dashboard.field bag="profile" name="email" label="Email" type="email" :value="$user->email" required />
                </div>
                <div class="dash-form-actions">
                    <button type="submit" class="dash-button dash-button-primary">Simpan profil</button>
                </div>
            </form>
        </section>

        {{-- Ganti password --}}
        <section id="password" class="dash-panel" aria-labelledby="password-title">
            <header class="dash-panel-header">
                <h2 id="password-title">Ganti password</h2>
                <p>Gunakan minimal 8 karakter dan jangan memakai password yang sama dengan akun lain.</p>
            </header>

            <form method="POST" action="{{ route('password.update') }}" class="dash-form">
                @csrf
                @method('PUT')
                <div class="dash-field-row">
                    <x-dashboard.field bag="updatePassword" name="current_password" label="Password saat ini" type="password" required />
                    <x-dashboard.field bag="updatePassword" name="password" label="Password baru" type="password" required />
                    <x-dashboard.field bag="updatePassword" name="password_confirmation" label="Ulangi password baru" type="password" required />
                </div>
                <div class="dash-form-actions">
                    <button type="submit" class="dash-button dash-button-primary">Ganti password</button>
                </div>
            </form>
        </section>

        {{-- Daftar admin --}}
        <section id="admin" class="dash-panel" aria-labelledby="admin-title">
            <header class="dash-panel-header">
                <h2 id="admin-title">Daftar admin</h2>
                <p>Semua admin bisa mengubah seluruh isi situs. Hapus akun pengurus yang sudah tidak bertugas.</p>
            </header>

            <ul class="dash-mini-list dash-admin-list">
                @foreach ($admins as $admin)
                    <li>
                        <span class="dash-date-chip dash-admin-avatar" aria-hidden="true">{{ strtoupper(substr($admin->name, 0, 2)) }}</span>
                        <span>
                            <strong>{{ $admin->name }}@if ($admin->is($user)) <span class="dash-status">Anda</span>@endif</strong>
                            <small>{{ $admin->email }} · ditambahkan {{ $admin->created_at?->locale('id')->translatedFormat('j F Y') }}</small>
                        </span>
                        @unless ($admin->is($user))
                            <form method="POST" action="{{ route('dashboard.admins.destroy', $admin) }}"
                                onsubmit="return confirm(@js('Hapus admin '.$admin->name.'? Akun ini tidak bisa masuk ke dashboard lagi.'))">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dash-text-button dash-text-button-danger" aria-label="Hapus admin {{ $admin->name }}">Hapus</button>
                            </form>
                        @endunless
                    </li>
                @endforeach
            </ul>

            <form method="POST" action="{{ route('dashboard.admins.store') }}" class="dash-add">
                @csrf
                <h3>Tambah admin</h3>
                <div class="dash-item-form dash-grid-admin">
                    <x-dashboard.field bag="admin" name="name" label="Nama" placeholder="Gatot Bambang" required />
                    <x-dashboard.field bag="admin" name="email" label="Email" type="email" placeholder="nama@gmail.com" required />
                    <x-dashboard.field bag="admin" name="password" label="Password" type="password" required hint="Minimal 8 karakter." />
                    <x-dashboard.field bag="admin" name="password_confirmation" label="Ulangi password" type="password" required />
                    <x-dashboard.item-actions submit-label="Tambah admin" class="dash-actions-row" />
                </div>
            </form>
        </section>
    </div>
@endsection
