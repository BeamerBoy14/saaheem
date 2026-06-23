@extends('layouts.site')

@section('title', 'About me — SAAHEEM')
@section('body_class', 'page-about')

@push('head')

    <style>
        body.page-about {
            background: #1a0a14;
            color: #fff;
        }
        body.page-about .site-header {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            background: transparent;
            border-bottom: none;
        }
        body.page-about .logo {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            color: #fff;
        }
        body.page-about .logo::after {
            content: "";
            width: 14px;
            height: 14px;
            background: var(--magenta);
            clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
        }
        body.page-about .nav-main a {
            color: rgba(255, 255, 255, 0.85);
            border-bottom: none;
            position: relative;
            padding-bottom: 0.5rem;
        }
        body.page-about .nav-main a:hover { color: #fff; }
        body.page-about .nav-main a.is-active {
            color: #fff;
        }
        body.page-about .nav-main a.is-active::after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: 0;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--magenta);
            transform: translateX(-50%);
        }
        body.page-about .socials a { color: #fff; }
        body.page-about .socials a:hover { color: var(--magenta); }
        body.page-about .btn-outline,
        body.page-about .header-auth { display: none; }
        body.page-about .nav-toggle {
            border-color: rgba(255,255,255,.25);
            background: rgba(255,255,255,.06);
        }
        body.page-about .nav-toggle span { background: #fff; }
        @media (max-width: 960px) {
            body.page-about .nav-main {
                background: #111;
                border-bottom: 1px solid rgba(255,255,255,.08);
            }
            body.page-about .nav-main a { border-bottom: 1px solid rgba(255,255,255,.06); }
        }

        .about-page {
            position: relative;
            z-index: 1;
            flex: 1;
            min-height: 100vh;
            min-height: 100dvh;
            padding: clamp(5.5rem, 12vh, 7rem) clamp(1rem, 4vw, 2.5rem) clamp(3rem, 8vh, 5rem);
        }
        .about-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
            background: #1a0a14;
        }
        .about-bg__video {
            position: absolute;
            inset: -5%;
            width: 110%;
            height: 110%;
            object-fit: cover;
            opacity: 0.55;
            filter: blur(6px) brightness(0.4);
            will-change: transform;
            transform: translateZ(0);
        }
        .about-bg__shade {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 55% 45% at 78% 22%, rgba(228, 0, 124, 0.22) 0%, transparent 65%),
                linear-gradient(180deg, rgba(0,0,0,.38) 0%, rgba(26,10,20,.22) 45%, rgba(0,0,0,.42) 100%);
        }
        .about-bg__grain {
            position: absolute;
            inset: 0;
            opacity: 0.08;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.55'/%3E%3C/svg%3E");
        }
        .about-bg__vignette {
            position: absolute;
            inset: 0;
            box-shadow: inset 0 0 80px rgba(0, 0, 0, 0.45);
        }
        @media (prefers-reduced-motion: reduce) {
            .about-bg__video { display: none; }
        }
        @media (max-width: 720px) {
            /* Sur mobile : pas de vidéo de fond, trop lourd pour le GPU */
            .about-bg { position: absolute; }
            .about-bg__video { display: none; }
            .about-bg__grain { display: none; }
            .about-bg__vignette { display: none; }
        }
        .about-page__grid {
            position: relative;
            z-index: 2;
            width: min(1280px, 100%);
            margin: 0 auto;
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) minmax(0, 1.05fr);
            gap: clamp(1.5rem, 4vw, 3.5rem);
            align-items: start;
            --about-block-h: min(66vh, 620px);
        }
        @media (max-width: 1024px) {
            .about-page__grid {
                grid-template-columns: auto 1fr;
            }
            .about-page__visual { grid-column: 1 / -1; max-width: 420px; margin: 0 auto; }
        }
        @media (max-width: 720px) {
            html:has(body.page-about),
            body.page-about { overflow: hidden; height: 100dvh; }

            /* Page = exactement l'écran */
            .about-page {
                height: 100dvh;
                min-height: 0;
                overflow: hidden;
                padding: clamp(4.5rem, 10dvh, 6rem) clamp(1rem, 4vw, 1.5rem) 5.5rem;
                display: flex;
                flex-direction: column;
            }

            /* Grid = colonne flexible qui remplit l'espace restant */
            .about-page__grid {
                grid-template-columns: 1fr;
                gap: 0;
                flex: 1;
                min-height: 0;
                display: flex;
                flex-direction: column;
            }
            .about-timeline { display: none; }

            /* Carte : hauteur adaptative, max 38% de l'écran */
            .about-page__visual { order: -1; flex-shrink: 0; margin-bottom: 1.25rem; }
            .about-copy-col { max-width: 100%; width: 100%; flex: 1; min-height: 0; }
            .about-copy {
                width: 100%;
                max-width: 100%;
                height: auto;
                overflow-x: scroll;
                overflow-y: hidden;
                scroll-snap-type: x mandatory;
                -webkit-overflow-scrolling: touch;
            }
            .about-slides__track {
                display: flex;
                flex-direction: row;
                width: 100%;
            }
            .about-slide {
                flex: 0 0 100%;
                min-width: 0;
                height: auto;
                scroll-snap-align: start;
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                padding: 0 0.5rem 1.5rem;
                box-sizing: border-box;
            }
        }

        .about-timeline {
            height: var(--about-block-h);
            padding-top: 0;
        }
        .about-timeline__body {
            position: relative;
            height: 100%;
        }
        /* Rail aligné sur le centre du dot (left = moitié du dot 2.35rem) */
        .about-timeline__rail {
            position: absolute;
            top: 1.175rem;
            bottom: 1.175rem;
            left: 1.175rem;
            width: 1px;
            transform: translateX(-50%);
            background: linear-gradient(180deg, var(--magenta) 0%, rgba(255,255,255,.12) 85%, rgba(255,255,255,.08) 100%);
            pointer-events: none;
            z-index: 1;
        }
        .about-timeline__marker {
            position: absolute;
            left: 0;
            top: 0;
            width: 2.35rem;
            height: 2.35rem;
            border-radius: 50%;
            background: var(--magenta);
            box-shadow: 0 0 20px rgba(228, 0, 124, 0.55);
            transform: translateY(var(--marker-y, 0));
            transition: transform 0.45s cubic-bezier(0.34, 1.2, 0.64, 1);
            pointer-events: none;
            z-index: 2;
        }
        .about-timeline__steps {
            position: relative;
            z-index: 3;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: flex-start;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .about-timeline__steps > li {
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
        }
        .about-timeline__btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0;
            border: none;
            background: transparent;
            font-family: inherit;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.25s ease;
        }
        .about-timeline__btn:hover { opacity: 1; }

        /* Cercle avec mot-clé */
        .about-timeline__dot {
            flex-shrink: 0;
            width: 2.5rem;
            height: 2.5rem;
            display: grid;
            place-items: center;
            border-radius: 50%;
            font-size: 0.55rem;
            letter-spacing: 0.04em;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.07);
            transition: color 0.25s, border-color 0.25s, background 0.25s;
            position: relative;
            z-index: 3;
        }
        /* Label texte à droite du cercle */
        .about-timeline__label {
            font-size: 0.6rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.55);
            white-space: nowrap;
            transition: color 0.25s;
        }
        .about-timeline__btn:hover .about-timeline__dot {
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.12);
        }
        .about-timeline__btn:hover .about-timeline__label {
            color: rgba(255, 255, 255, 0.9);
        }
        .about-timeline__steps .about-timeline__btn.is-active .about-timeline__dot {
            color: #000;
            background: var(--magenta);
            border-color: var(--magenta);
        }
        .about-timeline__steps .about-timeline__btn.is-active .about-timeline__label {
            color: #fff;
        }

        /* Mobile nav — affiche seulement les dots, cache les labels */
        .about-copy--mobile-nav .about-timeline__label { display: none; }
        .about-copy--mobile-nav .about-timeline__dot {
            width: 2.5rem;
            height: 2.5rem;
            border: 1px solid rgba(255,255,255,.15);
            color: rgba(255,255,255,0.5);
        }
        .about-copy--mobile-nav .about-timeline__btn.is-active .about-timeline__dot {
            color: #0a0a0a;
            border-color: var(--magenta);
            background: var(--magenta);
        }
        .about-timeline__btn:focus-visible {
            outline: 2px solid var(--magenta);
            outline-offset: 3px;
        }

        .about-copy-col {
            max-width: 34rem;
            min-width: 0;
        }
        .about-copy {
            max-width: 34rem;
            --slide-h: var(--about-block-h);
            height: var(--slide-h);
            overflow-y: scroll;
            overflow-x: hidden;
            scroll-snap-type: y mandatory;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .about-copy::-webkit-scrollbar { display: none; }
        .about-slides__track {
            position: relative;
        }
        .about-slide {
            height: var(--slide-h);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 1.25rem 0;
            box-sizing: border-box;
            scroll-snap-align: start;
        }
        @media (prefers-reduced-motion: reduce) {
            .about-timeline__marker { transition: none; }
        }

        /* Override mobile — doit venir APRÈS les styles de base */
        @media (max-width: 720px) {
            .about-copy {
                max-width: 100%;
                width: 100%;
                height: 100%;
                overflow-x: scroll;
                overflow-y: hidden;
                scroll-snap-type: x mandatory;
                -webkit-overflow-scrolling: touch;
            }
            .about-slides__track {
                display: flex;
                flex-direction: row;
                height: 100%;
            }
            .about-slide {
                flex: 0 0 100%;
                min-width: 0;
                height: 100%;
                scroll-snap-align: start;
                justify-content: flex-start;
                padding: 1rem 0.5rem 1rem;
            }
        }

        .about-copy--mobile-nav {
            display: none;
        }
        @media (max-width: 720px) {
            .about-timeline { display: none; }

            /* Image décalée vers le bas */
            .about-page__visual {
                margin-top: 2rem;
            }

            /* Bulle glass fixée en bas */
            .about-copy--mobile-nav {
                display: flex;
                gap: 0.75rem;
                list-style: none;
                padding: 1rem 1.75rem;
                margin: 0;

                position: fixed;
                bottom: 2rem;
                left: 50%;
                transform: translateX(-50%);
                z-index: 50;

                /* Liquid glass */
                background: rgba(255, 255, 255, 0.08);
                backdrop-filter: blur(24px) saturate(1.6);
                -webkit-backdrop-filter: blur(24px) saturate(1.6);
                border: 1px solid rgba(255, 255, 255, 0.18);
                border-radius: 999px;
                box-shadow:
                    0 8px 32px rgba(0, 0, 0, 0.35),
                    inset 0 1px 0 rgba(255, 255, 255, 0.2);
            }
            .about-copy--mobile-nav .about-timeline__btn {
                width: 4.2rem;
                height: 4.2rem;
                border: 1px solid rgba(255, 255, 255, 0.15);
                border-radius: 50%;
                transition: background 0.2s, border-color 0.2s;
            }
            .about-copy--mobile-nav .about-timeline__dot {
                width: 4.2rem;
                height: 4.2rem;
                font-size: 0.62rem;
            }
            .about-copy--mobile-nav .about-timeline__btn.is-active .about-timeline__dot {
                color: #000;
                background: var(--magenta);
                border-color: var(--magenta);
            }
        }
        .about-copy__title {
            margin: 0 0 1.5rem;
            font-size: clamp(2.75rem, 7.5vw, 4.75rem);
            font-weight: 800;
            letter-spacing: 0.04em;
            line-height: 1;
            text-transform: uppercase;
        }
        .about-copy__title-about { color: #fff; }
        .about-copy__title-me {
            position: relative;
            display: inline-block;
        }
        .about-copy__title-me::after {
            content: "";
            position: absolute;
            left: -0.05em;
            right: -0.15em;
            bottom: 0.02em;
            height: 0.28em;
            background: var(--magenta);
            opacity: 0.9;
            z-index: -1;
            transform: rotate(-2deg);
        }
        .about-copy__text {
            margin: 0 0 2.25rem;
            font-size: clamp(0.95rem, 1.8vw, 1.12rem);
            font-weight: 400;
            line-height: 1.75;
            color: rgba(255, 255, 255, 0.88);
        }
        .about-cta {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.85rem 1.35rem;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            text-decoration: none;
            color: var(--magenta);
            /* annule le gradient chrome du retro-theme */
            background-image: none;
            -webkit-text-fill-color: var(--magenta);
            border: 1px solid var(--magenta);
            background-color: transparent;
            transition: background-color 0.2s, box-shadow 0.2s, -webkit-text-fill-color 0.2s;
        }
        .about-cta:hover {
            background-color: var(--magenta);
            -webkit-text-fill-color: #fff;
            box-shadow: 0 0 24px rgba(228, 0, 124, 0.45);
        }

        .about-visual {
            position: relative;
            padding: 0 clamp(2rem, 5vw, 4rem) clamp(1.5rem, 4vw, 2.5rem) 0;
            overflow: visible;
            align-self: center;
            margin-top: clamp(0.5rem, 2vh, 1.5rem);
        }
        .about-card {
            position: relative;
            border-radius: 18px;
            overflow: hidden;
            aspect-ratio: 3 / 4;
            max-height: min(72vh, 620px);
            border: 1px solid rgba(255, 255, 255, 0.35);
            box-shadow:
                0 0 0 1px rgba(228, 0, 124, 0.25),
                0 0 40px rgba(228, 0, 124, 0.35),
                0 0 80px rgba(228, 0, 124, 0.15);
            transition: box-shadow 0.25s ease;
        }
        .about-card__img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            filter: contrast(1.05) brightness(0.75);
            transition: opacity 0.4s ease;
        }
        .about-card__img.is-swapping {
            opacity: 0;
        }
        .about-card__overlay {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background: linear-gradient(180deg, rgba(0,0,0,.15) 0%, rgba(0,0,0,.45) 100%);
            text-align: center;
            pointer-events: none;
        }
        .about-card__question {
            margin: 0;
            font-family: inherit;
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 600;
            color: #fff;
            line-height: 1.1;
            text-shadow: 0 2px 20px rgba(0,0,0,.5);
        }
        .about-card__star {
            margin: 0.35rem 0 0.25rem;
            width: 18px;
            height: 18px;
            color: var(--magenta);
        }
        .about-card__scribble {
            width: min(12rem, 70%);
            height: 6px;
            background: var(--magenta);
            border-radius: 2px;
            transform: rotate(-3deg);
            opacity: 0.9;
        }

        .about-tape {
            position: absolute;
            z-index: 3;
            padding: 0.35rem 0.85rem;
            font-size: 0.62rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            background: var(--magenta);
            box-shadow: 0 4px 12px rgba(0,0,0,.35);
            transform: rotate(var(--r, -8deg));
        }
        .about-tape--visionary {
            top: 8%;
            right: -4%;
            --r: 12deg;
            color: #0a0a0a;
        }
        .about-tape--curious {
            bottom: 18%;
            right: -6%;
            --r: -6deg;
            color: #fff;
        }
        @media (max-width: 720px) {
            .about-tape--visionary { right: 0; }
            .about-tape--curious { right: 0; }
            .about-sticker { bottom: 0; left: 0; }
            .about-visual { padding: 0; margin: 0; }
            .about-card {
                aspect-ratio: 4 / 3;
                max-height: 38dvh;
                margin: 0 auto;
            }
        }

        .about-sticker {
            position: absolute;
            z-index: 4;
            bottom: -6%;
            left: -4%;
            width: clamp(72px, 14vw, 100px);
            height: clamp(72px, 14vw, 100px);
            border-radius: 50%;
            background: var(--magenta);
            display: grid;
            place-items: center;
            box-shadow: 4px 6px 0 rgba(255,255,255,.25), 0 8px 24px rgba(0,0,0,.4);
            transform: rotate(-12deg);
        }
        .about-sticker__face {
            font-size: 1.1rem;
            font-weight: 800;
            color: #0a0a0a;
            line-height: 0.85;
            text-align: center;
        }

    </style>
@endpush

@section('content')
    <main class="about-page" role="main">
        <div class="about-bg" aria-hidden="true">
            <video class="about-bg__video" autoplay muted loop playsinline>
                <source src="{{ asset('photos/vid2.mp4') }}" type="video/mp4">
            </video>
            <div class="about-bg__shade"></div>
            <div class="about-bg__grain"></div>
            <div class="about-bg__vignette"></div>
        </div>
        <div class="about-page__grid">
            <nav class="about-timeline" aria-label="Sections" id="about-timeline">
                <div class="about-timeline__body">
                    <span class="about-timeline__rail" aria-hidden="true"></span>
                    <span class="about-timeline__marker" id="about-timeline-marker" aria-hidden="true"></span>
                    <ul class="about-timeline__steps" role="tablist">
                    <li><button type="button" class="about-timeline__btn is-active" data-step="0" role="tab" aria-selected="true" aria-controls="about-panel-0" id="about-tab-0"><span class="about-timeline__dot" aria-hidden="true">ME</span><span class="about-timeline__label">About me</span></button></li>
                    <li><button type="button" class="about-timeline__btn" data-step="1" role="tab" aria-selected="false" aria-controls="about-panel-1" id="about-tab-1"><span class="about-timeline__dot" aria-hidden="true">VIS</span><span class="about-timeline__label">Ma vision</span></button></li>
                    <li><button type="button" class="about-timeline__btn" data-step="2" role="tab" aria-selected="false" aria-controls="about-panel-2" id="about-tab-2"><span class="about-timeline__dot" aria-hidden="true">LIVE</span><span class="about-timeline__label">Scène & live</span></button></li>
                    <li><button type="button" class="about-timeline__btn" data-step="3" role="tab" aria-selected="false" aria-controls="about-panel-3" id="about-tab-3"><span class="about-timeline__dot" aria-hidden="true">WAVE</span><span class="about-timeline__label">L'univers</span></button></li>
                    </ul>
                </div>
            </nav>

            <div class="about-copy-col">
                <ul class="about-copy--mobile-nav" role="tablist" aria-label="Sections" data-about-tabs-mobile></ul>
                <div class="about-copy" id="about-copy">
                <div class="about-slides__track" id="about-slides-track">
                    <article class="about-slide is-active" id="about-panel-0" role="tabpanel" aria-labelledby="about-tab-0">
                        <h1 class="about-copy__title">
                            <span class="about-copy__title-about">About </span><span class="about-copy__title-me">me</span>
                        </h1>
                        <p class="about-copy__text">
                            Je crée des moments qui restent gravés. Entre musique, énergie et vision, je façonne des expériences uniques, sans jamais rentrer dans les cases.
                        </p>
                        <a class="about-cta" href="{{ route('moments') }}">Discover my world <span aria-hidden="true">→</span></a>
                    </article>
                    <article class="about-slide" id="about-panel-1" role="tabpanel" aria-labelledby="about-tab-1" aria-hidden="true">
                        <h2 class="about-copy__title">
                            <span class="about-copy__title-about">Ma </span><span class="about-copy__title-me">vision</span>
                        </h2>
                        <p class="about-copy__text">
                            Une esthétique nocturne, sensorielle et sans compromis. Chaque projet cherche l'impact : image, son, rythme — tout doit vibrer au même tempo.
                        </p>
                        <a class="about-cta" href="{{ route('moments') }}">Voir les moments <span aria-hidden="true">→</span></a>
                    </article>
                    <article class="about-slide" id="about-panel-2" role="tabpanel" aria-labelledby="about-tab-2" aria-hidden="true">
                        <h2 class="about-copy__title">
                            <span class="about-copy__title-about">Scène &amp; </span><span class="about-copy__title-me">live</span>
                        </h2>
                        <p class="about-copy__text">
                            De la préparation à la diffusion, j'accompagne les expériences live : clips, captations, identités visuelles pensées pour la scène et le digital.
                        </p>
                        <a class="about-cta" href="{{ route('actualites') }}">L'actualité <span aria-hidden="true">→</span></a>
                    </article>
                    <article class="about-slide" id="about-panel-3" role="tabpanel" aria-labelledby="about-tab-3" aria-hidden="true">
                        <h2 class="about-copy__title">
                            <span class="about-copy__title-about">Rejoindre </span><span class="about-copy__title-me">l'univers</span>
                        </h2>
                        <p class="about-copy__text">
                            Collaborations, bookings, projets sur mesure — construisons ensemble la prochaine expérience. Le merch et les prochains drops arrivent très bientôt.
                        </p>
                        <a class="about-cta" href="{{ route('merch') }}">Découvrir le merch <span aria-hidden="true">→</span></a>
                    </article>
                </div>
                </div>
            </div>

            <div class="about-visual about-page__visual">
                <div class="about-card" id="about-card">
                    <img
                        class="about-card__img"
                        id="about-card-img"
                        src="{{ $stepImages[0] ?? 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=700&q=80' }}"
                        alt="Portrait Saaheem"
                        width="600"
                        height="800"
                        draggable="false"
                    >
                    <div class="about-card__overlay">
                        <p class="about-card__question">Who is Saaheem?</p>
                        <svg class="about-card__star" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 2l2.2 6.8H21l-5.5 4 2.1 6.7L12 17.5 6.4 19.5l2.1-6.7L3 8.8h6.8L12 2z"/>
                        </svg>
                        <span class="about-card__scribble" aria-hidden="true"></span>
                    </div>
                </div>
                <span class="about-tape about-tape--visionary">Visionary</span>
                <span class="about-tape about-tape--curious">Curious</span>
                <div class="about-sticker" aria-hidden="true">
                    <span class="about-sticker__face" aria-hidden="true">✕<br>✕</span>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        (function () {
            var timeline  = document.getElementById('about-timeline');
            var marker    = document.getElementById('about-timeline-marker');
            var track     = document.getElementById('about-slides-track');
            var copy      = document.getElementById('about-copy');
            var mobileTabsHost = document.querySelector('[data-about-tabs-mobile]');
            var cardImg   = document.getElementById('about-card-img');
            var stepImages = @json($stepImages ?? []);
            var questions  = ['Who is Saaheem?', 'Vision & style', 'Live energy', 'Join the wave'];
            var currentStep = 0;
            var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            function isMobile() {
                return window.matchMedia('(max-width: 720px)').matches;
            }

            function stepOffset(index) {
                if (!timeline || !marker) return 0;
                var body  = timeline.querySelector('.about-timeline__body');
                var steps = timeline.querySelector('.about-timeline__steps');
                var btn   = steps && steps.querySelector('.about-timeline__btn[data-step="' + index + '"]');
                if (!btn || !body) return 0;
                var btnRect  = btn.getBoundingClientRect();
                var bodyRect = body.getBoundingClientRect();
                var markerTop = parseFloat(getComputedStyle(marker).top) || 0;
                return btnRect.top - bodyRect.top + (btnRect.height - marker.offsetHeight) / 2 - markerTop;
            }

            function syncMarker(index) {
                if (marker) marker.style.setProperty('--marker-y', stepOffset(index) + 'px');
            }

            function syncButtons(index) {
                document.querySelectorAll('.about-timeline__btn[data-step]').forEach(function (btn) {
                    var on = parseInt(btn.getAttribute('data-step'), 10) === index;
                    btn.classList.toggle('is-active', on);
                    btn.setAttribute('aria-selected', on ? 'true' : 'false');
                });
            }

            function swapCard(index) {
                var q = document.querySelector('.about-card__question');
                if (q && questions[index]) q.textContent = questions[index];
                if (cardImg && stepImages[index] && cardImg.src !== stepImages[index]) {
                    cardImg.classList.add('is-swapping');
                    setTimeout(function () {
                        cardImg.src = stepImages[index];
                        cardImg.onload = function () { cardImg.classList.remove('is-swapping'); cardImg.onload = null; };
                    }, 200);
                }
            }

            function scrollToSlide(index) {
                if (!copy) return;
                var behavior = reducedMotion ? 'instant' : 'smooth';
                if (isMobile()) {
                    copy.scrollTo({ left: index * copy.clientWidth, behavior: behavior });
                } else {
                    copy.scrollTo({ top: index * copy.clientHeight, behavior: behavior });
                }
            }

            function goToStep(index) {
                index = Math.max(0, Math.min(3, index));
                currentStep = index;
                syncMarker(index);
                syncButtons(index);
                swapCard(index);
                scrollToSlide(index);
            }

            /* Sync UI when user scrolls/swipes manually */
            if (copy) {
                var ticking = false;
                copy.addEventListener('scroll', function () {
                    if (ticking) return;
                    ticking = true;
                    requestAnimationFrame(function () {
                        ticking = false;
                        var index = isMobile()
                            ? Math.round(copy.scrollLeft / Math.max(1, copy.clientWidth))
                            : Math.round(copy.scrollTop  / Math.max(1, copy.clientHeight));
                        index = Math.max(0, Math.min(3, index));
                        if (index !== currentStep) {
                            currentStep = index;
                            syncMarker(index);
                            syncButtons(index);
                            swapCard(index);
                        }
                    });
                });
            }

            /* Buttons (desktop timeline + mobile dots) */
            if (timeline) {
                if (mobileTabsHost && !mobileTabsHost.dataset.ready) {
                    mobileTabsHost.dataset.ready = '1';
                    document.querySelectorAll('#about-timeline .about-timeline__btn[data-step]').forEach(function (btn) {
                        var li = document.createElement('li');
                        var clone = btn.cloneNode(true);
                        clone.removeAttribute('id');
                        clone.removeAttribute('aria-controls');
                        li.appendChild(clone);
                        mobileTabsHost.appendChild(li);
                    });
                }
                document.querySelectorAll('.about-timeline__btn[data-step]').forEach(function (btn) {
                    if (btn.dataset.bound) return;
                    btn.dataset.bound = '1';
                    btn.addEventListener('click', function () {
                        goToStep(parseInt(btn.getAttribute('data-step'), 10));
                    });
                });
                syncMarker(0);
                syncButtons(0);
                requestAnimationFrame(function () { requestAnimationFrame(function () { syncMarker(0); }); });
            }

            /* Resize (debounced) */
            var resizeTimer;
            window.addEventListener('resize', function () {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function () { syncMarker(currentStep); }, 120);
            });

            /* Bloquer le scroll vertical sur mobile */
            if (isMobile()) {
                document.documentElement.style.overflow = 'hidden';
                document.body.style.overflow = 'hidden';
                document.addEventListener('touchmove', function (e) {
                    /* Autoriser uniquement le scroll horizontal dans .about-copy */
                    if (!e.target.closest('#about-copy')) {
                        e.preventDefault();
                    }
                }, { passive: false });
            }

        })();
    </script>
@endpush
