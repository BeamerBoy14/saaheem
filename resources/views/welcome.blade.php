<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SAAHEEM</title>
    @include('partials.retro-theme')
    <style>
        :root {
            --magenta: #e4007c;
            --black: #0a0a0a;
            --grey-text: #6b6b6b;
            --white: #ffffff;
        }
        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            font-family: var(--font-retro);
            font-size: 10px;
            line-height: 1.9;
            color: var(--black);
            background: var(--white);
        }
        body.page-home { background: #1a0a14; }
        a { color: inherit; }
        .wrap {
            width: min(1180px, calc(100% - 3rem));
            margin-inline: auto;
        }
        @media (max-width: 640px) {
            .wrap { width: calc(100% - 1.5rem); }
        }

        /* —— Hero —— */
        .hero {
            position: relative;
            scroll-margin-top: 1rem;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #1a0a14;
        }
        .hero__media {
            position: absolute;
            inset: 0;
        }
        .hero__media video,
        .hero__media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .hero__overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,.35) 0%, rgba(26,10,20,.5) 50%, rgba(0,0,0,.55) 100%);
            pointer-events: none;
        }
        .hero__content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: clamp(4rem, 12vh, 6rem) 1rem clamp(2.5rem, 8vh, 4rem);
            /* Délai entre chaque lettre dans la vague (S→T→A→Y et W→E→I→R→D). */
            --hero-seq-step: 0.38s;
        }
        .hero__top {
            position: absolute;
            z-index: 4;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: clamp(0.85rem, 2.5vw, 1.35rem) clamp(1rem, 4vw, 2.5rem);
        }
        .logo--hero {
            font-size: clamp(13px, 1.87vw, 15px);
            letter-spacing: 0.04em;
            text-decoration: none;
            flex-shrink: 0;
            background-image: var(--retro-fire);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;
            filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.45));
        }
        .logo--hero:hover {
            filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.45)) brightness(1.12);
        }
        .hero__top-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
        }
        .hero-glass {
            border: 1px solid rgba(255, 255, 255, 0.45);
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            color: var(--white);
            box-shadow:
                0 1px 0 rgba(255, 255, 255, 0.3) inset,
                0 8px 28px rgba(0, 0, 0, 0.25);
            transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease;
        }
        .hero-glass:hover {
            background: rgba(255, 255, 255, 0.22);
            border-color: rgba(255, 255, 255, 0.65);
        }
        .hero-text-link {
            position: relative;
            display: inline-block;
            padding: 0.45rem 0.7rem;
            font-size: clamp(0.95rem, 2.4vw, 1.15rem);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            text-decoration: none;
            background: none;
            border: none;
            box-shadow: none;
            background-image: var(--retro-chrome);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;
            filter: drop-shadow(0 1px 6px rgba(0, 0, 0, 0.5));
            transition: filter 0.25s ease, opacity 0.35s ease, visibility 0.35s ease;
        }
        .hero-text-link::after {
            content: "";
            position: absolute;
            left: 0.55rem;
            right: 0.55rem;
            bottom: 0.1rem;
            height: 2px;
            background: var(--magenta);
            transform: scaleX(0);
            transform-origin: center;
            transition: transform 0.28s ease;
            opacity: 0.85;
        }
        .hero-text-link:hover,
        .hero-text-link:focus-visible {
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.35)) brightness(1.15);
        }
        .hero-text-link:hover::after,
        .hero-text-link:focus-visible::after {
            transform: scaleX(1);
        }
        .hero-text-link:focus-visible {
            outline: none;
        }
        .btn-letsgo {
            margin-top: clamp(1.25rem, 4vw, 2rem);
            cursor: pointer;
            font-family: inherit;
        }
        .btn-letsgo.is-dismissed {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            margin-top: 0;
            padding-top: 0;
            padding-bottom: 0;
            max-height: 0;
            overflow: hidden;
        }
        .hero-nav {
            margin-top: clamp(1rem, 3vw, 1.5rem);
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            pointer-events: none;
            transition: opacity 0.45s ease, transform 0.45s ease, visibility 0.45s;
        }
        .hero-nav.is-revealed {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }
        @media (prefers-reduced-motion: reduce) {
            .hero-nav {
                transform: none;
                transition: opacity 0.2s ease, visibility 0.2s;
            }
        }
        .hero-nav__list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: clamp(0.5rem, 2.5vw, 1.25rem);
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .hero-nav__list a {
            letter-spacing: 0.14em;
        }
        .hero-nav__list a.is-active {
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.45)) brightness(1.2);
        }
        .hero-nav__list a.is-active::after {
            transform: scaleX(1);
            background: var(--magenta);
        }
        .hero-socials {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        .hero-socials a {
            display: grid;
            place-items: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            color: var(--white);
            text-decoration: none;
        }
        .hero-socials a:hover { transform: scale(1.06); }
        .hero-socials svg { width: 16px; height: 16px; display: block; }
        @media (max-width: 520px) {
            .hero__top-actions .hero-socials { display: none; }
            .hero-text-link { font-size: 0.85rem; }
            .hero-stay { font-size: clamp(1.6rem, 9vw, 3.5rem); }
            .hero-weird__letter { font-size: clamp(1.1rem, 5.5vw, 2.2rem); }
        }
        @keyframes hero-seq-up-stay {
            0%, 2% { transform: translate3d(0, 0, 0) rotate(0deg); }
            11% { transform: translate3d(0, -12px, 0) rotate(0.4deg); }
            22%, 100% { transform: translate3d(0, 0, 0) rotate(0deg); }
        }
        @keyframes hero-seq-down-stay {
            0%, 2% { transform: translate3d(0, 0, 0) rotate(0deg); }
            11% { transform: translate3d(0, 10px, 0) rotate(-0.4deg); }
            22%, 100% { transform: translate3d(0, 0, 0) rotate(0deg); }
        }
        @keyframes hero-seq-up-weird {
            0%, 2% { transform: translate3d(0, 0, 0) rotate(var(--r, 0deg)); }
            11% { transform: translate3d(0, -13px, 0) rotate(var(--r, 0deg)); }
            22%, 100% { transform: translate3d(0, 0, 0) rotate(var(--r, 0deg)); }
        }
        @keyframes hero-seq-down-weird {
            0%, 2% { transform: translate3d(0, 0, 0) rotate(var(--r, 0deg)); }
            11% { transform: translate3d(0, 11px, 0) rotate(var(--r, 0deg)); }
            22%, 100% { transform: translate3d(0, 0, 0) rotate(var(--r, 0deg)); }
        }
        .hero-stay {
            font-family: var(--font-retro);
            font-size: clamp(2.4rem, 11vw, 6rem);
            line-height: 1.2;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: baseline;
            gap: 0.2em 0.08em;
            filter: drop-shadow(0 3px 12px rgba(0, 0, 0, 0.45));
        }
        .hero-stay .char {
            position: relative;
            display: inline-block;
            background-image: var(--retro-fire);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;
            animation-timing-function: ease-in-out;
            animation-iteration-count: infinite;
            animation-duration: calc(4 * var(--hero-seq-step));
        }
        .hero-stay .char:nth-child(odd) {
            animation-name: hero-seq-up-stay;
        }
        .hero-stay .char:nth-child(even) {
            animation-name: hero-seq-down-stay;
        }
        .hero-stay .char:nth-child(1) { animation-delay: 0s; }
        .hero-stay .char:nth-child(2) { animation-delay: var(--hero-seq-step); }
        .hero-stay .char:nth-child(3) { animation-delay: calc(2 * var(--hero-seq-step)); }
        .hero-stay .char:nth-child(4) { animation-delay: calc(3 * var(--hero-seq-step)); }
        .hero-stay .char--block::after {
            content: "";
            position: absolute;
            left: -0.08em;
            right: -0.08em;
            bottom: 0.12em;
            height: 0.55em;
            background: var(--magenta);
            z-index: -1;
        }
        .hero-weird {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.2rem 0.35rem;
            margin-top: 0.35rem;
        }
        .hero-weird__tile {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 1.2em;
            min-height: 1.2em;
            padding: 0.16em 0.26em;
            background: var(--white);
            border-radius: 3px;
            box-shadow: 0 2px 0 rgba(0, 0, 0, 0.15);
            transform: rotate(var(--r, 0deg));
            animation-timing-function: ease-in-out;
            animation-iteration-count: infinite;
            animation-duration: calc(5 * var(--hero-seq-step));
        }
        .hero-weird__letter {
            display: block;
            font-family: var(--font-retro);
            font-size: clamp(1.5rem, 6.5vw, 3.4rem);
            line-height: 1;
            background-image: var(--retro-fire);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;
        }
        .hero-weird__tile:nth-child(odd) {
            animation-name: hero-seq-up-weird;
        }
        .hero-weird__tile:nth-child(even) {
            animation-name: hero-seq-down-weird;
        }
        .hero-weird__tile:nth-child(1) { --r: -4deg; animation-delay: 0s; }
        .hero-weird__tile:nth-child(2) { --r: 3deg; animation-delay: var(--hero-seq-step); }
        .hero-weird__tile:nth-child(3) { --r: -2deg; animation-delay: calc(2 * var(--hero-seq-step)); }
        .hero-weird__tile:nth-child(4) { --r: 5deg; animation-delay: calc(3 * var(--hero-seq-step)); }
        .hero-weird__tile:nth-child(5) { --r: -3deg; animation-delay: calc(4 * var(--hero-seq-step)); }
        @media (prefers-reduced-motion: reduce) {
            .hero-stay .char,
            .hero-weird__tile {
                animation: none;
            }
        }
        .hero-video-badge {
            position: absolute;
            right: clamp(1rem, 4vw, 2.5rem);
            bottom: clamp(1rem, 3vw, 2rem);
            z-index: 3;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--white);
            text-shadow: 0 1px 4px rgba(0,0,0,.6);
        }
        .hero-media-btn {
            --btn-size: 44px;
            width: var(--btn-size);
            height: var(--btn-size);
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.45);
            background: rgba(255, 255, 255, 0.14);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            color: var(--white);
            cursor: pointer;
            display: grid;
            place-items: center;
            /* Une seule cellule : les deux SVG se superposent (sinon 2 lignes = pause + play visibles). */
            grid-template-columns: 1fr;
            grid-template-rows: 1fr;
            padding: 0;
            box-shadow:
                0 1px 0 rgba(255, 255, 255, 0.35) inset,
                0 8px 28px rgba(0, 0, 0, 0.35);
            transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .hero-media-btn:hover {
            background: rgba(255, 255, 255, 0.24);
            border-color: rgba(255, 255, 255, 0.65);
            transform: scale(1.06);
            box-shadow:
                0 1px 0 rgba(255, 255, 255, 0.45) inset,
                0 10px 32px rgba(0, 0, 0, 0.4);
        }
        .hero-media-btn:active {
            transform: scale(0.98);
        }
        .hero-media-btn:focus-visible {
            outline: 2px solid var(--magenta);
            outline-offset: 3px;
        }
        /* Ne pas utiliser seul `> svg { display:block }` : ça écrase (spécificité) le `display:none` du play. */
        .hero-media-btn > svg {
            grid-area: 1 / 1;
            width: 18px;
            height: 18px;
            justify-self: center;
            align-self: center;
        }
        .hero-media-btn > .hero-media-btn__icon--pause {
            display: block;
        }
        .hero-media-btn > .hero-media-btn__icon--play {
            display: none;
            margin-left: 2px;
        }
        .hero-media-btn.hero-media-btn--paused > .hero-media-btn__icon--pause {
            display: none;
        }
        .hero-media-btn.hero-media-btn--paused > .hero-media-btn__icon--play {
            display: block;
        }
        .lang-switch {
            display: inline-flex;
            align-items: center;
            gap: 0.15rem;
            font-size: clamp(9px, 1.02vw, 11px);
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            flex-shrink: 0;
        }
        .lang-switch__btn {
            text-decoration: none;
            padding: 0.34rem 0.47rem;
            transition: opacity 0.15s ease;
        }
        .lang-switch__sep {
            opacity: 0.35;
            user-select: none;
            pointer-events: none;
        }
        .lang-switch--hero {
            border: 1px solid rgba(255, 255, 255, 0.45);
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-radius: 999px;
            padding: 0.1rem 0.3rem;
            color: var(--white);
            box-shadow:
                0 1px 0 rgba(255, 255, 255, 0.3) inset,
                0 8px 28px rgba(0, 0, 0, 0.25);
        }
        .lang-switch--hero .lang-switch__btn {
            opacity: 0.65;
            color: var(--white);
            padding: 0.38rem 0.51rem;
        }
        .lang-switch--hero .lang-switch__btn:hover,
        .lang-switch--hero .lang-switch__btn.is-active {
            opacity: 1;
            color: var(--white);
        }

    </style>
</head>
<body class="page-home">
    @include('partials.site-cursor')
    <section class="hero" id="accueil">
        <div class="hero__media" aria-hidden="true">
            <video id="hero-video" autoplay muted loop playsinline poster="">
                <source src="{{ asset('videos/bk.mp4') }}" type="video/mp4">
            </video>
        </div>
        <div class="hero__overlay"></div>
        <div class="hero__top wrap">
            <a class="logo--hero" href="{{ route('home') }}">SAAHEEM</a>
            <div class="hero__top-actions">
                @include('partials.lang-switch', ['variant' => 'hero'])
                <div class="hero-socials" aria-label="{{ __('site.nav.socials') }}">
                    <a class="hero-glass" href="https://www.tiktok.com/@yungxboy_" target="_blank" rel="noopener noreferrer" aria-label="TikTok" title="TikTok"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19.59 6.69A4.83 4.83 0 0 1 15.82 2.4V1h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43V8.68a8.16 8.16 0 0 0 4.77 1.52V6.8a4.85 4.85 0 0 1-1-.11z"/></svg></a>
                    <a class="hero-glass" href="https://www.youtube.com/@yungxboy?si=euDF9fs-O6TkuAnK" target="_blank" rel="noopener noreferrer" aria-label="YouTube" title="YouTube"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23.5 6.2A3 3 0 0 0 21.4 4C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 0 0 .5 6.2 31.5 31.5 0 0 0 0 12a31.5 31.5 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1C4.5 20.5 12 20.5 12 20.5s7.5 0 9.4-.6a3 3 0 0 0 2.1-2.1c.4-2.6.5-3.9.5-5.8 0-1.9-.1-3.2-.5-5.8zM9.5 15.5v-7l6.3 3.5-6.3 3.5z"/></svg></a>
                    <a class="hero-glass" href="https://www.instagram.com/saaheem__" target="_blank" rel="noopener noreferrer" aria-label="Instagram" title="Instagram"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5A4.5 4.5 0 1 1 7.5 12 4.5 4.5 0 0 1 12 7.5zm5.5-1a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg></a>
                </div>
            </div>
        </div>
        <div class="hero__content">
            <div class="hero-stay" aria-hidden="true">
                <span class="char">S</span><span class="char char--block">T</span><span class="char char--block">A</span><span class="char">Y</span>
            </div>
            <div class="hero-weird" aria-label="WEIRD">
                <span class="hero-weird__tile"><span class="hero-weird__letter">W</span></span>
                <span class="hero-weird__tile"><span class="hero-weird__letter">E</span></span>
                <span class="hero-weird__tile"><span class="hero-weird__letter">I</span></span>
                <span class="hero-weird__tile"><span class="hero-weird__letter">R</span></span>
                <span class="hero-weird__tile"><span class="hero-weird__letter">D</span></span>
            </div>
            <button type="button" class="btn-letsgo hero-text-link" id="btn-letsgo" aria-expanded="false" aria-controls="hero-nav">{{ __('site.home.lets_go') }}</button>
            <nav class="hero-nav" id="hero-nav" aria-label="{{ __('site.nav.main') }}" aria-hidden="true">
                <ul class="hero-nav__list" id="hero-nav-list">
                    <li><a class="hero-text-link" href="{{ route('about') }}">{{ __('site.nav.about') }}</a></li>
                    <li><a class="hero-text-link" href="{{ route('moments') }}">{{ __('site.nav.moments') }}</a></li>
                    <li><a class="hero-text-link" href="{{ route('actualites') }}">{{ __('site.nav.news') }}</a></li>
                    <li><a class="hero-text-link" href="{{ route('merch') }}">{{ __('site.nav.merch') }}</a></li>
                </ul>
            </nav>
        </div>
        <div class="hero-video-badge">
            <span>• {{ __('site.home.video_playing') }}</span>
            <button type="button" id="hero-pause" class="hero-media-btn" aria-label="{{ __('site.home.pause_video') }}">
                <svg class="hero-media-btn__icon hero-media-btn__icon--pause" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <rect x="6" y="5" width="4" height="14" rx="1.2"></rect>
                    <rect x="14" y="5" width="4" height="14" rx="1.2"></rect>
                </svg>
                <svg class="hero-media-btn__icon hero-media-btn__icon--play" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M8 5.5v13L19 12 8 5.5z"></path>
                </svg>
            </button>
        </div>
    </section>

    <script>
        (function () {
            var videoLabels = {
                play: @json(__('site.home.play_video')),
                pause: @json(__('site.home.pause_video')),
            };
            var v = document.getElementById('hero-video');
            var b = document.getElementById('hero-pause');
            if (v && b) {
                function syncHeroMediaBtn() {
                    b.classList.toggle('hero-media-btn--paused', v.paused);
                    b.setAttribute('aria-label', v.paused ? videoLabels.play : videoLabels.pause);
                }
                b.addEventListener('click', function () {
                    if (v.paused) { v.play(); } else { v.pause(); }
                });
                v.addEventListener('play', syncHeroMediaBtn);
                v.addEventListener('pause', syncHeroMediaBtn);
                syncHeroMediaBtn();
            }
            var letsgo = document.getElementById('btn-letsgo');
            var heroNav = document.getElementById('hero-nav');
            if (letsgo && heroNav) {
                letsgo.addEventListener('click', function () {
                    letsgo.classList.add('is-dismissed');
                    letsgo.setAttribute('aria-expanded', 'true');
                    window.setTimeout(function () {
                        letsgo.hidden = true;
                        heroNav.classList.add('is-revealed');
                        heroNav.setAttribute('aria-hidden', 'false');
                    }, 320);
                });
            }
        })();
    </script>
</body>
</html>
