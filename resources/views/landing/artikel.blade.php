@extends('layouts.user')
@section('title', $article->title . ' - SehatYuk')
@push('styles') <link rel="stylesheet" href="{{ asset('css/landing.css') }}"> @endpush

@section('page')
<div class="lp">
<article class="art">
    <a class="back" href="/artikel">&larr; Semua artikel</a>
    <span class="art-poli">{{ $article->poli->name }}</span>
    <h1>{{ $article->title }}</h1>
    <p class="art-meta"><b>{{ $article->category }}</b> <span>{{ $article->published_at->translatedFormat('j F Y') }}</span>
        @foreach($article->tag_list as $t)<em>#{{ $t }}</em>@endforeach</p>
    <div class="art-body">{!! $article->bodyHtml() !!}</div>
    <p class="disclaimer">Artikel ini bersifat edukasi{{ $article->source === 'gemini' ? ' dan dibuat dengan bantuan Gemini AI' : '' }}. Informasi ini bukan pengganti pemeriksaan atau saran dokter. Jika Anda memiliki keluhan, konsultasikan ke dokter.</p>
    <a class="btn btn-primary" href="/ambil-tiket">Ambil antrean {{ $article->poli->name }}</a>
</article>

@if($related->isNotEmpty())
    <h2 class="sec-title" style="margin-top:56px">Artikel lain dari poli ini</h2>
    <div class="art-grid">@foreach($related as $a) @include('landing._card', ['a' => $a]) @endforeach</div>
@endif
</div>
@endsection
