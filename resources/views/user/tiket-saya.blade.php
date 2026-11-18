@extends('layouts.user')
@section('title', 'Tiket Saya - SehatYuk')
@push('styles') <link rel="stylesheet" href="{{ asset('css/tiket.css') }}"> @endpush

@section('page')
    <h1 class="title">Tiket Saya</h1>
    <p class="subtitle">Pantau posisi giliran dan perkiraan waktu pemeriksaan.</p>
    <div id="tickets" class="tickets"><p class="muted">Memuat tiket...</p></div>
@endsection

@push('scripts') <script src="{{ asset('js/tiket.js') }}"></script> @endpush
