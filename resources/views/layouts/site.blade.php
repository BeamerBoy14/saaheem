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
        html { scroll-behavior: smooth; }
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
        }
        .site-header__inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            min-height: 72px;
            padding: 0.5rem 0;
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
        .socials a { color: var(--black); opacity: .85; }
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
            font-size: clamp(9px, 1.02vw, 11px);
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
            .nav-toggle { display: inline-flex; }
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
            font-size: clamp(9px, 1.02vw, 11px);
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            flex-shrink: 0;
        }
        .lang-switch__btn {
            text-decoration: none;
            color: inherit;
            opacity: 0.5;
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
        body.page-dark .lang-switch__btn.is-active,
        body.page-dark .lang-switch__btn:hover {
            color: var(--magenta);
        }
        body.page-about .lang-switch__btn:hover,
        body.page-about .lang-switch__btn.is-active {
            color: #0a0a0a;
            opacity: 1;
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
        })();
    </script>
    @stack('scripts')
</body>
</html>
