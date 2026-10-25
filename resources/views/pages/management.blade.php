@extends('layouts.site')

@section('title', __('site.management.title'))
@section('body_class', 'page-management page-dark')

@php
    $heroPhoto = $photos->first();
    $gallery   = $photos->slice(1)->values();
@endphp

@push('head')
    @include('partials.dark-page-head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,700;1,9..40,300&display=swap" rel="stylesheet">
    <style>
        body.page-management { background: #0b0709; }
        .mgmt {
            flex: 1;
            position: relative;
            font-family: 'DM Sans', system-ui, sans-serif;
            color: rgba(255, 255, 255, 0.88);
            background:
                radial-gradient(ellipse 55% 45% at 90% 5%, rgba(228, 0, 124, 0.14) 0%, transparent 65%),
                radial-gradient(ellipse 45% 40% at 0% 100%, rgba(228, 0, 124, 0.08) 0%, transparent 60%),
                #0b0709;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }
        /* Titres : pas de dégradé "fire" du thème rétro, blanc franc */
        .mgmt h1,
        .mgmt h2 {
            background-image: none;
            -webkit-text-fill-color: currentColor;
        }
        /* Grain léger pour l'esthétique sombre */
        .mgmt::before {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            opacity: 0.07;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
        }
        .mgmt__inner {
            position: relative;
            width: min(1180px, calc(100% - 3rem));
            margin-inline: auto;
        }
        @media (max-width: 640px) {
            .mgmt__inner { width: calc(100% - 2rem); }
        }
        .mgmt__label {
            margin: 0 0 1.25rem;
            font-size: clamp(0.85rem, 1.6vw, 1rem);
            font-weight: 500;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--magenta);
        }

        /* ── Hero ── */
        .mgmt-hero {
            display: grid;
            grid-template-columns: minmax(0, 5fr) minmax(0, 6fr);
            gap: clamp(2rem, 6vw, 5rem);
            align-items: center;
            padding: clamp(3rem, 8vh, 6rem) 0 clamp(3rem, 6vh, 4.5rem);
        }
        .mgmt-hero__photo {
            position: relative;
            aspect-ratio: 3 / 4;
            border-radius: 6px;
            overflow: hidden;
            background: linear-gradient(160deg, #1d0f17 0%, #0b0709 100%);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.6);
        }
        .mgmt-hero__photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            filter: contrast(1.05) saturate(0.9);
        }
        .mgmt-hero__photo::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 55%, rgba(11, 7, 9, 0.75) 100%);
            pointer-events: none;
        }
        .mgmt-hero__placeholder {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: clamp(3rem, 9vw, 6rem);
            font-weight: 700;
            letter-spacing: -0.04em;
            color: rgba(255, 255, 255, 0.06);
        }
        .mgmt-hero__badge {
            position: absolute;
            left: 1.1rem;
            bottom: 1rem;
            z-index: 1;
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.75);
        }
        .mgmt-hero__name {
            margin: 0;
            font-size: clamp(5rem, 15vw, 11rem);
            font-weight: 700;
            letter-spacing: -0.05em;
            line-height: 0.85;
            color: #fff;
        }
        .mgmt-hero__name span { color: var(--magenta); -webkit-text-fill-color: var(--magenta); }
        .mgmt-hero__meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.6rem 1.25rem;
            margin: 1.5rem 0 2rem;
        }
        .mgmt-hero__location {
            font-size: clamp(0.95rem, 1.8vw, 1.1rem);
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.5);
        }
        .mgmt-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.45rem;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .mgmt-tags li {
            padding: 0.3rem 0.8rem;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 999px;
            font-size: 0.85rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.75);
        }
        .mgmt-hero__text {
            margin: 0 0 1.25rem;
            max-width: 36rem;
            font-size: clamp(1.15rem, 2vw, 1.35rem);
            font-weight: 300;
            line-height: 1.75;
            color: rgba(255, 255, 255, 0.78);
        }
        .mgmt-hero__quote {
            position: relative;
            margin: 2rem 0 2.25rem;
            padding-left: 1.4rem;
            border-left: 2px solid var(--magenta);
        }
        .mgmt-hero__quote p {
            margin: 0;
            max-width: 34rem;
            font-size: clamp(1.15rem, 2vw, 1.35rem);
            font-style: italic;
            font-weight: 300;
            line-height: 1.75;
            color: #fff;
        }
        .mgmt-hero__quote cite {
            display: block;
            margin-top: 0.75rem;
            font-size: 0.85rem;
            font-style: normal;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.45);
        }
        .mgmt-more {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.9rem 1.6rem;
            background: var(--magenta);
            color: #fff;
            border: none;
            border-radius: 999px;
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: 500;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .mgmt-more:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(228, 0, 124, 0.35);
        }
        .mgmt-more__icon {
            display: inline-block;
            transition: transform 0.3s;
        }
        .mgmt-more[aria-expanded="true"] .mgmt-more__icon { transform: rotate(180deg); }

        @media (max-width: 860px) {
            .mgmt-hero { grid-template-columns: 1fr; }
            .mgmt-hero__photo { max-width: 460px; width: 100%; margin-inline: auto; }
        }

        /* ── Bio dépliable ── */
        .mgmt-bio {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows 0.6s ease;
        }
        .mgmt-bio.is-open { grid-template-rows: 1fr; }
        .mgmt-bio__clip { overflow: hidden; }
        .mgmt-bio__body {
            padding: clamp(2rem, 5vh, 3.5rem) 0 clamp(3rem, 6vh, 4.5rem);
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }
        .mgmt-bio__cols {
            columns: 2 22rem;
            column-gap: clamp(2rem, 5vw, 4rem);
        }
        .mgmt-bio__cols p {
            margin: 0 0 1.4rem;
            break-inside: avoid;
            font-size: clamp(1.05rem, 1.8vw, 1.2rem);
            font-weight: 300;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.72);
        }
        .mgmt-bio__cols p:first-child::first-letter {
            float: left;
            margin: 0.1rem 0.6rem 0 0;
            font-size: 4.2rem;
            font-weight: 700;
            line-height: 0.8;
            color: var(--magenta);
        }

        /* ── Piliers du management ── */
        .mgmt-pillars {
            padding: clamp(3rem, 7vh, 5rem) 0;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }
        .mgmt-pillars__list {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1px;
            margin: 0;
            padding: 0;
            list-style: none;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .mgmt-pillars__list li {
            display: flex;
            flex-direction: column;
            gap: 1.75rem;
            padding: clamp(1.5rem, 3vw, 2.25rem);
            background: #0b0709;
            transition: background 0.3s;
        }
        .mgmt-pillars__list li:hover { background: #170b12; }
        .mgmt-pillars__num {
            font-size: 0.85rem;
            font-weight: 500;
            letter-spacing: 0.2em;
            color: var(--magenta);
        }
        .mgmt-pillars__name {
            font-size: clamp(1.3rem, 2.4vw, 1.75rem);
            font-weight: 400;
            line-height: 1.2;
            color: #fff;
        }
        /* Pilier cliquable (Musique) */
        .mgmt-pillars__list li:has(.mgmt-pillars__btn) { padding: 0; }
        .mgmt-pillars__btn {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 1.75rem;
            width: 100%;
            height: 100%;
            padding: clamp(1.5rem, 3vw, 2.25rem);
            background: transparent;
            border: none;
            color: inherit;
            font-family: inherit;
            text-align: left;
            cursor: pointer;
        }
        .mgmt-pillars__open {
            margin-top: auto;
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--magenta);
        }
        .mgmt-pillars__list li:has(.mgmt-pillars__btn[aria-pressed="true"]) {
            background: var(--magenta);
        }
        .mgmt-pillars__btn[aria-pressed="true"] .mgmt-pillars__num,
        .mgmt-pillars__btn[aria-pressed="true"] .mgmt-pillars__open { color: #fff; }
        @media (max-width: 860px) {
            .mgmt-pillars__list { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        /* ── Scène : Univers / Musique ── */
        .mgmt-stage {
            padding: clamp(3rem, 7vh, 5rem) 0;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            scroll-margin-top: 80px;
        }
        .mgmt-panel { animation: mgmt-fade 0.45s ease; }
        .mgmt-panel[hidden] { display: none; }
        @keyframes mgmt-fade {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: none; }
        }
        .mgmt-panel__head {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 1rem;
        }
        .mgmt-panel__back {
            padding: 0;
            background: none;
            border: none;
            font-family: inherit;
            font-size: 0.85rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.55);
            cursor: pointer;
        }
        .mgmt-panel__back:hover { color: #fff; }

        /* ── Musique : liste + lecteur ── */
        .mgmt-music {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1.1fr);
            gap: clamp(1.5rem, 4vw, 3.5rem);
            align-items: start;
        }
        .mgmt-music__sub {
            margin: 0 0 0.5rem;
            font-size: 0.8rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.4);
        }
        .mgmt-tracks {
            margin: 0;
            padding: 0;
            list-style: none;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }
        .mgmt-track {
            display: grid;
            grid-template-columns: 2.25rem minmax(0, 1fr) auto;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            padding: 0.95rem 0.5rem;
            background: none;
            border: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.82);
            font-family: inherit;
            text-align: left;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }
        .mgmt-track:hover { background: rgba(255, 255, 255, 0.04); color: #fff; }
        .mgmt-track__num {
            font-size: 0.85rem;
            letter-spacing: 0.1em;
            color: rgba(255, 255, 255, 0.35);
        }
        .mgmt-track__title {
            font-size: clamp(1rem, 1.7vw, 1.15rem);
            font-weight: 500;
            letter-spacing: 0.02em;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .mgmt-track__time {
            font-size: 0.85rem;
            font-variant-numeric: tabular-nums;
            color: rgba(255, 255, 255, 0.4);
        }
        .mgmt-track.is-active { color: var(--magenta); }
        .mgmt-track.is-active .mgmt-track__num { color: var(--magenta); }
        .mgmt-music__spotify {
            display: inline-block;
            margin-top: 1.5rem;
            font-size: 0.85rem;
            font-weight: 500;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: #fff;
            text-decoration: none;
            border-bottom: 2px solid #1ed760;
            padding-bottom: 0.25rem;
        }
        .mgmt-music__spotify:hover { color: #1ed760; }
        .mgmt-music__player {
            position: sticky;
            top: 90px;
        }
        .mgmt-music__player iframe {
            display: block;
            border-radius: 12px;
            background: #170b12;
        }
        /* Variante vidéo : vignettes + lecteur 16:9 */
        .mgmt-music--video { grid-template-columns: minmax(0, 1fr) minmax(0, 1.6fr); }
        .mgmt-track--video {
            grid-template-columns: 6.5rem minmax(0, 1fr) auto;
            padding-block: 0.7rem;
        }
        .mgmt-track__thumb {
            width: 100%;
            aspect-ratio: 16 / 9;
            object-fit: cover;
            border-radius: 4px;
            display: block;
            opacity: 0.75;
            transition: opacity 0.2s;
        }
        .mgmt-track--video:hover .mgmt-track__thumb,
        .mgmt-track--video.is-active .mgmt-track__thumb { opacity: 1; }
        .mgmt-track--video.is-active .mgmt-track__thumb { outline: 2px solid var(--magenta); outline-offset: 2px; }
        .mgmt-music--video .mgmt-music__player iframe {
            height: auto;
            aspect-ratio: 16 / 9;
        }
        .mgmt-music__spotify--yt { border-bottom-color: #ff0033; }

        /* Variante liens (Direction artistique) */
        .mgmt-music--links { grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr); }
        .mgmt-track--link { text-decoration: none; }
        .mgmt-track--link .mgmt-track__title small {
            display: block;
            margin-top: 0.15rem;
            font-size: 0.85rem;
            font-weight: 400;
            letter-spacing: 0.04em;
            color: rgba(255, 255, 255, 0.45);
        }
        .mgmt-track--link .mgmt-track__num { color: var(--brand); }
        .mgmt-track--link:hover .mgmt-track__time { color: var(--brand); transform: translate(2px, -2px); }
        .mgmt-track--link .mgmt-track__time { transition: transform 0.2s, color 0.2s; font-size: 1.1rem; }
        .mgmt-linkcard {
            position: sticky;
            top: 90px;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2.25rem 1.5rem 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            background:
                radial-gradient(ellipse 80% 50% at 50% 0%, rgba(228, 0, 124, 0.22) 0%, transparent 70%),
                #120a0f;
            text-align: center;
        }
        .mgmt-linkcard__avatar {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            overflow: hidden;
            background: #1d0f17;
            border: 2px solid var(--magenta);
        }
        .mgmt-linkcard__avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .mgmt-linkcard__handle {
            margin: 1rem 0 0;
            font-size: 0.85rem;
            letter-spacing: 0.14em;
            color: rgba(255, 255, 255, 0.5);
        }
        .mgmt-linkcard__name {
            margin: 0.2rem 0 1.5rem;
            font-size: 2.4rem;
            font-weight: 700;
            letter-spacing: -0.04em;
            line-height: 1;
            color: #fff;
        }
        .mgmt-linkcard__name span { color: var(--magenta); }
        .mgmt-linkcard__links {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            width: 100%;
            margin: 0 0 1.75rem;
            padding: 0;
            list-style: none;
        }
        .mgmt-linkcard__links a {
            display: block;
            padding: 0.8rem 1rem;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 999px;
            font-size: 0.95rem;
            font-weight: 500;
            letter-spacing: 0.08em;
            color: #fff;
            text-decoration: none;
            transition: border-color 0.2s, background 0.2s;
        }
        .mgmt-linkcard__links a:hover {
            border-color: var(--brand);
            background: rgba(255, 255, 255, 0.04);
        }
        .mgmt-linkcard__cta { text-decoration: none; }
        .mgmt-music__spotify--yt:hover { color: #ff4d6d; }
        @media (max-width: 860px) {
            .mgmt-music { grid-template-columns: 1fr; }
            .mgmt-music--video .mgmt-music__player iframe { height: auto; }
            .mgmt-linkcard { position: static; }
            /* Sur mobile le lecteur passe au-dessus de la liste */
            .mgmt-music__player { position: static; order: -1; }
            .mgmt-music__player iframe { height: 352px; }
        }
        .mgmt-gallery__grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(240px, 100%), 1fr));
            gap: 0.75rem;
        }
        .mgmt-gallery__item {
            display: block;
            aspect-ratio: 4 / 5;
            padding: 0;
            border: none;
            border-radius: 4px;
            overflow: hidden;
            background: #170b12;
            cursor: zoom-in;
        }
        .mgmt-gallery__item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            filter: grayscale(35%);
            transition: transform 0.6s ease, filter 0.4s;
        }
        .mgmt-gallery__item:hover img {
            transform: scale(1.03);
            filter: grayscale(0%);
        }

        /* ── Lightbox ── */
        .mgmt-lightbox {
            position: fixed;
            inset: 0;
            z-index: 500;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: rgba(5, 3, 4, 0.94);
            cursor: zoom-out;
        }
        .mgmt-lightbox.is-open { display: flex; }
        .mgmt-lightbox img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 4px;
        }

        /* ── Contact ── */
        .mgmt-cta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            padding: clamp(2.5rem, 6vh, 4rem) 0 clamp(3rem, 8vh, 5rem);
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }
        .mgmt-cta__title {
            margin: 0;
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 700;
            letter-spacing: -0.03em;
            color: #fff;
        }
        .mgmt-cta__link {
            font-size: 1rem;
            font-weight: 500;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #fff;
            text-decoration: none;
            border-bottom: 2px solid var(--magenta);
            padding-bottom: 0.3rem;
        }
        .mgmt-cta__link:hover { color: var(--magenta); }
    </style>
@endpush

@section('content')
    <main class="mgmt" role="main">
        <div class="mgmt__inner">

            {{-- Hero : présentation STON! + rôle du management --}}
            <section class="mgmt-hero" aria-labelledby="mgmt-name">
                <div class="mgmt-hero__photo">
                    @if ($heroPhoto)
                        <img src="{{ $heroPhoto }}" alt="{{ __('site.management.photo_alt') }}">
                    @else
                        <span class="mgmt-hero__placeholder" aria-hidden="true">STON!</span>
                    @endif
                    <span class="mgmt-hero__badge">{{ __('site.management.location') }}</span>
                </div>

                <div class="mgmt-hero__copy">
                    <p class="mgmt__label">{{ __('site.management.kicker') }}</p>
                    <h1 class="mgmt-hero__name" id="mgmt-name">STON<span>!</span></h1>
                    <div class="mgmt-hero__meta">
                        <span class="mgmt-hero__location">{{ __('site.management.location') }}</span>
                        <ul class="mgmt-tags">
                            @foreach (__('site.management.tags') as $tag)
                                <li>{{ $tag }}</li>
                            @endforeach
                        </ul>
                    </div>

                    @php $intro = __('site.management.intro'); @endphp
                    <p class="mgmt-hero__text">{{ $intro[0] }}</p>
                    <blockquote class="mgmt-hero__quote">
                        <p>{{ $intro[1] }}</p>
                        <cite>{{ __('site.management.kicker') }}</cite>
                    </blockquote>

                    <button type="button" class="mgmt-more" id="mgmt-more"
                        aria-expanded="false" aria-controls="mgmt-bio"
                        data-more="{{ __('site.management.more') }}" data-less="{{ __('site.management.less') }}">
                        <span class="mgmt-more__label">{{ __('site.management.more') }}</span>
                        <span class="mgmt-more__icon" aria-hidden="true">↓</span>
                    </button>
                </div>
            </section>

            {{-- Biographie complète (dépliée via "En savoir +") --}}
            <section class="mgmt-bio" id="mgmt-bio" aria-label="{{ __('site.management.bio_label') }}">
                <div class="mgmt-bio__clip">
                    <div class="mgmt-bio__body">
                        <p class="mgmt__label">{{ __('site.management.bio_label') }}</p>
                        <div class="mgmt-bio__cols">
                            @foreach (__('site.management.bio') as $paragraph)
                                <p>{{ $paragraph }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            {{-- Axes du management --}}
            <section class="mgmt-pillars">
                <p class="mgmt__label">{{ __('site.management.pillars_label') }}</p>
                <ol class="mgmt-pillars__list">
                    @foreach (__('site.management.pillars') as $i => $pillar)
                        @php
                            $num = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
                            // Piliers cliquables : Musique → Spotify, Image → Univers (photos),
                            // Projets visuels → YouTube, Direction artistique → liens du Linktree
                            $panel = [0 => 'music', 1 => 'univers', 2 => 'visuals', 3 => 'direction'][$i] ?? null;
                            $openKey = ['music' => 'pillar_open', 'univers' => 'pillar_view', 'visuals' => 'pillar_watch', 'direction' => 'pillar_explore'][$panel] ?? null;
                        @endphp
                        @if ($panel)
                            <li>
                                <button type="button" class="mgmt-pillars__btn" data-panel="{{ $panel }}"
                                    aria-pressed="false" aria-controls="mgmt-stage">
                                    <span class="mgmt-pillars__num">{{ $num }}</span>
                                    <span class="mgmt-pillars__name">{{ $pillar }}</span>
                                    <span class="mgmt-pillars__open">{{ __('site.management.' . $openKey) }} ↓</span>
                                </button>
                            </li>
                        @else
                            <li>
                                <span class="mgmt-pillars__num">{{ $num }}</span>
                                <span class="mgmt-pillars__name">{{ $pillar }}</span>
                            </li>
                        @endif
                    @endforeach
                </ol>
            </section>

            {{-- Scène : Univers (photos) par défaut, les autres panneaux au clic sur leur pilier --}}
            <section class="mgmt-stage" id="mgmt-stage" data-active="univers">
                <div class="mgmt-panel" data-panel="univers">
                    <p class="mgmt__label">{{ __('site.management.gallery') }}</p>
                    @if ($gallery->isNotEmpty())
                        <div class="mgmt-gallery__grid">
                            @foreach ($gallery as $src)
                                <button type="button" class="mgmt-gallery__item" data-src="{{ $src }}">
                                    <img src="{{ $src }}" alt="{{ __('site.management.photo_alt') }}" loading="lazy">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="mgmt-panel" data-panel="music" hidden>
                    <div class="mgmt-panel__head">
                        <p class="mgmt__label">{{ __('site.management.music_label') }}</p>
                        <button type="button" class="mgmt-panel__back" data-panel="univers">{{ __('site.management.back_univers') }}</button>
                    </div>
                    <div class="mgmt-music">
                        <div class="mgmt-music__list">
                            <p class="mgmt-music__sub">{{ __('site.management.tracks_label') }}</p>
                            <ol class="mgmt-tracks">
                                @foreach ($tracks as $i => $track)
                                    <li>
                                        <button type="button" class="mgmt-track" data-embed="https://open.spotify.com/embed/track/{{ $track['id'] }}?utm_source=generator&theme=0">
                                            <span class="mgmt-track__num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                            <span class="mgmt-track__title">{{ $track['title'] }}</span>
                                            <span class="mgmt-track__time">{{ $track['duration'] }}</span>
                                        </button>
                                    </li>
                                @endforeach
                            </ol>
                            <a class="mgmt-music__spotify" href="https://open.spotify.com/artist/{{ $spotifyArtist }}" target="_blank" rel="noopener">{{ __('site.management.open_spotify') }} ↗</a>
                        </div>
                        <div class="mgmt-music__player">
                            {{-- src posé au premier affichage pour ne pas charger Spotify inutilement --}}
                            <iframe title="{{ __('site.management.player_title') }}"
                                data-src="https://open.spotify.com/embed/artist/{{ $spotifyArtist }}?utm_source=generator&theme=0"
                                width="100%" height="452" frameborder="0" loading="lazy"
                                allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"></iframe>
                        </div>
                    </div>
                </div>

                <div class="mgmt-panel" data-panel="visuals" hidden>
                    <div class="mgmt-panel__head">
                        <p class="mgmt__label">{{ __('site.management.visuals_label') }}</p>
                        <button type="button" class="mgmt-panel__back" data-panel="univers">{{ __('site.management.back_univers') }}</button>
                    </div>
                    <div class="mgmt-music mgmt-music--video">
                        <div class="mgmt-music__list">
                            <p class="mgmt-music__sub">{{ __('site.management.videos_label') }}</p>
                            <ol class="mgmt-tracks">
                                @foreach ($videos as $i => $video)
                                    <li>
                                        <button type="button" class="mgmt-track mgmt-track--video{{ $i === 0 ? ' is-active' : '' }}"
                                            data-embed="https://www.youtube-nocookie.com/embed/{{ $video['id'] }}?rel=0&autoplay=1">
                                            <img class="mgmt-track__thumb" src="https://i.ytimg.com/vi/{{ $video['id'] }}/mqdefault.jpg" alt="" loading="lazy">
                                            <span class="mgmt-track__title">{{ $video['title'] }}</span>
                                            <span class="mgmt-track__time">{{ $video['duration'] }}</span>
                                        </button>
                                    </li>
                                @endforeach
                            </ol>
                            <a class="mgmt-music__spotify mgmt-music__spotify--yt" href="{{ $youtubeChannel }}" target="_blank" rel="noopener">{{ __('site.management.open_youtube') }} ↗</a>
                        </div>
                        <div class="mgmt-music__player">
                            <iframe title="{{ __('site.management.video_title') }}"
                                data-src="https://www.youtube-nocookie.com/embed/{{ $videos->first()['id'] }}?rel=0"
                                width="100%" frameborder="0" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"
                                allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>

                <div class="mgmt-panel" data-panel="direction" hidden>
                    <div class="mgmt-panel__head">
                        <p class="mgmt__label">{{ __('site.management.direction_label') }}</p>
                        <button type="button" class="mgmt-panel__back" data-panel="univers">{{ __('site.management.back_univers') }}</button>
                    </div>
                    <div class="mgmt-music mgmt-music--links">
                        <div class="mgmt-music__list">
                            <p class="mgmt-music__sub">{{ __('site.management.links_label') }}</p>
                            <ol class="mgmt-tracks">
                                @foreach ($linktree['links'] as $i => $link)
                                    <li>
                                        <a class="mgmt-track mgmt-track--link" href="{{ $link['url'] }}" target="_blank" rel="noopener" style="--brand: {{ $link['color'] }}">
                                            <span class="mgmt-track__num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                            <span class="mgmt-track__title">{{ $link['name'] }}<small>{{ $link['handle'] }}</small></span>
                                            <span class="mgmt-track__time" aria-hidden="true">↗</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                        {{-- Carte façon Linktree (le vrai Linktree refuse d'être affiché en iframe) --}}
                        @php $linkPhoto = $photos->first(fn ($src) => str_contains($src, 'IMG_1950')) ?? $heroPhoto; @endphp
                        <div class="mgmt-linkcard">
                            <div class="mgmt-linkcard__avatar">
                                @if ($linkPhoto)
                                    <img src="{{ $linkPhoto }}" alt="{{ __('site.management.photo_alt') }}" loading="lazy">
                                @endif
                            </div>
                            <p class="mgmt-linkcard__handle">{{ $linktree['handle'] }}</p>
                            <p class="mgmt-linkcard__name">STON<span>!</span></p>
                            <ul class="mgmt-linkcard__links">
                                @foreach ($linktree['links'] as $link)
                                    <li><a href="{{ $link['url'] }}" target="_blank" rel="noopener" style="--brand: {{ $link['color'] }}">{{ $link['name'] }}</a></li>
                                @endforeach
                            </ul>
                            <a class="mgmt-more mgmt-linkcard__cta" href="{{ $linktree['url'] }}" target="_blank" rel="noopener">{{ __('site.management.open_linktree') }} ↗</a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mgmt-cta">
                <h2 class="mgmt-cta__title">{{ __('site.management.contact') }}</h2>
                <a class="mgmt-cta__link" href="{{ route('contact') }}">{{ __('site.nav.contact') }} →</a>
            </section>
        </div>

        <div class="mgmt-lightbox" id="mgmt-lightbox" role="dialog" aria-modal="true" aria-label="{{ __('site.management.photo_alt') }}">
            <img src="" alt="{{ __('site.management.photo_alt') }}">
        </div>
    </main>

    <script>
        (function () {
            var btn = document.getElementById('mgmt-more');
            var bio = document.getElementById('mgmt-bio');
            if (btn && bio) {
                btn.addEventListener('click', function () {
                    var open = !bio.classList.contains('is-open');
                    bio.classList.toggle('is-open', open);
                    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
                    btn.querySelector('.mgmt-more__label').textContent = open ? btn.dataset.less : btn.dataset.more;
                    if (open) {
                        setTimeout(function () { bio.scrollIntoView({ behavior: 'smooth', block: 'start' }); }, 250);
                    }
                });
            }

            // Piliers ↔ panneaux de la scène (Univers, Musique, Projets visuels)
            var stage = document.getElementById('mgmt-stage');
            var pillarBtns = document.querySelectorAll('.mgmt-pillars__btn');
            function showPanel(name, scroll) {
                stage.dataset.active = name;
                stage.querySelectorAll('.mgmt-panel').forEach(function (panel) {
                    var hide = panel.dataset.panel !== name;
                    // Panneau masqué : recharger son lecteur coupe la lecture en cours
                    if (hide && !panel.hidden) {
                        panel.querySelectorAll('iframe[src]').forEach(function (frame) {
                            frame.src = frame.src.replace('&autoplay=1', '');
                        });
                    }
                    panel.hidden = hide;
                });
                pillarBtns.forEach(function (b) {
                    b.setAttribute('aria-pressed', b.dataset.panel === name ? 'true' : 'false');
                });
                // Les lecteurs (Spotify / YouTube) ne se chargent qu'au premier affichage
                stage.querySelectorAll('.mgmt-panel[data-panel="' + name + '"] iframe[data-src]').forEach(function (frame) {
                    if (!frame.getAttribute('src')) frame.src = frame.dataset.src;
                });
                if (scroll) stage.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
            pillarBtns.forEach(function (b) {
                b.addEventListener('click', function () {
                    showPanel(stage.dataset.active === b.dataset.panel ? 'univers' : b.dataset.panel, true);
                });
            });
            stage.querySelectorAll('.mgmt-panel__back').forEach(function (b) {
                b.addEventListener('click', function () { showPanel(b.dataset.panel, false); });
            });

            // Clic sur un titre / clip : le charge dans le lecteur de son panneau
            document.querySelectorAll('.mgmt-track[data-embed]').forEach(function (track) {
                track.addEventListener('click', function () {
                    var panel = track.closest('.mgmt-panel');
                    var player = panel.querySelector('.mgmt-music__player iframe');
                    panel.querySelectorAll('.mgmt-track.is-active').forEach(function (t) { t.classList.remove('is-active'); });
                    track.classList.add('is-active');
                    player.src = track.dataset.embed;
                    if (window.matchMedia('(max-width: 860px)').matches) {
                        player.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                });
            });

            var lightbox = document.getElementById('mgmt-lightbox');
            var lightboxImg = lightbox.querySelector('img');
            document.querySelectorAll('.mgmt-gallery__item').forEach(function (item) {
                item.addEventListener('click', function () {
                    lightboxImg.src = item.dataset.src;
                    lightbox.classList.add('is-open');
                });
            });
            function closeLightbox() {
                lightbox.classList.remove('is-open');
                lightboxImg.src = '';
            }
            lightbox.addEventListener('click', closeLightbox);
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeLightbox();
            });
        })();
    </script>
@endsection
