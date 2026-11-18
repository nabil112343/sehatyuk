@extends('layouts.user')
@section('title', 'Arsip Artikel Kesehatan - SehatYuk')
@push('styles') <link rel="stylesheet" href="{{ asset('css/landing.css') }}"> @endpush

@section('page')
<div class="lp">
    <h1 class="title">Arsip artikel kesehatan</h1>
    <p class="subtitle">Semua artikel mingguan per poli. Bukan pengganti konsultasi dokter.</p>
    <div class="chips">
        <a class="{{ !$active ? 'on' : '' }}" href="/artikel">Semua</a>
        @foreach($polis as $p)<a class="{{ $active === $p->id ? 'on' : '' }}" href="/artikel?poli={{ $p->id }}">{{ $p->name }}</a>@endforeach
    </div>
    <div class="art-grid">
        @forelse($articles as $a) @include('landing._card', ['a' => $a]) @empty <p class="muted">Belum ada artikel.</p> @endforelse
    </div>
    <p class="pager">
        @if($articles->previousPageUrl())<a href="{{ $articles->previousPageUrl() }}">&larr; Lebih baru</a>@endif
        @if($articles->nextPageUrl())<a href="{{ $articles->nextPageUrl() }}">Lebih lama &rarr;</a>@endif
    </p>
</div>
@endsection
