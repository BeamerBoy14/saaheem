@extends('layouts.site')

@section('title', __('site.about.title'))
@section('body_class', 'page-about page-dark')

@push('head')
    @include('partials.dark-page-head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,700;0,9..40,800;1,9..40,300&display=swap" rel="stylesheet">
    <style>
        /* Rappel : le thème rétro fixe html à 10px, donc 1rem = 10px ici */
        body.page-about { background: #0b0709; }
        .ab {
            flex: 1;
            font-family: 'DM Sans', system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
            overflow: clip;
        }
        /* Titres : pas de dégradé "fire" du thème rétro */
        .ab h1,
        .ab h2 {
            background-image: none;
            -webkit-text-fill-color: currentColor;
        }
        .ab__wrap {
            width: min(1180px, calc(100% - 4.8rem));
            margin-inline: auto;
        }
        @media (max-width: 640px) {
            .ab__wrap { width: calc(100% - 3.2rem); }
        }

        /* Apparition au scroll */
        .ab-reveal {
            opacity: 0;
            transform: translateY(32px);
            transition: opacity 0.9s ease, transform 0.9s cubic-bezier(.2, .7, .2, 1);
        }
        .ab-reveal.is-in { opacity: 1; transform: none; }
        @media (prefers-reduced-motion: reduce) {
            .ab-reveal { opacity: 1; transform: none; transition: none; }
        }

        /* ── 1. Hero vidéo (sticky : la section suivante passe par-dessus) ── */
        .ab-hero {
            position: sticky;
            top: 0;
            z-index: 0;
            height: 100svh;
            min-height: 480px;
            display: flex;
            align-items: flex-end;
            overflow: hidden;
            color: #fff;
        }
        .ab-hero__video {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: saturate(0.85) contrast(1.05);
        }
        .ab-hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(11, 7, 9, 0.15) 0%, rgba(11, 7, 9, 0.1) 40%, rgba(11, 7, 9, 0.85) 100%);
        }
        .ab-hero__body {
            position: relative;
            z-index: 1;
            padding-bottom: clamp(4rem, 8vh, 8rem);
        }
        .ab-hero__kicker {
            display: inline-block;
            margin: 0 0 1.6rem;
            padding: 0.4rem 0.96rem;
            background: var(--magenta);
            font-size: clamp(1.28rem, 1.5vw, 1.52rem);
            font-weight: 500;
            letter-spacing: 0.24em;
            text-transform: uppercase;
            color: #fff;
        }
        .ab-hero__title {
            margin: 0;
            max-width: 14ch;
            font-size: clamp(4.16rem, 7.5vw, 10.4rem);
            font-weight: 800;
            letter-spacing: -0.035em;
            line-height: 0.95;
            color: #fff;
        }
        .ab-hero__meta {
            margin: 2rem 0 0;
            font-size: clamp(1.52rem, 1.6vw, 1.76rem);
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.65);
        }
        .ab-hero__scroll {
            position: absolute;
            right: clamp(2rem, 3vw, 4rem);
            bottom: clamp(4rem, 8vh, 8rem);
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.8rem;
            font-size: 1.2rem;
            font-weight: 500;
            letter-spacing: 0.24em;
            text-transform: uppercase;
            color: #fff;
        }
        .ab-hero__scroll::after {
            content: '';
            width: 2px;
            height: 56px;
            background: var(--magenta);
            transform-origin: top;
            animation: ab-drop 1.6s ease-in-out infinite;
        }
        @keyframes ab-drop {
            0%, 100% { transform: scaleY(0.5); opacity: 0.4; }
            50%      { transform: scaleY(1);   opacity: 1; }
        }
        @media (max-width: 640px) {
            .ab-hero__scroll { display: none; }
        }

        /* Tout ce qui suit le hero recouvre la vidéo */
        .ab-sheet {
            position: relative;
            z-index: 1;
            background: #fff;
            color: #111;
        }

        /* ── 2. Nom géant ── */
        .ab-name {
            padding: clamp(4rem, 6vw, 7.2rem) 0 clamp(1.6rem, 3vw, 3.2rem);
        }
        .ab-name__tag {
            display: inline-block;
            margin: 0 0 0.8rem;
            padding: 0.32rem 0.96rem;
            background: var(--magenta);
            color: #fff;
            font-size: clamp(1.76rem, 2.4vw, 2.56rem);
            font-weight: 700;
            font-style: italic;
            letter-spacing: -0.01em;
        }
        .ab-name__word {
            margin: 0;
            font-size: clamp(4.8rem, 22.5vw, 28.5rem); /* ≈ pleine largeur du conteneur (DM Sans 800 ≈ 4.1em) */
            font-weight: 800;
            letter-spacing: -0.06em;
            line-height: 0.82;
            color: #111;
            white-space: nowrap;
        }

        @media (max-width: 640px) {
            .ab-name__word { font-size: 22vw; }
        }

        /* ── 3. Bandeau qui défile ── */
        .ab-marquee {
            overflow: hidden;
            background: var(--magenta);
            color: #fff;
            padding: 1.44rem 0;
        }
        .ab-marquee__track {
            display: flex;
            width: max-content;
            animation: ab-marquee 28s linear infinite;
        }
        .ab-marquee__track span {
            padding-right: 4rem;
            font-size: clamp(1.92rem, 3vw, 3.2rem);
            font-weight: 800;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            white-space: nowrap;
        }
        @keyframes ab-marquee {
            to { transform: translateX(-50%); }
        }
        @media (prefers-reduced-motion: reduce) {
            .ab-marquee__track { animation: none; }
        }

        /* ── 4. Blocs photo + phrase ── */
        .ab-split {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: min(88vh, 820px);
            border-bottom: 1px solid #eee;
        }
        .ab-split__media { position: relative; overflow: hidden; }
        .ab-split__media img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 1.2s ease;
        }
        .ab-split:hover .ab-split__media img { transform: scale(1.03); }
        .ab-split__copy {
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            gap: 2rem;
            padding: clamp(3.2rem, 5vw, 7.2rem);
        }
        .ab-split__num {
            font-size: 1.36rem;
            font-weight: 500;
            letter-spacing: 0.22em;
            color: var(--magenta);
        }
        .ab-split__line {
            margin: 0;
            font-size: clamp(3.04rem, 4vw, 5.44rem);
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.02;
            text-transform: uppercase;
            color: #111;
        }
        .ab-split__text {
            margin: 0;
            max-width: 48rem;
            font-size: clamp(1.68rem, 1.6vw, 1.92rem);
            font-weight: 300;
            line-height: 1.7;
            color: rgba(0, 0, 0, 0.6);
        }
        .ab-split--rev .ab-split__media { order: 2; }
        .ab-split--rev .ab-split__copy { text-align: right; align-items: flex-end; }
        @media (max-width: 760px) {
            .ab-split { grid-template-columns: 1fr; min-height: 0; }
            .ab-split__media { aspect-ratio: 4 / 5; }
            .ab-split--rev .ab-split__media { order: 0; }
            .ab-split--rev .ab-split__copy { text-align: left; align-items: flex-start; }
        }

        /* ── 5. Stay Weird plein écran ── */
        .ab-full {
            position: relative;
            min-height: 90vh;
            display: flex;
            align-items: center;
            color: #fff;
            overflow: hidden;
        }
        .ab-full__img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .ab-full::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(11, 7, 9, 0.85) 0%, rgba(11, 7, 9, 0.35) 60%, rgba(11, 7, 9, 0.1) 100%);
        }
        .ab-full__body { position: relative; z-index: 1; }
        .ab-full__kicker {
            margin: 0 0 1.6rem;
            font-size: 1.36rem;
            font-weight: 500;
            letter-spacing: 0.24em;
            text-transform: uppercase;
            color: var(--magenta);
        }
        .ab-full__title {
            margin: 0 0 2rem;
            font-size: clamp(4.8rem, 9vw, 12rem);
            font-weight: 800;
            letter-spacing: -0.05em;
            line-height: 0.9;
            color: #fff;
        }
        .ab-full__text {
            margin: 0 0 3.2rem;
            max-width: 48rem;
            font-size: clamp(1.68rem, 1.7vw, 2rem);
            font-weight: 300;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.8);
        }
        .ab-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.96rem;
            padding: 1.44rem 2.56rem;
            border-radius: 999px;
            background: var(--magenta);
            color: #fff;
            font-size: 1.44rem;
            font-weight: 500;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .ab-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(228, 0, 124, 0.35);
        }

        /* ── 6. Aujourd'hui + liens ── */
        .ab-now {
            padding: clamp(6.4rem, 10vh, 11.2rem) 0 clamp(4.8rem, 8vh, 8rem);
        }
        .ab-now__grid {
            display: grid;
            grid-template-columns: minmax(0, 5fr) minmax(0, 7fr);
            gap: clamp(3.2rem, 6vw, 8rem);
            align-items: center;
        }
        .ab-now__photo {
            aspect-ratio: 3 / 4;
            border-radius: 6px;
            overflow: hidden;
        }
        .ab-now__photo img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .ab-now__lead {
            margin: 0 0 2.4rem;
            font-size: clamp(2.56rem, 3.2vw, 4.16rem);
            font-weight: 700;
            letter-spacing: -0.025em;
            line-height: 1.15;
            color: #111;
        }
        .ab-now__roles {
            margin: 0 0 3.2rem;
            font-size: clamp(1.52rem, 1.5vw, 1.68rem);
            font-weight: 500;
            letter-spacing: 0.1em;
            line-height: 1.8;
            text-transform: uppercase;
            color: var(--magenta);
        }
        .ab-links {
            display: flex;
            flex-wrap: wrap;
            gap: 0.96rem;
            margin: 0 0 2.4rem;
            padding: 0;
            list-style: none;
        }
        .ab-links a {
            display: inline-block;
            padding: 0.96rem 1.76rem;
            border: 1.5px solid #111;
            border-radius: 999px;
            color: #111;
            font-size: 1.44rem;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            text-decoration: none;
            transition: background 0.2s, color 0.2s, border-color 0.2s;
        }
        .ab-links a:hover {
            background: var(--magenta);
            border-color: var(--magenta);
            color: #fff;
        }
        .ab-now__base {
            margin: 0;
            font-size: 1.44rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(0, 0, 0, 0.4);
        }
        @media (max-width: 760px) {
            .ab-now__grid { grid-template-columns: 1fr; }
            .ab-now__photo { max-width: 420px; }
        }
    </style>
@endpush

@section('content')
<main class="ab" role="main">


    {{-- 1. Hero vidéo --}}
    <section class="ab-hero">
        <video class="ab-hero__video" autoplay muted loop playsinline preload="metadata" poster="{{ asset('saaheem/1.2.jpg') }}" aria-hidden="true">
            <source src="{{ asset('videos/bk.mp4') }}" type="video/mp4">
        </video>
        <div class="ab__wrap ab-hero__body">
            <p class="ab-hero__kicker">Artist / Producer / Creative</p>
            <h1 class="ab-hero__title">Self-taught. I make things happen.</h1>
            <p class="ab-hero__meta">Born in France &middot; Based in Brussels</p>
        </div>
        <span class="ab-hero__scroll" aria-hidden="true">Scroll</span>
    </section>

    <div class="ab-sheet">

        {{-- 2. Nom géant --}}
        <section class="ab-name">
            <div class="ab__wrap">
                <p class="ab-name__tag ab-reveal">Be you.</p>
                <h2 class="ab-name__word ab-reveal">SAAHEEM</h2>
            </div>
        </section>

        {{-- 3. Bandeau --}}
        <div class="ab-marquee" aria-hidden="true">
            <div class="ab-marquee__track">
                @for ($i = 0; $i < 2; $i++)
                    <span>Producer &bull; DJ &bull; Shooter &bull; Connector &bull; Creative direction &bull; Events &bull; Artist development &bull;</span>
                @endfor
            </div>
        </div>

        {{-- 4. Blocs photo + phrase --}}
        <section class="ab-split">
            <div class="ab-split__media">
                <img src="{{ asset('saaheem/1.1.jpg') }}" alt="Saaheem" loading="lazy">
            </div>
            <div class="ab-split__copy ab-reveal">
                <span class="ab-split__num">01</span>
                <p class="ab-split__line">I shoot. I DJ. I produce. I create.</p>
                <p class="ab-split__text">I move between music, visuals, fashion, events and culture, but most of all, I make things happen.</p>
            </div>
        </section>

        <section class="ab-split ab-split--rev">
            <div class="ab-split__media">
                <img src="{{ asset('saaheem/1.4.jpg') }}" alt="Saaheem" loading="lazy">
            </div>
            <div class="ab-split__copy ab-reveal">
                <span class="ab-split__num">02</span>
                <p class="ab-split__line">I've always been the type to figure things out myself.</p>
                <p class="ab-split__text">Self-taught artist and producer. No shortcuts, just curiosity and work.</p>
            </div>
        </section>

        <section class="ab-split">
            <div class="ab-split__media">
                <img src="{{ asset('saaheem/1.3.jpg') }}" alt="Saaheem" loading="lazy">
            </div>
            <div class="ab-split__copy ab-reveal">
                <span class="ab-split__num">03</span>
                <p class="ab-split__line">I plug artists. I connect people.</p>
                <p class="ab-split__text">I find opportunities and build bridges between different scenes and cities.</p>
            </div>
        </section>

        {{-- 5. Stay Weird --}}
        <section class="ab-full">
            <img class="ab-full__img" src="{{ asset('saaheem/1.5.jpg') }}" alt="" loading="lazy">
            <div class="ab__wrap ab-full__body ab-reveal">
                <p class="ab-full__kicker">Founder</p>
                <h2 class="ab-full__title">Stay weird.</h2>
                <p class="ab-full__text">A creative universe built around music, image, people and self-expression.</p>
                <a class="ab-btn" href="{{ route('stay-weird') }}">Stay weird &rarr;</a>
            </div>
        </section>

        {{-- 6. Aujourd'hui + liens --}}
        <section class="ab-now">
            <div class="ab__wrap ab-now__grid">
                <div class="ab-now__photo ab-reveal">
                    <img src="{{ asset('saaheem/1.2.jpg') }}" alt="Saaheem" loading="lazy">
                </div>
                <div class="ab-reveal">
                    <p class="ab-now__lead">Today, I work with artists, studios, labels, models, brands, media and creative teams, helping create projects, develop artists, build connections and bring ideas to life.</p>
                    <p class="ab-now__roles">Artist / Producer / Creative direction / Visuals / Events / Artist development</p>
                    <ul class="ab-links">
                        <li><a href="https://youtu.be/vvKvbrI6Wi0?si=Mxx-fVNmc1f5Y6w3" target="_blank" rel="noopener">Interview</a></li>
                        <li><a href="https://on.soundcloud.com/2EgrpTGxALkaIH1A8e" target="_blank" rel="noopener">DJ Set</a></li>
                        <li><a href="https://youtube.com/@yungxboy?si=-au8fue6uFA_d6zf" target="_blank" rel="noopener">YouTube</a></li>
                        <li><a href="https://on.soundcloud.com/aXUMABrPf4hNvEGhhr" target="_blank" rel="noopener">SoundCloud</a></li>
                        <li><a href="https://www.instagram.com/saaheem__" target="_blank" rel="noopener">Instagram</a></li>
                        <li><a href="#" data-blog-gate>Blog</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                    <p class="ab-now__base">Based in Brussels &middot; Connected internationally</p>
                </div>
            </div>
        </section>

    </div>{{-- .ab-sheet --}}
</main>
@endsection

@push('scripts')
<script>
(function () {
    /* ── Apparition des blocs au scroll ── */
    var items = document.querySelectorAll('.ab-reveal');
    if (!('IntersectionObserver' in window)) {
        items.forEach(function (el) { el.classList.add('is-in'); });
        return;
    }
    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-in');
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });
    items.forEach(function (el) { io.observe(el); });
})();
</script>
@endpush
