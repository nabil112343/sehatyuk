@extends('layouts.user')
@section('title', 'Ambil Tiket - SehatYuk')
@push('styles') <link rel="stylesheet" href="{{ asset('css/ambil-tiket.css') }}"> @endpush

@section('page')
    <h1 class="title">Pilih Dokter & Ambil Tiket</h1>
    <p class="subtitle">Cari dokter, pilih hari kunjungan, lalu ambil nomor antrean.</p>

    <div class="filters">
        <input id="fSearch" class="input" placeholder="Cari nama dokter / spesialis">
        <select id="fPoli" class="input"><option value="">Semua poliklinik</option></select>
        <select id="fDay" class="input">
            <option value="">Semua hari praktik</option>
            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $d)
                <option>{{ $d }}</option>
            @endforeach
        </select>
    </div>
    <p id="count" class="muted"></p>
    <div id="docGrid" class="doc-grid"></div>

    {{-- Modal ambil tiket --}}
    <div id="ticketModal" class="modal" hidden>
        <div class="modal-box">
            <h2>Ambil nomor antrean</h2>
            <p id="mDoctor" class="muted"></p>
            <form id="ticketForm" class="form" novalidate>
                <label class="label">Tanggal kunjungan
                    <input id="mDate" class="input" type="date" required>
                </label>
                <p id="mHint" class="hint"></p>
                <label class="label">Nomor HP (untuk notifikasi WhatsApp)
                    <input id="mPhone" class="input" type="tel" value="{{ auth()->user()->phone }}" required>
                </label>
                <div id="mError" class="alert" hidden></div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-outline" data-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Ambil tiket</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts') <script src="{{ asset('js/ambil-tiket.js') }}"></script> @endpush
