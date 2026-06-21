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

                <li><a class="{{ $nav === 'about' ? 'is-active' : '' }}" href="{{ route('about') }}">{{ __('site.nav.about') }}</a></li>

                <li><a class="{{ $nav === 'moments' ? 'is-active' : '' }}" href="{{ route('moments') }}">{{ __('site.nav.moments') }}</a></li>

                <li><a class="{{ $nav === 'actualites' ? 'is-active' : '' }}" href="{{ route('actualites') }}">{{ __('site.nav.news') }}</a></li>

                <li><a class="{{ $nav === 'merch' ? 'is-active' : '' }}" href="{{ route('merch') }}">{{ __('site.nav.merch') }}</a></li>

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

