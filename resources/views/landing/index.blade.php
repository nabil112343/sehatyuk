@extends('layouts.user')
@section('title', 'SehatYuk - Sistem Reservasi & Antrean Digital')
@push('styles') <link rel="stylesheet" href="{{ asset('css/landing.css') }}"> @endpush

@php
    $short = fn ($n) => trim(preg_replace('/\s*\(.*\)/', '', preg_replace('/^Poli\s+/', '', $n)));
@endphp

@section('page')
<div class="lp">

    {{-- HERO --}}
    <section class="hero">
        <div>
            <h1>Kesehatan Keluarga Anda, <span>Tanpa Antre Lama di Ruang Tunggu</span></h1>
            <p>Ambil tiket antrean dan reservasi dokter secara daring di Klinik SehatYuk. Pantau perkiraan panggilan langsung dari smartphone Anda secara real-time.</p>
            <div class="hero-cta">
                <a class="btn btn-primary" href="/ambil-tiket">Ambil nomor antrean sekarang</a>
                <a class="btn btn-outline" href="/layar-tv">Pantau layar antrean</a>
            </div>
            <div class="stats">
                <div><b>{{ $polis->count() }}</b><span>Poli & spesialis</span></div>
                <div><b>{{ $doctorCount }}</b><span>Dokter berpengalaman</span></div>
                <div><b>100%</b><span>Tiket terintegrasi</span></div>
                <div><b>Rp0</b><span>Biaya pendaftaran online</span></div>
            </div>
        </div>
        <div class="hero-img">
            <img src="{{ asset('images/hero-ilustrasi.png') }}" alt="Tenaga kesehatan SehatYuk" onerror="this.style.display='none';this.parentElement.classList.add('empty')">
        </div>
    </section>

    {{-- ALUR --}}
    <section id="alur">
        <h2 class="sec-title">Alur mudah mendapatkan pelayanan</h2>
        <p class="sec-sub">Proses sederhana dari awal pendaftaran hingga konsultasi dokter.</p>
        <ol class="steps">
            <li><b>1</b><h3>Pilih poli & dokter</h3><p>Tentukan poliklinik tujuan dan jadwal dokter sesuai ketersediaan hari praktik.</p></li>
            <li><b>2</b><h3>Ambil no antrean</h3><p>Isi data pasien dan peroleh nomor antrean otomatis dengan kode resmi poli.</p></li>
            <li><b>3</b><h3>Pantau status live</h3><p>Lihat nomor yang sedang dilayani dan berapa pasien tersisa di depan Anda.</p></li>
            <li><b>4</b><h3>Panggilan & periksa</h3><p>Datang tepat waktu, dengarkan panggilan, lalu temui dokter di ruang periksa.</p></li>
        </ol>
    </section>

    {{-- POLI --}}
    <section id="poli">
        <h2 class="sec-title">Daftar poliklinik SehatYuk</h2>
        <p class="sec-sub">Layanan spesialis yang tersedia di klinik kami.</p>
        <div class="poli-grid">
            @foreach($polis as $p)
                <div class="poli-item">
                    <div class="poli-ic">@include('landing._icon', ['code' => $p->code])</div>
                    <h3>{{ $p->name }}</h3>
                    <p>{{ $p->description }}</p>
                    <div class="poli-foot">
                        <span><b>{{ $p->doctors_count }}</b> dokter aktif</span>
                        <a href="/ambil-tiket">Pilih dokter</a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ARTIKEL --}}
    <section id="artikel">
        <h2 class="sec-title">Tips & artikel kesehatan terkini</h2>
        <p class="sec-sub">Panduan praktis sesuai poliklinik SehatYuk, diperbarui setiap minggu dengan bantuan Gemini AI. Bukan pengganti konsultasi dokter.</p>

        @if($articles->isEmpty())
            <p class="muted">Artikel belum tersedia. Jalankan <code>php artisan articles:generate</code> atau <code>php artisan db:seed --class=ArticleSeeder</code>.</p>
        @else
            <div class="chips">
                <button type="button" class="on" data-filter="all">Semua spesialis</button>
                @foreach($polis->filter->latestArticle as $p)
                    <button type="button" data-filter="{{ $p->id }}">{{ $short($p->name) }}</button>
                @endforeach
            </div>
            <div class="art-grid">
                @foreach($articles as $a) @include('landing._card', ['a' => $a->setRelation('poli', $polis->firstWhere('id', $a->poli_id))]) @endforeach
            </div>
            <p class="muted" id="artEmpty" hidden>Belum ada artikel untuk poli ini.</p>
            <p class="art-all"><a href="/artikel">Lihat semua artikel dan arsip</a></p>
        @endif
    </section>

    {{-- INFO KLINIK --}}
    <section class="clinic">
        <div>
            <h2>{{ $clinic['name'] }}</h2>
            <p>{{ $clinic['about'] }}</p>
            <p class="clinic-meta">{{ $clinic['address'] }}</p>
        </div>
        <a class="btn btn-primary" href="/ambil-tiket">Daftar antrean online</a>
    </section>

    {{-- CTA BAWAH --}}
    <section class="cta">
        <h2>Kesehatan Anda, Prioritas Kami</h2>
        <a class="btn btn-primary" href="#poli">Lihat layanan</a>
        <img src="{{ asset('images/hero-ilustrasi.png') }}" alt="" onerror="this.style.display='none'">
    </section>

    <footer class="lp-foot">
        <b>Sehat<span>Yuk</span></b> | Klinik Pratama & Spesialis Terintegrasi
        <span>{{ $clinic['hours'] }}</span>
        <span>Hotline: {{ $clinic['hotline'] }}</span>
        <span>&copy; {{ date('Y') }} SehatYuk</span>
    </footer>
</div>
@endsection

@push('scripts') <script src="{{ asset('js/landing.js') }}"></script> @endpush
