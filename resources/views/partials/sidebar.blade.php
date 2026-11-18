@php
    $icons = [
        'home' => '<svg viewBox="0 0 24 24"><path d="M4 11l8-7 8 7v9a1 1 0 0 1-1 1h-4v-6H9v6H5a1 1 0 0 1-1-1z"/></svg>',
        'list' => '<svg viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h10"/></svg>',
        'calendar' => '<svg viewBox="0 0 24 24"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M8 3v4M16 3v4M4 10h16"/></svg>',
        'ticket' => '<svg viewBox="0 0 24 24"><path d="M3 8a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2a2 2 0 0 0 0 4v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-4z"/></svg>',
        'tv' => '<svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/></svg>',
        'queue' => '<svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3"/><path d="M3 20v-1a5 5 0 0 1 5-5h2a5 5 0 0 1 5 5v1M17 11a3 3 0 1 0 0-6M21 20v-1a4 4 0 0 0-3-3.8"/></svg>',
        'doctor' => '<svg viewBox="0 0 24 24"><path d="M6 3v6a4 4 0 0 0 8 0V3M10 13v3a4 4 0 0 0 8 0v-1"/><circle cx="18" cy="13" r="2"/></svg>',
        'clinic' => '<svg viewBox="0 0 24 24"><path d="M4 21V8l8-5 8 5v13zM12 10v6M9 13h6"/></svg>',
    ];
    $u = auth()->user();
@endphp
<aside class="sidebar">
    <a class="brand" href="/" style="display:block;text-decoration:none;color:inherit">Sehat<span>Yuk</span></a>
    <nav class="nav">
        @foreach($items as $i)
            @php $path = trim($i['url'], '/') ?: '/'; @endphp
            <a href="{{ $i['url'] }}" class="{{ request()->is($path) ? 'active' : '' }}">{!! $icons[$i['icon']] !!}<span>{{ $i['label'] }}</span></a>
        @endforeach
    </nav>
    @if($u)
    <div class="userbox">
        <div class="avatar">{{ strtoupper(mb_substr($u->name, 0, 1)) }}</div>
        <div class="uinfo">
            <strong>{{ $u->name }}</strong>
            <span class="badge-role">{{ ucfirst($u->role) }}</span>
        </div>
        <button id="logoutBtn" class="icon-btn" title="Keluar" aria-label="Keluar">
            <svg viewBox="0 0 24 24"><path d="M9 4H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h4M16 8l4 4-4 4M20 12H9"/></svg>
        </button>
    </div>
    @else
    <div class="userbox"><a class="btn btn-primary btn-block" href="/login">Masuk / Daftar</a></div>
    @endif
</aside>
