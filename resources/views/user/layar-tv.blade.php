@extends('layouts.user')
@section('title', 'Layar Live TV - SehatYuk')
@push('styles') <link rel="stylesheet" href="{{ asset('css/tv.css') }}"> @endpush

@section('page')
    <h1 class="title">Layar Live TV</h1>
    <p class="subtitle" id="tvSub">Nomor yang sedang dipanggil hari ini. Diperbarui otomatis.</p>
    <div id="tvGrid" class="tv-grid"></div>
@endsection

@push('scripts') <script src="{{ asset('js/tv.js') }}"></script> @endpush
