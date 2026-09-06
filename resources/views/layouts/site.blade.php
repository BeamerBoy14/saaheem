<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    @include('partials.retro-theme')
    <style>
        :root {
            --magenta: #e4007c;
            --black: #0a0a0a;
            --grey-text: #6b6b6b;
            --white: #ffffff;
        }
        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; overflow-x: hidden; }
        body { max-width: 100%; overflow-x: hidden; }
        body {
            margin: 0;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            font-family: var(--font-retro);
            font-size: 10px;
            line-height: 1.9;
            color: var(--black);
            background: var(--white);
        }
        a { color: inherit; }
        .wrap {
            width: min(1180px, calc(100% - 3rem));
            margin-inline: auto;
            max-width: 100%;
        }
        @media (max-width: 640px) {
            .wrap { width: calc(100% - 1.5rem); }
        }
        .site-header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: var(--white);
            border-bottom: 1px solid rgba(0,0,0,.06);
            width: 100%;
            max-width: 100vw;
            overflow-x: clip;
        }
        .site-header__inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            min-height: 72px;
            padding: 0.5rem 0;
            max-width: 100%;
        }
        .logo {
            font-weight: 800;
            font-size: clamp(13px, 1.7vw, 15px);
            letter-spacing: 0.02em;
            color: var(--magenta);
            text-decoration: none;
            flex-shrink: 0;
        }
        .nav-main {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: clamp(0.55rem, 2.2vw, 1.35rem);
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .nav-main a {
            font-size: clamp(10px, 1.15vw, 13px);
            font-weight: 700;
            letter-spacing: 0.1em;
            text-decoration: none;
            text-transform: uppercase;
            color: var(--black);
            padding: 0.42rem 0.6rem;
            border-bottom: 2px solid transparent;
        }
        .nav-main a:hover,
        .nav-main a.is-active { border-bottom-color: var(--magenta); }

        /* ── Nav more (···) dropdown ── */
        .nav-more { position: relative; }
        .nav-more__trigger {
            font-family: var(--font-retro, inherit);
            font-size: clamp(10px, 1.15vw, 13px);
            font-weight: 700;
            letter-spacing: 0.12em;
            color: var(--black);
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            padding: 0.42rem 0.6rem;
            line-height: 1;
            transition: border-color 0.15s;
        }
        .nav-more__trigger:hover,
        .nav-more__trigger.is-active { border-bottom-color: var(--magenta); }
        .nav-more__dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            min-width: 180px;
            background: rgba(15, 8, 18, 0.97);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            box-shadow: 0 12px 32px rgba(0,0,0,0.5);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            z-index: 300;
            flex-direction: column;
            list-style: none;
            margin: 0;
            padding: 0.35rem 0;
        }
        .nav-more.is-open .nav-more__dropdown { display: flex; }
        .nav-more__dropdown a {
            display: block;
            padding: 0.7rem 1.2rem;
            font-size: clamp(10px, 1.15vw, 12px);
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            text-decoration: none;
            color: rgba(255,255,255,0.75);
            border-bottom: none;
            transition: color 0.15s, background 0.15s;
        }
        .nav-more__dropdown a:hover,
        .nav-more__dropdown a.is-active { color: var(--magenta); background: rgba(228,0,124,0.08); }

        /* Mobile : aplatir le dropdown dans la liste */
        @media (max-width: 960px) {
            .nav-more__trigger { display: none; }
            .nav-more__dropdown {
                display: flex !important;
                position: static;
                min-width: auto;
                background: none;
                border: none;
                border-radius: 0;
                box-shadow: none;
                padding: 0;
                width: 100%;
            }
            .nav-more__dropdown li { width: 100%; }
            .nav-more__dropdown a {
                display: block;
                padding: 0.85rem 0.5rem;
                font-size: clamp(10px, 1.15vw, 13px);
                border-bottom: 1px solid #f0f0f0;
                color: var(--black);
                background: none;
            }
            .nav-more__dropdown a:hover,
            .nav-more__dropdown a.is-active { color: var(--magenta); background: none; }
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-shrink: 0;
        }
        .socials {
            display: flex;
            gap: 0.65rem;
            align-items: center;
        }
        .socials a {
            color: var(--black);
            opacity: .85;
            min-width: 44px;
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .socials a:hover { opacity: 1; color: var(--magenta); }
        .socials svg { width: 18px; height: 18px; display: block; }
        .btn-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.55rem 1.1rem;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            text-decoration: none;
            color: var(--magenta);
            border: 1px solid var(--magenta);
            background: transparent;
            transition: background .15s, color .15s;
        }
        .btn-outline:hover {
            background: var(--magenta);
            color: var(--white);
        }
        .header-auth {
            font-size: clamp(10px, 1.02vw, 11px);
            font-weight: 600;
        }
        .header-auth a { color: var(--grey-text); text-decoration: none; margin-left: 0.65rem; }
        .header-auth a:hover { color: var(--magenta); }
        .nav-toggle {
            display: none;
            width: 44px;
            height: 44px;
            border: 1px solid #ddd;
            background: var(--white);
            border-radius: 2px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 5px;
            padding: 0;
        }
        .nav-toggle span {
            display: block;
            width: 18px;
            height: 2px;
            background: var(--black);
        }
        @media (max-width: 960px) {
            /* Mobile : SAAHEEM à gauche + carré hamburger à droite uniquement */
            .header-right { display: none; }

            .nav-toggle {
                display: inline-flex;
                width: 40px;
                height: 40px;
                border: 1.5px solid currentColor;
                border-radius: 4px;
                background: transparent;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 5px;
                padding: 0;
                flex-shrink: 0;
                margin-left: auto;
            }
            .nav-toggle span {
                width: 18px;
                height: 2px;
                background: currentColor;
                display: block;
            }

            .nav-main {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--white);
                flex-direction: column;
                padding: 1rem 1.25rem;
                border-bottom: 1px solid #eee;
                gap: 0.35rem;
                z-index: 200;
            }
            .nav-main.is-open { display: flex; }
            .nav-main li { width: 100%; }
            .nav-main a {
                display: block;
                padding: 0.85rem 0.5rem;
                border-bottom: 1px solid #f0f0f0;
            }
            .site-header { position: relative; }
            .site-header .wrap { position: relative; }
        }
        .page-blank {
            flex: 1;
            min-height: 0;
            background: var(--white);
        }
        .lang-switch {
            display: inline-flex;
            align-items: center;
            gap: 0.15rem;
            font-size: clamp(10px, 1.02vw, 11px);
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            flex-shrink: 0;
        }
        .lang-switch__btn {
            text-decoration: none;
            color: inherit;
            opacity: 0.5;
            min-width: 44px;
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.34rem 0.47rem;
            border-radius: 2px;
            transition: opacity 0.15s ease, color 0.15s ease;
        }
        .lang-switch__btn:hover,
        .lang-switch__btn.is-active {
            opacity: 1;
            color: var(--magenta);
        }
        .lang-switch__sep {
            opacity: 0.35;
            user-select: none;
            pointer-events: none;
        }
        body.page-dark .lang-switch__btn {
            color: rgba(255, 255, 255, 0.5);
        }
        body.page-dark .lang-switch__btn.is-active,
        body.page-dark .lang-switch__btn:hover {
            color: #d4a437;
            opacity: 1;
        }
        body.page-dark .lang-switch__sep {
            color: rgba(255, 255, 255, 0.3);
        }
        @media (max-width: 960px) {
            .header-right .lang-switch { order: -1; }
        }
    </style>
    @stack('head')
</head>
<body class="@yield('body_class')">
    @include('partials.site-cursor')
    @include('partials.site-header', ['activeNav' => $activeNav ?? ''])
    @yield('content')
    <script>
        (function () {
            var toggle = document.getElementById('nav-toggle');
            var nav = document.getElementById('nav-main');
            if (toggle && nav) {
                toggle.addEventListener('click', function () {
                    var open = nav.classList.toggle('is-open');
                    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                });
            }

            var navMore = document.getElementById('nav-more');
            var navMoreTrigger = document.getElementById('nav-more-trigger');
            if (navMore && navMoreTrigger) {
                navMoreTrigger.addEventListener('click', function (e) {
                    e.stopPropagation();
                    var open = navMore.classList.toggle('is-open');
                    navMoreTrigger.setAttribute('aria-expanded', open ? 'true' : 'false');
                });
                document.addEventListener('click', function () {
                    navMore.classList.remove('is-open');
                    navMoreTrigger.setAttribute('aria-expanded', 'false');
                });
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') {
                        navMore.classList.remove('is-open');
                        navMoreTrigger.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        })();
    </script>
    @stack('scripts')
</body>
</html>
