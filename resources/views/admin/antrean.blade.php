@extends('layouts.admin')
@section('title', 'Manajemen Antrean - Admin SehatYuk')

@section('page')
    <h1 class="title">Manajemen Antrean</h1>
    <p class="subtitle">Pantau pasien yang mengambil tiket, panggil, lewati yang terlambat, dan kirim pengingat WhatsApp.</p>

    <div class="stats" id="stats"></div>

    <div class="toolbar">
        <label class="inline">Tanggal kunjungan
            <input type="date" id="fDate" class="input">
        </label>
        <button class="btn btn-outline" id="btnAll">Semua</button>
        <select id="fDoctor" class="input"><option value="">Semua dokter</option></select>
        <button class="btn btn-primary" id="btnNext" title="Panggil pasien menunggu berikutnya (hari ini) untuk dokter terpilih">Panggil berikutnya</button>
    </div>

    <p class="hint">Tombol <b>Kirim WA</b> menyala hijau jika pasien tinggal ≤ 2 antrean lagi. Pasien yang dipanggil tapi belum datang bisa <b>Dilewati</b>; saat tiba, klik <b>Panggil kembali</b>.</p>

    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>No</th><th>Pasien</th><th>Dokter</th><th>Tanggal</th><th>No HP</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody id="rows"><tr><td colspan="7" class="empty">Memuat...</td></tr></tbody>
        </table>
    </div>
@endsection

@push('scripts') <script src="{{ asset('js/admin-antrean.js') }}"></script> @endpush
