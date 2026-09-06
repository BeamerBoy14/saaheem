@php

    $nav = $activeNav ?? '';

@endphp

<header class="site-header">

    <div class="wrap site-header__inner">

        <a class="logo" href="{{ route('home') }}">SAAHEEM</a>

        <button type="button" class="nav-toggle" id="nav-toggle" aria-expanded="false" aria-controls="nav-main" aria-label="{{ __('site.nav.open_menu') }}">

            <span></span><span></span><span></span>

        </button>

        <nav aria-label="{{ __('site.nav.main') }}">

            <ul class="nav-main" id="nav-main">

                <li><a class="{{ $nav === 'about'   ? 'is-active' : '' }}" href="{{ route('about') }}">{{ __('site.nav.about') }}</a></li>
                <li><a class="{{ $nav === 'moments' ? 'is-active' : '' }}" href="{{ route('moments') }}">{{ __('site.nav.moments') }}</a></li>
                <li><a class="{{ $nav === 'merch'   ? 'is-active' : '' }}" href="{{ route('merch') }}">{{ __('site.nav.merch') }}</a></li>
                <li><a class="{{ $nav === 'contact' ? 'is-active' : '' }}" href="{{ route('contact') }}">{{ __('site.nav.contact') }}</a></li>

                <li class="nav-more" id="nav-more">
                    <button type="button" class="nav-more__trigger {{ in_array($nav, ['blog','espace','weird']) ? 'is-active' : '' }}" id="nav-more-trigger" aria-expanded="false" aria-haspopup="true">···</button>
                    <ul class="nav-more__dropdown" id="nav-more-dropdown" role="list">
                        <li><a class="{{ $nav === 'blog'   ? 'is-active' : '' }}" href="#" id="blog-gate-trigger">{{ __('site.nav.blog') }}</a></li>
                        <li><a class="{{ $nav === 'espace' ? 'is-active' : '' }}" href="#">{{ __('site.nav.espace') }}</a></li>
                        <li><a class="{{ $nav === 'weird'  ? 'is-active' : '' }}" href="{{ route('stay-weird') }}">{{ __('site.nav.weird') }}</a></li>
                    </ul>
                </li>

            </ul>

        </nav>

        <div class="header-right">

            @include('partials.lang-switch')

            <div class="socials" aria-label="{{ __('site.nav.socials') }}">

                <a href="https://www.tiktok.com/@yungxboy_" target="_blank" rel="noopener noreferrer" aria-label="TikTok" title="TikTok"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19.59 6.69A4.83 4.83 0 0 1 15.82 2.4V1h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43V8.68a8.16 8.16 0 0 0 4.77 1.52V6.8a4.85 4.85 0 0 1-1-.11z"/></svg></a>

                <a href="https://www.youtube.com/@yungxboy?si=euDF9fs-O6TkuAnK" target="_blank" rel="noopener noreferrer" aria-label="YouTube" title="YouTube"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23.5 6.2A3 3 0 0 0 21.4 4C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 0 0 .5 6.2 31.5 31.5 0 0 0 0 12a31.5 31.5 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1C4.5 20.5 12 20.5 12 20.5s7.5 0 9.4-.6a3 3 0 0 0 2.1-2.1c.4-2.6.5-3.9.5-5.8 0-1.9-.1-3.2-.5-5.8zM9.5 15.5v-7l6.3 3.5-6.3 3.5z"/></svg></a>

                <a href="https://www.instagram.com/saaheem__" target="_blank" rel="noopener noreferrer" aria-label="Instagram" title="Instagram"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5A4.5 4.5 0 1 1 7.5 12 4.5 4.5 0 0 1 12 7.5zm5.5-1a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg></a>

            </div>

            @if (Route::has('login'))

                <span class="header-auth">

                    @auth

                        <a href="{{ url('/home') }}">{{ __('site.auth.dashboard') }}</a>

                    @else

                        <a href="{{ route('login') }}">{{ __('site.auth.login') }}</a>

                        @if (Route::has('register'))

                            <a href="{{ route('register') }}">{{ __('site.auth.register') }}</a>

                        @endif

                    @endauth

                </span>

            @endif

        </div>

    </div>

</header>

{{-- ── Blog password gate ── --}}
<div id="blog-gate" style="
    display:none;
    position:fixed;inset:0;z-index:99998;
    background:rgba(0,0,0,0.92);
    align-items:center;justify-content:center;
    flex-direction:column;gap:1.5rem;
">
    <p style="font-family:'Press Start 2P',monospace;font-size:clamp(0.6rem,2.5vw,0.85rem);color:rgba(255,255,255,0.5);letter-spacing:0.1em;text-transform:uppercase;">{{ __('site.contact.password') }}</p>
    <input id="blog-gate-input" type="password" autocomplete="off" placeholder="••••••••" style="
        font-family:'Press Start 2P',monospace;
        font-size:clamp(0.75rem,3vw,1rem);
        background:transparent;
        border:none;
        border-bottom:2px solid rgba(228,0,124,0.6);
        color:#fff;
        text-align:center;
        outline:none;
        padding:0.5rem 1rem;
        width:min(280px,80vw);
        letter-spacing:0.15em;
    ">
    <p id="blog-gate-error" style="font-family:'Press Start 2P',monospace;font-size:0.55rem;color:#e4007c;opacity:0;transition:opacity 0.2s;">{{ __('site.contact.wrong') }}</p>
</div>

<script>
(function () {
    var trigger = document.getElementById('blog-gate-trigger');
    var gate    = document.getElementById('blog-gate');
    var input   = document.getElementById('blog-gate-input');
    var error   = document.getElementById('blog-gate-error');
    var URL     = 'https://www.tumblr.com/saaheemwrld';

    function openGate(e) {
        e.preventDefault();
        gate.style.display = 'flex';
        input.value = '';
        error.style.opacity = '0';
        setTimeout(function () { input.focus(); }, 50);
    }

    function closeGate() {
        gate.style.display = 'none';
    }

    function check() {
        if (input.value.trim().toLowerCase() === 'stayweird') {
            closeGate();
            window.open(URL, '_blank', 'noopener,noreferrer');
        } else {
            error.style.opacity = '1';
            input.value = '';
            input.focus();
        }
    }

    trigger.addEventListener('click', openGate);
    gate.addEventListener('click', function (e) { if (e.target === gate) closeGate(); });
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') check();
        if (e.key === 'Escape') closeGate();
    });
})();
</script>

