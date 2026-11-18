@extends('layouts.user')
@section('title', 'Katalog Poli - SehatYuk')
@push('styles') <link rel="stylesheet" href="{{ asset('css/katalog.css') }}"> @endpush

@section('page')
    <h1 class="title">Katalog Poli</h1>
    <p class="subtitle">Daftar poliklinik, dokter, jadwal praktik, dan kuota harian.</p>
    <div id="poliList"><p class="muted">Memuat data...</p></div>
@endsection

@push('scripts') <script src="{{ asset('js/katalog.js') }}"></script> @endpush
