@extends('layouts.site')

@section('title', __('site.moments.title'))
@section('body_class', 'page-moments page-dark')

@push('head')
    @include('partials.dark-page-head')
    <style>
        .moments-page {
            flex: 1;
            position: relative;
            padding: clamp(2rem, 5vw, 3.5rem) 0 clamp(3rem, 8vw, 5rem);
        }
        .moments-page,
        .moments-page__intro {
            background: transparent;
        }
        .moments-page__intro {
            max-width: 42rem;
            margin: 0 auto clamp(2.5rem, 5vw, 3.5rem);
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
        .moments-section {
            width: min(1720px, calc(100% - 1.25rem));
            margin: 0 auto;
        }
        .moments-masonry {
            columns: 5;
            column-gap: 1rem;
        }
        @media (max-width: 1400px) {
            .moments-masonry { columns: 4; }
        }
        @media (max-width: 1100px) {
            .moments-masonry { columns: 3; }
        }
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
            box-shadow:
                0 0 0 1px rgba(228, 0, 124, 0.5),
                0 0 24px rgba(228, 0, 124, 0.25);
        }
        .moments-masonry__media {
            display: block;
            width: 100%;
            height: auto;
            vertical-align: middle;
            border-radius: 10px;
        }
        .moments-masonry__item--video .moments-masonry__media {
            pointer-events: none;
            object-fit: cover;
        }
        .moments-page__empty {
            text-align: center;
            padding: 3rem 1.5rem;
            color: rgba(255, 255, 255, 0.55);
            font-size: 0.95rem;
        }
        .moments-page__empty code {
            color: rgba(255, 255, 255, 0.75);
            background: rgba(255, 255, 255, 0.06);
            padding: 0.1em 0.35em;
            border-radius: 3px;
        }
        .moments-page__empty a {
            color: var(--magenta);
            font-weight: 700;
        }
    </style>
@endpush

@section('content')
    <main class="moments-page dark-page__surface" role="main" aria-label="{{ __('site.moments.aria') }}">
        <header class="moments-page__intro">
            <p class="moments-page__kicker">{{ __('site.moments.kicker') }}</p>
            <h1 class="moments-page__title">{{ __('site.moments.heading') }}</h1>
            <p class="moments-page__lead">{{ __('site.moments.lead') }}</p>
        </header>

        @if (count($media ?? []) > 0)
            <section class="moments-section" aria-label="{{ __('site.moments.gallery') }}">
                <div class="moments-masonry">
                    @foreach ($media as $item)
                        <figure class="moments-masonry__item{{ $item['type'] === 'video' ? ' moments-masonry__item--video' : '' }}">
                            @if ($item['type'] === 'video')
                                <video
                                    class="moments-masonry__media"
                                    src="{{ asset($item['folder'] . '/' . $item['file']) }}"
                                    autoplay
                                    muted
                                    loop
                                    playsinline
                                    preload="metadata"
                                    aria-hidden="true"
                                ></video>
                            @else
                                <img
                                    class="moments-masonry__media"
                                    src="{{ asset($item['folder'] . '/' . $item['file']) }}"
                                    alt=""
                                    loading="lazy"
                                    decoding="async"
                                >
                            @endif
                        </figure>
                    @endforeach
                </div>
            </section>
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
            document.querySelectorAll('.moments-masonry__item--video video').forEach(function (video) {
                var item = video.closest('.moments-masonry__item');

                // Cache l'élément si la vidéo ne peut pas se charger
                video.addEventListener('error', function () {
                    if (item) item.style.display = 'none';
                });

                // Cache l'élément si après 3s la vidéo n'a toujours aucune donnée
                setTimeout(function () {
                    if (video.readyState === 0 && item) item.style.display = 'none';
                }, 3000);

                var play = function () {
                    video.play().catch(function () {});
                };
                play();
                if ('IntersectionObserver' in window) {
                    new IntersectionObserver(function (entries) {
                        entries.forEach(function (entry) {
                            if (entry.isIntersecting) play();
                            else video.pause();
                        });
                    }, { threshold: 0.2 }).observe(video);
                }
            });
        })();
    </script>
@endpush
