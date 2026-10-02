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
            .about-card__body--split-rev { flex-direction: column; text-align: center; align-items: center; }
            .about-card--0 .about-card__body {
                width: 100%;
                max-width: 100vw;
                padding: 0;
                gap: 1.5rem;
                overflow: hidden;
            }
            .about-card--0 .about-card__photo {
                width: 100vw;
                max-width: 100vw;
                height: 55vh;
                aspect-ratio: auto;
                border-radius: 0;
                box-shadow: none;
            }
            .about-card--0 .about-card__copy {
                padding: 0 1.5rem;
            }
            .about-card__name {
                font-size: clamp(2.5rem, 10vw, 4rem);
            }
        }

        /* ── Scroll hint ── */
        .about-scroll-hint {
            position: absolute;
            bottom: clamp(1.5rem, 4vh, 2.5rem);
            right: clamp(1.5rem, 3vw, 2.5rem);
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            pointer-events: none;
        }
        .about-scroll-hint__label {
            font-family: 'DM Sans', system-ui, sans-serif;
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: #e4007c;
        }
        .about-scroll-hint__line {
            width: 2px;
            height: 60px;
            background: #e4007c;
            transform-origin: top;
            animation: hint-drop 1.6s ease-in-out infinite;
        }

        /* ── Photo frame ── */
        .about-card__photo {
            flex-shrink: 0;
            width: clamp(200px, 32vw, 380px);
            aspect-ratio: 3/4;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 16px 48px rgba(0,0,0,.08);
        }
        /* Card 0 : contrainte par la hauteur pour ne pas déborder */
        .about-card--0 .about-card__photo {
            width: auto;
            height: clamp(300px, 72vh, 82vh);
        }
        .about-card--0 .about-card__body {
            width: min(1100px, calc(100% - 3rem));
            gap: clamp(2rem, 5vw, 5rem);
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
            font-size: clamp(1rem, 2.2vw, 1.4rem);
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
        .about-card__name {
            position: absolute;
            top: clamp(1.2rem, 3.5vh, 2.5rem);
            left: clamp(1.5rem, 4vw, 3rem);
            margin: 0;
            font-family: 'DM Sans', system-ui, sans-serif;
            font-size: clamp(4rem, 12vw, 9rem);
            font-weight: 300;
            letter-spacing: -0.03em;
            line-height: 1;
            z-index: 3;
            background: linear-gradient(135deg, #ff80c0 0%, #e4007c 35%, #ff55aa 60%, #ffb3d9 85%, #e4007c 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;
        }
        .about-card__heading {
            margin: 0;
            font-family: 'DM Sans', system-ui, sans-serif;
            font-size: clamp(3.5rem, 10vw, 7rem);
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
            font-size: clamp(1rem, 2.2vw, 1.4rem);
            font-weight: 400;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(0,0,0,.38);
        }
        .about-card__text {
            margin: 0;
            font-family: 'DM Sans', system-ui, sans-serif;
            font-size: clamp(1.45rem, 3.2vw, 2rem);
            font-weight: 300;
            line-height: 1.7;
            color: rgba(0,0,0,.55);
            max-width: 38rem;
        }
        .about-card__text--bold { font-weight: 600; color: #111; }

        /* ── Card 1: grid 2×2 ── */
        .about-card__grid {
            position: absolute;
            inset: 0;
            z-index: 2;
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: clamp(0.25rem, 0.6vw, 0.5rem);
            padding: clamp(0.25rem, 0.6vw, 0.5rem);
        }
        .about-card__grid-item {
            overflow: hidden;
            min-height: 0;
        }
        .about-card__grid-item img {
            width: 100%; height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.6s ease;
        }
        .about-card__grid-item:hover img {
            transform: scale(1.03);
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

        /* ── Card 2: body texte ── */
        .about-card__body--text {
            text-align: center;
            align-items: center;
            max-width: 680px;
            gap: clamp(0.9rem, 2vh, 1.4rem);
        }
        .about-card__text--lead {
            font-family: 'DM Sans', system-ui, sans-serif;
            font-size: clamp(1.4rem, 3.5vw, 2.2rem);
            font-weight: 300;
            line-height: 1.35;
            color: #111;
            margin: 0;
        }
        .about-card__links {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem 1.4rem;
            margin-top: 0.4rem;
        }
        .about-card__link {
            font-family: 'DM Sans', system-ui, sans-serif;
            font-size: clamp(0.72rem, 1.4vw, 0.88rem);
            font-weight: 500;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            text-decoration: none;
            color: #111;
            border-bottom: 1px solid rgba(0,0,0,.22);
            padding-bottom: 2px;
            transition: color 0.2s, border-color 0.2s;
        }
        .about-card__link:visited { color: #111; }
        .about-card__link:hover,
        .about-card__link:focus { color: #e4007c; border-color: #e4007c; }

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
        .about-back--lg {
            padding: 1rem 5rem;
            font-size: 0.82rem;
            letter-spacing: 0.22em;
            margin-top: clamp(1.5rem, 4vh, 3rem);
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
            <h2 class="about-card__name">SAAHEEM</h2>
            <div class="about-scroll-hint" aria-hidden="true">
                <span class="about-scroll-hint__label">Scroll</span>
                <span class="about-scroll-hint__line"></span>
            </div>
            <div class="about-card__body about-card__body--split">
                <div class="about-card__photo">
                    <img src="{{ asset('saaheem/1.1.jpg') }}" alt="Saaheem" loading="lazy">
                </div>
                <div class="about-card__copy">
                    <p class="about-card__kicker">Artist / Producer / Creative</p>
                    <p class="about-card__location">Born in France &middot; Based in Brussels</p>
                    <p class="about-card__text">I'm a self-taught artist, producer</p>
                    <p class="about-card__text">I move between music, visuals, fashion, events and culture but most of all, I make things happen</p>
                    <p class="about-card__text">I shoot. I DJ. I produce. I create.<br>I plug artists, connect people, find opportunities and build bridges between different scenes and cities</p>
                </div>
            </div>
        </div>

        {{-- Card 1 — pleine page --}}
        <div class="about-card about-card--1" id="about-card-1">
            <div class="about-card__bg">
                <img src="{{ asset('saaheem/1.2.jpg') }}" alt="" loading="lazy">
            </div>
            <div class="about-card__overlay"></div>
        </div>

        {{-- Card 2 — Bio suite + liens --}}
        <div class="about-card about-card--2" id="about-card-2">
            <div class="about-card__overlay"></div>
            <div class="about-card__body about-card__body--text">
                <p class="about-card__text--lead">I've always been the type to figure things out myself</p>
                <p class="about-card__text">I'm also the founder of Stay Weird, a creative universe built around music, image, people and self-expression</p>
                <p class="about-card__text">Today, I work with artists, studios, labels, models, brands, media and creative teams helping create projects, develop artists, build connections and bring ideas to life</p>
                <p class="about-card__text about-card__text--bold">Artist / Producer / Creative Direction / Visuals / Events / Artist Development</p>
                <p class="about-card__location">Based in Brussels &middot; Connected internationally</p>
                <div class="about-card__links">
                    <a href="https://youtube.com/@yungxboy?si=-au8fue6uFA_d6zf" target="_blank" rel="noopener" class="about-card__link">YouTube</a>
                    <a href="https://on.soundcloud.com/aXUMABrPf4hNvEGhhr" target="_blank" rel="noopener" class="about-card__link">SoundCloud</a>
                    <a href="#" id="about-blog-trigger" class="about-card__link">Blog</a>
                    <a href="https://www.instagram.com/saaheem__?stkn=MTd6ZWF5MnFucjE0MQ%3D%3D&utm_source=qr" target="_blank" rel="noopener" class="about-card__link">Instagram</a>
                </div>
                <a href="{{ route('home') }}" class="about-back about-back--lg">Menu</a>
            </div>
        </div>

    </div>{{-- .about-fixed --}}
</div>
@endsection

@push('scripts')
<script>
(function () {
    /* ── Blog gate (même gate que le header) ── */
    var aboutBlogTrigger = document.getElementById('about-blog-trigger');
    var blogGateTrigger  = document.getElementById('blog-gate-trigger');
    if (aboutBlogTrigger && blogGateTrigger) {
        aboutBlogTrigger.addEventListener('click', function (e) {
            e.preventDefault();
            blogGateTrigger.click();
        });
    }

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
