@extends('layouts.site')

@section('title', __('site.moments.title'))
@section('body_class', 'page-moments page-dark')

@push('head')
    @include('partials.dark-page-head')
    <style>
        /* ── Page shell ── */
        .moments-page {
            flex: 1;
            position: relative;
            padding: clamp(2rem, 5vw, 3.5rem) 0 clamp(3rem, 8vw, 5rem);
        }
        .moments-page,
        .moments-page__intro { background: transparent; }
        .moments-page__intro {
            max-width: 42rem;
            margin: 0 auto clamp(1rem, 3vw, 2rem);
            text-align: center;
            padding: 0 1rem;
        }
        .moments-page__kicker {
            margin: 0 0 0.6rem;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--magenta);
        }
        .moments-page__title {
            margin: 0 0 0.75rem;
            font-size: clamp(1.5rem, 4vw, 2.15rem);
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #fff;
            line-height: 1.15;
        }
        .moments-page__lead {
            margin: 0;
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.62);
            line-height: 1.6;
        }

        /* ── Hide nav & page content when overlay is open ── */
        body.cat-overlay-open .site-header,
        body.cat-overlay-open .moments-page { visibility: hidden; }
        body.cat-overlay-open { overflow: hidden; }

        /* ── Category overlay ── */
        .moments-cat-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(0, 0, 0, 0.88);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
            transition: opacity 0.35s ease;
            overflow: hidden;
        }
        .moments-cat-overlay.is-hidden {
            opacity: 0;
            pointer-events: none;
        }
        .moments-cat-overlay__bg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.55;
            pointer-events: none;
            z-index: 0;
            filter: blur(4px);
            transform: scale(1.05);
        }
        .moments-cat-overlay__veil {
            position: absolute;
            inset: 0;
            z-index: 1;
            background: rgba(0, 0, 0, 0.50);
            pointer-events: none;
        }

        /* ── Steps ── */
        .cat-step {
            width: 100%;
            max-width: 540px;
            max-height: 80vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
            scrollbar-width: none;
        }
        .cat-step::-webkit-scrollbar { display: none; }
        .cat-step[hidden] { display: none !important; }

        .moments-cat__display {
            min-height: clamp(2.8rem, 9vw, 4.5rem);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: clamp(0.5rem, 2vw, 1rem);
            width: 100%;
        }
        .moments-cat__display-text {
            font-family: var(--font-retro, 'Press Start 2P', monospace);
            font-size: clamp(1.8rem, 10vw, 4rem);
            background-image: var(--retro-chrome, linear-gradient(135deg, #fff 0%, #e4007c 50%, #fff 100%));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            text-align: center;
            animation: cat-pulse 1.2s ease-in-out infinite;
            transition: opacity 0.12s ease;
        }
        .moments-cat__display-text.is-blank { opacity: 0; animation: none; }

        .moments-cat__list {
            list-style: none;
            margin: 0;
            padding: 0;
            width: 100%;
        }
        .moments-cat__item {
            font-family: var(--font-retro, 'Press Start 2P', monospace);
            font-size: clamp(0.9rem, 3.8vw, 1.3rem);
            padding: clamp(0.65rem, 2vw, 0.9rem) 1.25rem;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 0.75rem;
            color: rgba(255, 255, 255, 0.45);
            cursor: pointer;
            user-select: none;
            -webkit-user-select: none;
            border-radius: 6px;
            transition: color 0.15s ease;
            text-align: left;
        }
        .moments-cat__item::before { content: '▶'; font-size: 0.8rem; color: var(--magenta); opacity: 0; transition: opacity 0.15s; flex-shrink: 0; }
        .moments-cat__item::after  { display: none; }
        .moments-cat__item.is-highlighted,
        .moments-cat__item:hover { color: #fff; animation: cat-pulse 1.2s ease-in-out infinite; }
        .moments-cat__item.is-highlighted::before,
        .moments-cat__item:hover::before,
        .moments-cat__item.is-highlighted::after,
        .moments-cat__item:hover::after { opacity: 1; }

        @keyframes cat-pulse {
            0%, 100% { opacity: 1; filter: drop-shadow(0 0 6px rgba(228, 0, 124, 0.6)); }
            50%       { opacity: 0.55; filter: drop-shadow(0 0 2px rgba(228, 0, 124, 0.2)); }
        }

        /* ── Filter bar ── */
        .moments-filter-bar {
            display: none;
            justify-content: center;
            gap: 0.6rem;
            flex-wrap: wrap;
            margin: 0 auto clamp(1.5rem, 4vw, 2.5rem);
            padding: 0 1rem;
        }
        .moments-filter-bar.is-visible { display: flex; }
        .moments-filter-btn {
            font-family: var(--font-retro, 'Press Start 2P', monospace);
            font-size: clamp(0.55rem, 2vw, 0.72rem);
            padding: 0.55rem 1.1rem;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 999px;
            background: transparent;
            color: rgba(255, 255, 255, 0.45);
            cursor: pointer;
            transition: color 0.18s, border-color 0.18s, background 0.18s;
        }
        .moments-filter-btn.is-active,
        .moments-filter-btn:hover {
            color: #fff;
            border-color: var(--magenta);
            background: rgba(228, 0, 124, 0.1);
        }

        /* ── Gallery ── */
        .moments-gallery-wrap { display: none; }
        .moments-gallery-wrap.is-visible { display: block; }

        .moments-section { width: min(1720px, calc(100% - 1.25rem)); margin: 0 auto; }
        .moments-masonry { columns: 5; column-gap: 1rem; }
        @media (max-width: 1400px) { .moments-masonry { columns: 4; } }
        @media (max-width: 1100px) { .moments-masonry { columns: 3; } }
        @media (max-width: 720px) {
            .moments-masonry { columns: 2; column-gap: 0.5rem; }
            .moments-section { width: calc(100% - 0.75rem); }
            .moments-masonry__item { margin-bottom: 0.5rem; border-radius: 8px; }
        }
        .moments-masonry__item {
            margin: 0 0 1rem;
            break-inside: avoid;
            border-radius: 10px;
            overflow: hidden;
            background: #111;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .moments-masonry__item.is-filtered-out { display: none; }
        @media (hover: hover) {
            .moments-masonry__item:hover {
                transform: translateY(-3px);
                box-shadow:
                    0 16px 40px rgba(0, 0, 0, 0.45),
                    0 0 0 1px rgba(228, 0, 124, 0.35),
                    0 0 24px rgba(228, 0, 124, 0.15);
            }
        }
        .moments-masonry__item:active {
            box-shadow: 0 0 0 1px rgba(228, 0, 124, 0.5), 0 0 24px rgba(228, 0, 124, 0.25);
        }
        .moments-masonry__media {
            display: block; width: 100%; height: auto;
            vertical-align: middle; border-radius: 10px;
        }
        .moments-masonry__item--video .moments-masonry__media {
            pointer-events: none; object-fit: cover;
        }
        .moments-page__empty {
            text-align: center; padding: 3rem 1.5rem;
            color: rgba(255, 255, 255, 0.55); font-size: 0.95rem;
        }
        .moments-page__empty a { color: var(--magenta); font-weight: 700; }
    </style>
@endpush

@section('content')
    {{-- ── Category overlay ── --}}
    <div class="moments-cat-overlay" id="moments-cat-overlay" role="dialog" aria-modal="true" aria-label="Choisir une catégorie">
        <video class="moments-cat-overlay__bg" autoplay muted loop playsinline preload="auto" aria-hidden="true">
            <source src="{{ asset('momentoum/azer.mp4') }}" type="video/mp4">
        </video>
        <div class="moments-cat-overlay__veil"></div>

        {{-- Étape 1 : type --}}
        <div id="cat-step-1" class="cat-step">
            <div class="moments-cat__display">
                <span class="moments-cat__display-text is-blank" id="cat-display-1">&nbsp;</span>
            </div>
            <ul class="moments-cat__list">
                <li class="moments-cat__item" data-action="all"   data-label="Tout">Tout</li>
                <li class="moments-cat__item" data-action="image" data-label="Photo">Photo</li>
                <li class="moments-cat__item" data-action="video" data-label="Vidéo">Vidéo</li>
            </ul>
        </div>

        {{-- Étape 2 : sous-catégories (peuplée dynamiquement) --}}
        <div id="cat-step-2" class="cat-step" hidden>
            <div class="moments-cat__display">
                <span class="moments-cat__display-text is-blank" id="cat-display-2">&nbsp;</span>
            </div>
            <ul class="moments-cat__list" id="cat-sublist"></ul>
            <button id="cat-back-btn" style="margin-top:1.2rem;align-self:center;background:none;border:1px solid rgba(255,255,255,0.2);border-radius:999px;cursor:pointer;font-family:var(--font-retro,'Press Start 2P',monospace);font-size:clamp(0.45rem,1.6vw,0.6rem);color:rgba(255,255,255,0.45);padding:0.55rem 1.2rem;position:relative;z-index:2;transition:color 0.15s,border-color 0.15s;" onmouseover="this.style.color='#fff';this.style.borderColor='var(--magenta)'" onmouseout="this.style.color='rgba(255,255,255,0.45)';this.style.borderColor='rgba(255,255,255,0.2)'">◀ Retour</button>
        </div>
    </div>

    <main class="moments-page dark-page__surface" role="main" aria-label="{{ __('site.moments.aria') }}">
        <header class="moments-page__intro">
            <p class="moments-page__kicker">{{ __('site.moments.kicker') }}</p>
            <h1 class="moments-page__title">{{ __('site.moments.heading') }}</h1>
            <p class="moments-page__lead">{{ __('site.moments.lead') }}</p>
        </header>

        <div class="moments-filter-bar" id="moments-filter-bar" role="group" aria-label="Filtrer"></div>

        @if (count($media ?? []) > 0)
            <div class="moments-gallery-wrap" id="moments-gallery-wrap">
                <section class="moments-section" aria-label="{{ __('site.moments.gallery') }}">
                    <div class="moments-masonry">
                        @foreach ($media as $item)
                            <figure
                                class="moments-masonry__item{{ $item['type'] === 'video' ? ' moments-masonry__item--video' : '' }}"
                                data-media-type="{{ $item['type'] }}"
                                data-media-cat="{{ $item['category'] ?? 'uncategorized' }}"
                            >
                                @if ($item['type'] === 'video')
                                    <video
                                        class="moments-masonry__media"
                                        src="{{ asset($item['folder'] . '/' . $item['file']) }}"
                                        autoplay muted loop playsinline preload="metadata"
                                        aria-hidden="true"
                                    ></video>
                                @else
                                    <img
                                        class="moments-masonry__media"
                                        src="{{ asset($item['folder'] . '/' . $item['file']) }}"
                                        alt="" loading="lazy" decoding="async"
                                    >
                                @endif
                            </figure>
                        @endforeach
                    </div>
                </section>
            </div>
        @else
            <div class="moments-page__empty wrap">
                <p>{!! __('site.moments.empty') !!}</p>
                <p style="margin-top:1rem"><a href="{{ route('home') }}">{{ __('site.moments.back_home') }}</a></p>
            </div>
        @endif
    </main>
@endsection

@push('scripts')
    <script>
    (function () {
        document.body.classList.add('cat-overlay-open');

        var overlay     = document.getElementById('moments-cat-overlay');
        var step1       = document.getElementById('cat-step-1');
        var step2       = document.getElementById('cat-step-2');
        var display1    = document.getElementById('cat-display-1');
        var display2    = document.getElementById('cat-display-2');
        var sublist     = document.getElementById('cat-sublist');
        var filterBar   = document.getElementById('moments-filter-bar');
        var galleryWrap = document.getElementById('moments-gallery-wrap');
        var allItems    = document.querySelectorAll('.moments-masonry__item');

        var subcats = {
            image: [
                { label: 'Fashion week',       slug: 'fashion-week' },
                { label: 'Concert / Festival', slug: 'concert-festival' },
                { label: 'Brand',              slug: 'brand' },
                { label: 'Portrait',           slug: 'portrait' },
                { label: 'Soirée',             slug: 'soiree' },
                { label: 'Shoot',              slug: 'shoot' },
                { label: 'Personal Emotions',  slug: 'personal-emotions' },
            ],
            video: [
                { label: 'Artist',     slug: 'artist' },
                { label: 'Clip',       slug: 'clip' },
                { label: 'Brand',      slug: 'brand' },
                { label: 'Interview',  slug: 'interview' },
                { label: 'Aftermovie', slug: 'aftermovie' },
                { label: 'Life',       slug: 'life' },
            ],
        };

        /* ── Filtering ── */
        function applyFilter(type, cat) {
            allItems.forEach(function (item) {
                var t = item.dataset.mediaType;
                var c = item.dataset.mediaCat;
                var show = type === 'all' || (t === type && (cat === 'all' || c === cat));
                item.classList.toggle('is-filtered-out', !show);
            });
            document.querySelectorAll('.moments-masonry__item--video:not(.is-filtered-out) video').forEach(function (v) {
                v.play().catch(function () {});
            });
        }

        function buildFilterBar(type, cat) {
            filterBar.innerHTML = '';
            if (type === 'all') return;
            subcats[type].forEach(function (sc) {
                var btn = document.createElement('button');
                btn.className = 'moments-filter-btn' + (sc.slug === cat ? ' is-active' : '');
                btn.textContent = sc.label;
                btn.addEventListener('click', function () {
                    applyFilter(type, sc.slug);
                    buildFilterBar(type, sc.slug);
                });
                filterBar.appendChild(btn);
            });
        }

        function openGallery(type, cat) {
            overlay.classList.add('is-hidden');
            document.body.classList.remove('cat-overlay-open');
            filterBar.classList.add('is-visible');
            galleryWrap.classList.add('is-visible');
            applyFilter(type, cat);
            buildFilterBar(type, cat);
            initVideoObservers();
        }

        /* ── GMS binder ── */
        function bindItems(items, display, onSelect) {
            items.forEach(function (item) {
                item.addEventListener('mouseenter', function () {
                    display.textContent = item.dataset.label;
                    display.classList.remove('is-blank');
                });
                item.addEventListener('mouseleave', function () {
                    display.classList.add('is-blank');
                });
                item.addEventListener('click', function () {
                    items.forEach(function (i) { i.classList.remove('is-highlighted'); });
                    item.classList.add('is-highlighted');
                    onSelect(item);
                });
                item.addEventListener('touchstart', function (e) {
                    e.preventDefault();
                    items.forEach(function (i) { i.classList.remove('is-highlighted'); });
                    item.classList.add('is-highlighted');
                    display.textContent = item.dataset.label;
                    display.classList.remove('is-blank');
                    onSelect(item);
                }, { passive: false });
            });
        }

        /* ── Back button ── */
        document.getElementById('cat-back-btn').addEventListener('click', function () {
            step2.hidden = true;
            step1.hidden = false;
            display1.textContent = ' ';
            display1.classList.add('is-blank');
            step1.querySelectorAll('.moments-cat__item').forEach(function (i) { i.classList.remove('is-highlighted'); });
        });

        /* ── Step 1 ── */
        bindItems(step1.querySelectorAll('.moments-cat__item'), display1, function (item) {
            var action = item.dataset.action;
            if (action === 'all') {
                openGallery('all', 'all');
                return;
            }
            /* build step 2 */
            sublist.innerHTML = '';
            subcats[action].forEach(function (sc) {
                var li = document.createElement('li');
                li.className = 'moments-cat__item';
                li.dataset.label = sc.label;
                li.dataset.slug  = sc.slug;
                li.textContent   = sc.label;
                sublist.appendChild(li);
            });
            step1.hidden = true;
            step2.hidden = false;
            display2.textContent = ' ';
            display2.classList.add('is-blank');

            bindItems(sublist.querySelectorAll('.moments-cat__item'), display2, function (sub) {
                openGallery(action, sub.dataset.slug);
            });
        });

        /* ── Video observers ── */
        function initVideoObservers() {
            document.querySelectorAll('.moments-masonry__item--video video').forEach(function (video) {
                var item = video.closest('.moments-masonry__item');
                video.addEventListener('error', function () { if (item) item.style.display = 'none'; });
                setTimeout(function () { if (video.readyState === 0 && item) item.style.display = 'none'; }, 3000);
                var play = function () { if (!item.classList.contains('is-filtered-out')) video.play().catch(function () {}); };
                play();
                if ('IntersectionObserver' in window) {
                    new IntersectionObserver(function (entries) {
                        entries.forEach(function (e) { if (e.isIntersecting) play(); else video.pause(); });
                    }, { threshold: 0.2 }).observe(video);
                }
            });
        }
    })();
    </script>
@endpush
