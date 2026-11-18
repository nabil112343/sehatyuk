<svg viewBox="0 0 24 24" aria-hidden="true">
@switch($code)
    @case('GG') <circle cx="12" cy="12" r="9"/><path d="M8 14s1.5 2 4 2 4-2 4-2M9 9h.01M15 9h.01"/> @break
    @case('AN') <circle cx="12" cy="13" r="7"/><path d="M12 6c0-1.5 1-2.5 2.5-2.5M9 12h.01M15 12h.01M10 16h4"/> @break
    @case('OG') <path d="M12 20s-7-4.5-7-10a4 4 0 0 1 7-2.5A4 4 0 0 1 19 10c0 5.5-7 10-7 10z"/> @break
    @case('MT') <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/> @break
    @case('PD') <path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/><path d="M9 12l2 2 4-4"/> @break
    @default <rect x="3" y="3" width="18" height="18" rx="4"/><path d="M12 7v10M7 12h10"/>
@endswitch
</svg>
