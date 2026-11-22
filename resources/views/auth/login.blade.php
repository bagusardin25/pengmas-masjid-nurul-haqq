<x-guest-layout title="Masuk Pengelola">
    <header class="auth-heading">
        <p class="auth-eyebrow">Panel pengelola</p>
        <h1>Masuk ke dashboard</h1>
        <p>Khusus pengurus Masjid Nurul Haqq untuk mengelola isi situs.</p>
    </header>

    @if (session('status'))
        <p class="auth-alert auth-alert-success" role="status">{{ session('status') }}</p>
    @endif

    @if ($errors->any())
        <p class="auth-alert auth-alert-error" role="alert">{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="{{ route('login') }}" class="auth-form" x-data="{ show: false, busy: false }" @submit="busy = true">
        @csrf

        <div class="auth-field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                placeholder="nama@gmail.com" @error('email') aria-invalid="true" aria-describedby="login-error" @enderror>
        </div>

        <div class="auth-field">
            <label for="password">Password</label>
            <div class="auth-password">
                <input id="password" :type="show ? 'text' : 'password'" type="password" name="password" required autocomplete="current-password">
                <button type="button" class="auth-password-toggle" @click="show = !show"
                    :aria-label="show ? 'Sembunyikan password' : 'Tampilkan password'" aria-label="Tampilkan password">
                    <svg x-show="!show" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    <svg x-show="show" x-cloak width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3l18 18"></path><path d="M10.6 5.1A10.5 10.5 0 0 1 12 5c6.4 0 10 7 10 7a17.7 17.7 0 0 1-3.2 4.1M6.6 6.6A17.4 17.4 0 0 0 2 12s3.6 7 10 7a9.8 9.8 0 0 0 5.4-1.6"></path><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"></path></svg>
                </button>
            </div>
        </div>

        <label class="auth-remember" for="remember_me">
            <input id="remember_me" type="checkbox" name="remember" @checked(old('remember'))>
            Ingat saya di perangkat ini
        </label>

        <button type="submit" class="auth-submit" :disabled="busy">
            <span x-show="!busy">Masuk</span>
            <span x-show="busy" x-cloak>Memeriksa…</span>
        </button>
    </form>

    <p class="auth-help">Lupa password? Minta admin lain mengganti atau membuatkan akun baru lewat menu <strong>Akun &amp; Admin</strong> di dashboard.</p>
</x-guest-layout>
