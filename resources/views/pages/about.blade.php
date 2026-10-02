@extends('layouts.site')

@section('title', __('site.about.title'))
@section('body_class', 'page-about page-dark')

@push('head')
    @include('partials.dark-page-head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">
    <style>
        --about-font: 'DM Sans', system-ui, sans-serif;
        /* ── Header stays above fixed cards ── */
        .site-header { position: relative; z-index: 100; }

        .about-page { flex: 1; }

        /* ── Spacer creates scroll height (3 cards × 100vh) ── */
        .about-spacer { height: 300vh; }

        /* ── Fixed stack: always covers the viewport ── */
        .about-fixed {
            position: fixed;
            inset: 0;
            z-index: 5;
            pointer-events: none;
        }

        /* ── One card = full viewport ── */
        .about-card {
            position: absolute;
            inset: 0;
            pointer-events: auto;
            will-change: transform, filter, opacity;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* ── Backgrounds ── */
        .about-card__bg {
            position: absolute;
            inset: 0;
            z-index: 0;
        }
        .about-card__bg img,
        .about-card__bg video {
            width: 100%; height: 100%;
            object-fit: cover;
            display: block;
        }
        .about-card__overlay {
            position: absolute;
            inset: 0;
            z-index: 1;
        }

        /* ── Content wrapper ── */
        .about-card__body {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: clamp(1rem, 3vh, 1.75rem);
            padding: 2rem 1.5rem;
            width: min(900px, 100%);
        }
        .about-card__body--split {
            flex-direction: row;
            flex-wrap: wrap;
            text-align: left;
            align-items: center;
            justify-content: center;
            gap: clamp(2rem, 6vw, 5rem);
        }
        .about-card__body--split-rev {
            flex-direction: row-reverse;
        }
        @media (max-width: 680px) {
            .about-card__body--split,
            .about-card__body--split-rev { flex-direction: column; text-align: center; }
        }

        /* ── Photo frame ── */
        .about-card__photo {
            flex-shrink: 0;
            width: clamp(200px, 32vw, 340px);
            aspect-ratio: 3/4;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 16px 48px rgba(0,0,0,.08);
        }
        .about-card__photo img {
            width: 100%; height: 100%;
            object-fit: cover;
            display: block;
        }

        /* ── Text elements ── */
        .about-card__copy { flex: 1; min-width: min(240px, 100%); display: flex; flex-direction: column; gap: 1rem; }
        .about-card__kicker {
            margin: 0;
            font-family: 'DM Sans', system-ui, sans-serif;
            font-size: 0.68rem;
            font-weight: 500;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #e4007c;
        }
        .about-card__title {
            margin: 0;
            font-family: 'DM Sans', system-ui, sans-serif;
            font-size: clamp(4rem, 18vw, 10rem);
            font-weight: 300;
            letter-spacing: -0.02em;
            line-height: 0.9;
            color: #fff;
            text-transform: uppercase;
            text-shadow: 0 4px 40px rgba(0,0,0,.4);
        }
        .about-card__heading {
            margin: 0;
            font-family: 'DM Sans', system-ui, sans-serif;
            font-size: clamp(2rem, 7vw, 4rem);
            font-weight: 300;
            letter-spacing: -0.02em;
            line-height: 1.05;
            color: #111;
        }
        .about-card__sub {
            margin: 0;
            font-family: 'DM Sans', system-ui, sans-serif;
            font-size: clamp(0.72rem, 2vw, 0.85rem);
            font-weight: 400;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: rgba(255,255,255,.45);
        }
        .about-card__location {
            margin: 0;
            font-family: 'DM Sans', system-ui, sans-serif;
            font-size: 0.72rem;
            font-weight: 400;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(0,0,0,.38);
        }
        .about-card__text {
            margin: 0;
            font-family: 'DM Sans', system-ui, sans-serif;
            font-size: clamp(0.9rem, 2vw, 1rem);
            font-weight: 300;
            line-height: 1.8;
            color: rgba(0,0,0,.55);
            max-width: 30rem;
        }

        /* ── Cards 0 & 1: fond blanc ── */
        .about-card--0 { background: #fff; }
        .about-card--0 .about-card__overlay {
            background:
                radial-gradient(ellipse 70% 80% at 5% 65%, rgba(255,140,195,.38) 0%, rgba(255,180,220,.12) 40%, transparent 70%),
                radial-gradient(ellipse 40% 40% at 8% 62%, rgba(255,100,170,.22) 0%, transparent 50%);
        }
        .about-card--1 { background: #fff; }
        .about-card--1 .about-card__overlay {
            background:
                radial-gradient(ellipse 70% 80% at 95% 35%, rgba(255,140,195,.36) 0%, rgba(255,180,220,.10) 40%, transparent 70%),
                radial-gradient(ellipse 40% 40% at 92% 38%, rgba(255,100,170,.20) 0%, transparent 50%);
        }

        /* ── Card 2: fond blanc ── */
        .about-card--2 { background: #fafafa; }
        .about-card--2 .about-card__overlay {
            background:
                radial-gradient(ellipse 65% 65% at 50% 90%, rgba(255,140,195,.32) 0%, rgba(255,180,220,.10) 45%, transparent 70%),
                radial-gradient(ellipse 35% 35% at 50% 88%, rgba(255,100,170,.18) 0%, transparent 50%);
        }
        .about-card--2 .about-card__heading { color: #111; }
        .about-card--2 .about-card__text { color: rgba(0,0,0,.4); }

        @keyframes hint-drop {
            0%,100% { transform: scaleY(.6) translateY(-5px); opacity: .4; }
            50%      { transform: scaleY(1) translateY(0);     opacity: 1; }
        }

        /* ── Back button ── */
        .about-back {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.7rem 1.6rem;
            font-family: 'DM Sans', system-ui, sans-serif;
            font-size: 0.72rem;
            font-weight: 400;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #111;
            background: transparent;
            border: 1px solid rgba(0,0,0,.18);
            cursor: pointer;
            transition: background .2s, border-color .2s, color .2s;
        }
        .about-back:hover {
            background: #e4007c;
            border-color: #e4007c;
            color: #fff;
        }

        /* ── Progress dots ── */
        .about-dots {
            position: fixed;
            right: clamp(1rem, 3vw, 1.75rem);
            top: 50%;
            transform: translateY(-50%);
            z-index: 50;
            display: flex;
            flex-direction: column;
            gap: 0.55rem;
            pointer-events: none;
        }
        .about-dots__dot {
            width: 5px; height: 5px;
            border-radius: 50%;
            background: rgba(0,0,0,.18);
            transition: background .3s, transform .3s;
        }
        .about-dots__dot.is-active {
            background: #e4007c;
            transform: scale(1.5);
        }
    </style>
@endpush

@section('content')
<div class="about-page">

    {{-- Progress dots --}}
    <div class="about-dots" aria-hidden="true" id="about-dots">
        <span class="about-dots__dot"></span>
        <span class="about-dots__dot"></span>
        <span class="about-dots__dot"></span>
    </div>

    {{-- Spacer = scroll height --}}
    <div class="about-spacer" id="about-spacer"></div>

    {{-- Fixed cards stack --}}
    <div class="about-fixed" id="about-fixed">

        {{-- Card 0 --}}
        <div class="about-card about-card--0" id="about-card-0">
            <div class="about-card__overlay"></div>
            <div class="about-card__body about-card__body--split">
                <div class="about-card__photo">
                    <img src="{{ asset('saaheem/1.1.jpg') }}" alt="Saaheem" loading="lazy">
                </div>
                <div class="about-card__copy">
                    <p class="about-card__kicker">Artist / Producer / Creative</p>
                    <h2 class="about-card__heading">SAAHEEM</h2>
                    <p class="about-card__location">Born in France &middot; Based in Brussels</p>
                    <p class="about-card__text">I'm a self-taught artist, producer</p>
                    <p class="about-card__text">I move between music, visuals, fashion, events and culture but most of all, I make things happen</p>
                    <p class="about-card__text">I shoot. I DJ. I produce. I create.<br>I plug artists, connect people, find opportunities and build bridges between different scenes and cities</p>
                </div>
            </div>
        </div>

        {{-- Card 1 --}}
        <div class="about-card about-card--1" id="about-card-1">
            <div class="about-card__overlay"></div>
            <div class="about-card__body about-card__body--split about-card__body--split-rev">
                <div class="about-card__photo">
                    <img src="{{ asset('photos/IMG_1995.jpeg') }}" alt="" loading="lazy">
                </div>
                <div class="about-card__copy">
                    <p class="about-card__kicker">Chapitre 02</p>
                    <h2 class="about-card__heading">—</h2>
                    <p class="about-card__text">Cette section sera complétée prochainement.</p>
                </div>
            </div>
        </div>

        {{-- Card 2 — Fin --}}
        <div class="about-card about-card--2" id="about-card-2">
            <div class="about-card__overlay"></div>
            <div class="about-card__body">
                <p class="about-card__kicker">{{ __('site.about.soon') }}</p>
                <h2 class="about-card__heading">Bientôt.</h2>
                <p class="about-card__text">{{ __('site.about.lead') }}</p>
                <button type="button" class="about-back" id="about-back">{{ __('site.about.back') }}</button>
            </div>
        </div>

    </div>{{-- .about-fixed --}}
</div>
@endsection

@push('scripts')
<script>
(function () {
    /* ── Back ── */
    var backBtn = document.getElementById('about-back');
    if (backBtn) {
        backBtn.addEventListener('click', function () {
            window.history.length > 1 ? window.history.back() : (window.location.href = '{{ route('home') }}');
        });
    }

    /* ── Scroll-driven stacked cards ── */
    var spacer   = document.getElementById('about-spacer');
    var cards    = Array.from(document.querySelectorAll('.about-card'));
    var dots     = Array.from(document.querySelectorAll('.about-dots__dot'));
    var numCards = cards.length;
    var spacerTop; /* stable document offset */

    function measureSpacer() {
        spacerTop = spacer.getBoundingClientRect().top + window.scrollY;
    }

    function update() {
        var scrolled = window.scrollY - spacerTop; /* < 0 before track */
        var vh       = window.innerHeight;

        cards.forEach(function (card, i) {
            /* p = progress within this card's slot
               p < 0  → card not yet reached
               p 0-1  → card is active
               p > 1  → card passed (blur behind) */
            var p = scrolled / vh - i;

            /* Card 0 always fully visible before we enter the track */
            if (i === 0 && scrolled < 0) {
                card.style.transform = 'translateY(0)';
                card.style.filter    = 'blur(0px)';
                card.style.opacity   = '1';
                card.style.zIndex    = '10';
                return;
            }

            if (p < -0.25) {
                /* Waiting below — hidden */
                card.style.transform = 'translateY(100px)';
                card.style.opacity   = '0';
                card.style.filter    = 'blur(0px)';
                card.style.zIndex    = String(10 + i);
            } else if (p < 0) {
                /* Rising into view */
                var t = (p + 0.25) / 0.25;          /* 0 → 1 */
                card.style.transform = 'translateY(' + Math.round((1 - t) * 100) + 'px)';
                card.style.opacity   = String(t);
                card.style.filter    = 'blur(0px)';
                card.style.zIndex    = String(10 + i);
            } else if (p <= 1) {
                /* Active */
                card.style.transform = 'translateY(0)';
                card.style.opacity   = '1';
                card.style.filter    = 'blur(0px)';
                card.style.zIndex    = String(10 + i);
            } else {
                /* Past — blur & fade, stays in place */
                var excess  = p - 1;
                var blur    = Math.min(excess * 24, 24);
                var opacity = Math.max(1 - excess * 0.45, 0.15);
                card.style.transform = 'translateY(0)';
                card.style.opacity   = String(opacity);
                card.style.filter    = 'blur(' + blur + 'px)';
                card.style.zIndex    = String(i);
            }
        });

        /* Active dot */
        var active = Math.min(Math.max(Math.round(Math.max(scrolled, 0) / vh), 0), numCards - 1);
        dots.forEach(function (d, i) { d.classList.toggle('is-active', i === active); });
    }

    measureSpacer();
    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', function () { measureSpacer(); update(); }, { passive: true });
    update();
})();
</script>
@endpush
