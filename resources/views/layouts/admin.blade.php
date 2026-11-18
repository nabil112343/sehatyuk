@extends('layouts.base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endpush

@section('sidebar')
    @include('partials.sidebar', ['items' => [
        ['label' => 'Layar Live TV', 'url' => '/admin/layar-tv', 'icon' => 'tv'],
        ['label' => 'Manajemen Antrean', 'url' => '/admin/antrean', 'icon' => 'queue'],
        ['label' => 'Dokter & Jadwal', 'url' => '/admin/dokter', 'icon' => 'doctor'],
        ['label' => 'Data Poli', 'url' => '/admin/poli', 'icon' => 'clinic'],
    ]])
@endsection

@section('content')
    @yield('page')
@endsection
