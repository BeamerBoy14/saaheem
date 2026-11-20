{{-- Navigation flottante (remplace l'ancienne navbar) : Retour + Menu (le menu vit sur l'accueil) --}}
<style>
    .page-nav {
        position: fixed;
        top: clamp(12px, 2.2vw, 22px);
        right: clamp(12px, 2.2vw, 22px);
        z-index: 400;
        display: flex;
        gap: 8px;
        font-family: 'DM Sans', system-ui, -apple-system, 'Segoe UI', sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .page-nav__btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 11px 18px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 500;
        line-height: 1;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        text-decoration: none;
        white-space: nowrap;
        -webkit-tap-highlight-color: transparent;
        touch-action: manipulation;
        transition: transform 0.2s, box-shadow 0.2s, background 0.2s;
    }
    .page-nav__btn:hover { transform: translateY(-2px); }
    .page-nav__btn--back {
        background: var(--magenta, #e4007c); /* repli pour les pages hors layout */
        color: #fff;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
    }
    .page-nav__btn--back:hover { box-shadow: 0 12px 30px rgba(228, 0, 124, 0.4); }
    .page-nav__btn--menu {
        background: rgba(15, 8, 12, 0.72);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }
    .page-nav__btn--menu:hover,
    .page-nav__btn--menu[aria-expanded="true"] { background: rgba(15, 8, 12, 0.92); }
    .page-nav__btn--menu {
        border-style: solid;
        font-family: inherit;
        cursor: pointer;
    }
    .page-nav__burger {
        display: inline-flex;
        flex-direction: column;
        gap: 3px;
    }
    .page-nav__burger span {
        display: block;
        width: 12px;
        height: 1.5px;
        background: currentColor;
        transition: transform 0.25s, opacity 0.25s;
    }
    .page-nav__btn--menu[aria-expanded="true"] .page-nav__burger span:nth-child(1) { transform: translateY(4.5px) rotate(45deg); }
    .page-nav__btn--menu[aria-expanded="true"] .page-nav__burger span:nth-child(2) { opacity: 0; }
    .page-nav__btn--menu[aria-expanded="true"] .page-nav__burger span:nth-child(3) { transform: translateY(-4.5px) rotate(-45deg); }

    /* ── Panneau du menu (liens gris du menu d'accueil) ── */
    .page-nav__panel {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        min-width: 240px;
        margin: 0;
        padding: 10px;
        list-style: none;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 16px;
        background: rgba(15, 8, 12, 0.94);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        box-shadow: 0 18px 50px rgba(0, 0, 0, 0.5);
        opacity: 0;
        transform: translateY(-8px);
        pointer-events: none;
        transition: opacity 0.2s ease, transform 0.2s ease;
    }
    .page-nav__panel.is-open {
        opacity: 1;
        transform: none;
        pointer-events: auto;
    }
    .page-nav__panel a {
        display: block;
        padding: 14px 16px;
        border-radius: 10px;
        font-family: var(--font-retro, 'Press Start 2P', monospace);
        font-size: 11px;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        text-decoration: none;
        color: rgba(255, 255, 255, 0.48);
        -webkit-text-fill-color: rgba(255, 255, 255, 0.48);
        transition: color 0.15s, background 0.15s;
    }
    .page-nav__panel a:hover,
    .page-nav__panel a:focus-visible {
        color: #fff;
        -webkit-text-fill-color: #fff;
        background: rgba(255, 255, 255, 0.06);
    }
    .page-nav__panel a[aria-current="page"] {
        color: var(--magenta, #e4007c);
        -webkit-text-fill-color: var(--magenta, #e4007c);
    }
    .page-nav__panel li + li.page-nav__home {
        margin-top: 6px;
        padding-top: 6px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }
</style>

@php
    // Mêmes liens que les entrées grises du menu d'accueil
    $menuLinks = [
        ['label' => __('site.nav.about'), 'url' => route('about'),      'external' => false],
        ['label' => 'Photo / Video',      'url' => route('moments'),    'external' => false],
        ['label' => 'Management',         'url' => route('management'), 'external' => false],
        ['label' => 'YouTube',            'url' => 'https://youtube.com/@yungxboy?si=-au8fue6uFA_d6zf', 'external' => true],
    ];
@endphp

<nav class="page-nav" aria-label="{{ __('site.nav.main') }}">
    <a class="page-nav__btn page-nav__btn--back" id="page-nav-back" href="{{ route('home') }}">&larr; {{ __('site.nav.back') }}</a>
    <button type="button" class="page-nav__btn page-nav__btn--menu" id="page-nav-menu"
        aria-expanded="false" aria-controls="page-nav-panel">
        <span class="page-nav__burger" aria-hidden="true"><span></span><span></span><span></span></span>
        {{ __('site.nav.menu') }}
    </button>
    <ul class="page-nav__panel" id="page-nav-panel">
        @foreach ($menuLinks as $link)
            <li>
                <a href="{{ $link['url'] }}"
                    @if ($link['external']) target="_blank" rel="noopener" @endif
                    @if (! $link['external'] && url()->current() === $link['url']) aria-current="page" @endif>{{ $link['label'] }}</a>
            </li>
        @endforeach
        <li class="page-nav__home"><a href="{{ route('home') }}">{{ __('site.nav.home') }}</a></li>
    </ul>
</nav>

<script>
(function () {
    // Retour : page précédente si elle vient du site, sinon l'accueil (lien par défaut)
    var back = document.getElementById('page-nav-back');
    if (back && document.referrer.indexOf(location.origin) === 0 && history.length > 1) {
        back.addEventListener('click', function (e) {
            e.preventDefault();
            history.back();
        });
    }

    // Menu : ouvre / ferme le panneau des liens
    var menuBtn = document.getElementById('page-nav-menu');
    var panel   = document.getElementById('page-nav-panel');
    if (!menuBtn || !panel) return;
    function setOpen(open) {
        panel.classList.toggle('is-open', open);
        menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
    }
    menuBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        setOpen(!panel.classList.contains('is-open'));
    });
    document.addEventListener('click', function (e) {
        if (!panel.contains(e.target)) setOpen(false);
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') setOpen(false);
    });
})();
</script>
