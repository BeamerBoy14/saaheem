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
        @media (max-width: 860px) {
            .mgmt-pillars__list { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        /* ── Galerie ── */
        .mgmt-gallery {
            padding: clamp(3rem, 7vh, 5rem) 0;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
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
                        <li>
                            <span class="mgmt-pillars__num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="mgmt-pillars__name">{{ $pillar }}</span>
                        </li>
                    @endforeach
                </ol>
            </section>

            {{-- Galerie photos (public/ston) --}}
            @if ($gallery->isNotEmpty())
                <section class="mgmt-gallery">
                    <p class="mgmt__label">{{ __('site.management.gallery') }}</p>
                    <div class="mgmt-gallery__grid">
                        @foreach ($gallery as $src)
                            <button type="button" class="mgmt-gallery__item" data-src="{{ $src }}">
                                <img src="{{ $src }}" alt="{{ __('site.management.photo_alt') }}" loading="lazy">
                            </button>
                        @endforeach
                    </div>
                </section>
            @endif

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
