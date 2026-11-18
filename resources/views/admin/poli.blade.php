@extends('layouts.admin')
@section('title', 'Data Poli - Admin SehatYuk')

@section('page')
    <div class="page-head">
        <div>
            <h1 class="title">Data Poli</h1>
            <p class="subtitle">Kelola poliklinik. Kode poli dipakai sebagai awalan nomor antrean (contoh: UM-001).</p>
        </div>
        <button class="btn btn-primary" id="btnAdd">+ Tambah poli</button>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Kode</th><th>Nama poli</th><th>Deskripsi</th><th>Dokter</th><th>Aksi</th></tr></thead>
            <tbody id="rows"><tr><td colspan="5" class="empty">Memuat...</td></tr></tbody>
        </table>
    </div>

    <div id="modal" class="modal" hidden>
        <div class="modal-box">
            <h2 id="mTitle">Tambah poli</h2>
            <form id="form" class="form" novalidate>
                <label class="label">Kode (maks. 5 huruf) <input id="fCode" class="input" maxlength="5" required></label>
                <label class="label">Nama poli <input id="fName" class="input" required></label>
                <label class="label">Deskripsi <textarea id="fDesc" class="input" rows="3"></textarea></label>
                <div id="mError" class="alert" hidden></div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-outline" data-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts') <script src="{{ asset('js/admin-poli.js') }}"></script> @endpush
