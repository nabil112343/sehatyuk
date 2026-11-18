@extends('layouts.base')

@section('sidebar')
    @include('partials.sidebar', ['items' => [
        ['label' => 'Beranda', 'url' => '/', 'icon' => 'home'],
        ['label' => 'Katalog Poli', 'url' => '/katalog', 'icon' => 'list'],
        ['label' => 'Pilih Dokter & Ambil Tiket', 'url' => '/ambil-tiket', 'icon' => 'calendar'],
        ['label' => 'Tiket Saya', 'url' => '/tiket-saya', 'icon' => 'ticket'],
        ['label' => 'Layar Live TV', 'url' => '/layar-tv', 'icon' => 'tv'],
    ]])
@endsection

@section('content')
    @yield('page')
@endsection
