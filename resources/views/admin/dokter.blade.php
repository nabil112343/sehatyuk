@extends('layouts.admin')
@section('title', 'Dokter & Jadwal - Admin SehatYuk')

@section('page')
    <div class="page-head">
        <div>
            <h1 class="title">Dokter & Jadwal</h1>
            <p class="subtitle">Tambah, ubah, dan hapus data dokter beserta jadwal praktik.</p>
        </div>
        <button class="btn btn-primary" id="btnAdd">+ Tambah dokter</button>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Dokter</th><th>Poli</th><th>Hari praktik</th><th>Jam</th><th>Kuota</th><th>Aksi</th></tr></thead>
            <tbody id="rows"><tr><td colspan="6" class="empty">Memuat...</td></tr></tbody>
        </table>
    </div>

    <div id="modal" class="modal" hidden>
        <div class="modal-box">
            <h2 id="mTitle">Tambah dokter</h2>
            <form id="form" class="form" novalidate>
                <label class="label">Poliklinik <select id="fPoli" class="input" required></select></label>
                <label class="label">Nama dokter <input id="fName" class="input" required></label>
                <label class="label">Spesialisasi <input id="fSpec" class="input" required></label>
                <div class="label">Hari praktik
                    <div class="days" id="fDays">
                        @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $d)
                            <label class="day"><input type="checkbox" value="{{ $d }}"><span>{{ $d }}</span></label>
                        @endforeach
                    </div>
                </div>
                <div class="grid3">
                    <label class="label">Jam mulai <input id="fStart" type="time" class="input" value="08:00" required></label>
                    <label class="label">Jam selesai <input id="fEnd" type="time" class="input" value="13:00" required></label>
                    <label class="label">Kuota/hari <input id="fQuota" type="number" min="1" class="input" value="15" required></label>
                </div>
                <div id="mError" class="alert" hidden></div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-outline" data-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts') <script src="{{ asset('js/admin-dokter.js') }}"></script> @endpush
