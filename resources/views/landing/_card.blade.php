<article class="art-card" data-poli="{{ $a->poli_id }}">
    <span class="art-poli">{{ $a->poli->name }}</span>
    <h3><a href="/artikel/{{ $a->slug }}">{{ $a->title }}</a></h3>
    <p>{{ $a->summary }}</p>
    <div class="art-meta">
        <b>{{ $a->category }}</b>
        @foreach($a->tag_list as $t)<em>#{{ $t }}</em>@endforeach
    </div>
    <a class="art-more" href="/artikel/{{ $a->slug }}">Baca selengkapnya</a>
</article>
