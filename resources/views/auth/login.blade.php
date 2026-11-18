<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SehatYuk</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body class="auth-body">
<div class="auth-card">
    <div class="brand center">Sehat<span>Yuk</span></div>
    <p id="authSub" class="center muted">Masuk untuk mengambil tiket antrean</p>

    <p class="center muted switch"><a href="/">&larr; Kembali ke beranda</a></p>
    <div id="authError" class="alert" hidden></div>

    {{-- FORM LOGIN: dipakai pasien, admin & petugas --}}
    <form id="loginForm" class="form" novalidate>
        <input class="input" type="email" name="email" placeholder="Email" autocomplete="username" required>
        <input class="input" type="password" name="password" placeholder="Password" autocomplete="current-password" required>
        <button class="btn btn-primary btn-block" type="submit">Masuk</button>
        <div class="divider"><span>atau</span></div>
        <a class="btn btn-google btn-block" href="/auth/google">
            <svg width="20" height="20" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.5 0 6.6 1.2 9.1 3.6l6.8-6.8C35.8 2.4 30.3 0 24 0 14.6 0 6.5 5.4 2.6 13.2l7.9 6.1C12.4 13.6 17.7 9.5 24 9.5z"/><path fill="#4285F4" d="M46.5 24.5c0-1.6-.1-3.1-.4-4.5H24v9h12.7c-.6 3-2.3 5.5-4.8 7.2l7.5 5.8c4.4-4.1 7.1-10.1 7.1-17.5z"/><path fill="#FBBC05" d="M10.5 28.7c-.5-1.4-.8-3-.8-4.7s.3-3.2.8-4.7l-7.9-6.1C.9 16.4 0 20.1 0 24s.9 7.6 2.6 10.8l7.9-6.1z"/><path fill="#34A853" d="M24 48c6.5 0 11.9-2.1 15.9-5.8l-7.5-5.8c-2.1 1.4-4.9 2.3-8.4 2.3-6.3 0-11.6-4.1-13.5-9.8l-7.9 6.1C6.5 42.6 14.6 48 24 48z"/></svg>
            Masuk dengan Google
        </a>
        <p class="center muted switch">Belum punya akun? <a href="#" id="showRegister">Daftar</a></p>
    </form>

    {{-- FORM REGISTRASI: tersimpan ke tabel users (MySQL) --}}
    <form id="registerForm" class="form" novalidate hidden>
        <input class="input" type="text" name="name" placeholder="Nama lengkap" autocomplete="name" required>
        <input class="input" type="tel" name="phone" placeholder="No HP (contoh: 081234567890)" autocomplete="tel" required>
        <input class="input" type="email" name="email" placeholder="Email" autocomplete="email" required>
        <input class="input" type="password" name="password" placeholder="Password (min. 6 karakter)" autocomplete="new-password" required>
        <button class="btn btn-primary btn-block" type="submit">Daftar</button>
        <p class="center muted switch">Sudah punya akun? <a href="#" id="showLogin">Masuk</a></p>
    </form>
</div>
<script src="{{ asset('js/api.js') }}"></script>
<script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
