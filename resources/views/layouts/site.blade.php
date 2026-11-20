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
        .page-blank {
            flex: 1;
            min-height: 0;
            background: var(--white);
        }
    </style>
    @stack('head')
</head>
<body class="@yield('body_class')">
    @include('partials.site-cursor')
    @include('partials.page-nav')
    @include('partials.blog-gate')
    @yield('content')
    @stack('scripts')
</body>
</html>
