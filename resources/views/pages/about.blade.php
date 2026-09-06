@extends('layouts.site')

@section('title', __('site.about.title'))

@section('body_class', 'page-about page-dark')

@push('head')
    @include('partials.dark-page-head')
    <style>
        .about-page {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
        }
        .about-hero {
            position: relative;
            flex: 1;
            min-height: calc(100dvh - 72px);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #1a0a14;
        }
        .about-hero__media { position: absolute; inset: 0; }
        .about-hero__media video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .about-hero__overlay {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 70% 55% at 50% 45%, rgba(0,0,0,.45) 0%, transparent 70%),
                linear-gradient(0deg, rgba(0,0,0,.55) 0%, transparent 40%, transparent 60%, rgba(0,0,0,.5) 100%);
        }
        .about-hero__inner {
            position: relative;
            z-index: 2;
            width: min(920px, calc(100% - 2rem));
            margin: 0 auto;
            padding: 1rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .about-hero__title {
            margin: 0;
            font-size: clamp(4.5rem, 18vw, 11rem);
            font-weight: 800;
            letter-spacing: 0.04em;
            line-height: 0.9;
            color: var(--white);
            text-transform: uppercase;
            text-shadow: 0 4px 40px rgba(0, 0, 0, 0.45);
        }
        .about-hero__soon {
            margin: clamp(1rem, 2.5vw, 1.5rem) 0 0;
            max-width: 32rem;
            font-size: clamp(0.85rem, 2vw, 1.05rem);
            font-weight: 500;
            line-height: 1.7;
            letter-spacing: 0.04em;
            color: rgba(255, 255, 255, 0.9);
        }
        .about-back {
            margin-top: clamp(1.5rem, 4vw, 2rem);
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.75rem 1.4rem;
            font-family: inherit;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--white);
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.45);
            cursor: pointer;
            transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
        }
        .about-back:hover {
            background: var(--magenta);
            border-color: var(--magenta);
            box-shadow: 0 0 24px rgba(228, 0, 124, 0.45);
        }
        .about-video-badge {
            position: absolute;
            right: clamp(1rem, 4vw, 2.5rem);
            bottom: clamp(1rem, 3vw, 2rem);
            z-index: 3;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--white);
        }
        .about-video-badge::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--magenta);
        }
        .about-media-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.5);
            background: rgba(255,255,255,.12);
            color: var(--white);
            cursor: pointer;
            display: grid;
            place-items: center;
            padding: 0;
        }
        .about-media-btn > svg { width: 16px; height: 16px; }
        .about-media-btn > .about-media-btn__play { display: none; margin-left: 2px; }
        .about-media-btn.is-paused > .about-media-btn__pause { display: none; }
        .about-media-btn.is-paused > .about-media-btn__play { display: block; }
        @media (max-width: 640px) {
            .about-hero__inner { width: calc(100% - 1.5rem); }
        }
    </style>
@endpush

@section('content')
    <div class="about-page">
        <section class="about-hero" aria-labelledby="about-hero-title">
            <div class="about-hero__media" aria-hidden="true">
                <video id="about-video" autoplay muted loop playsinline>
                    <source src="{{ asset('videos/bk.mp4') }}" type="video/mp4">
                </video>
            </div>
            <div class="about-hero__overlay" aria-hidden="true"></div>
            <div class="about-hero__inner">
                <h1 class="about-hero__title" id="about-hero-title">{{ __('site.about.heading') }}</h1>
                <span class="soon-label">{{ __('site.about.soon') }}</span>
                <p class="about-hero__soon">{{ __('site.about.lead') }}</p>
                @include('partials.soon-progress', [
                    'progressMeta' => __('site.about.loading') . '…',
                    'progressAria' => __('site.about.progress'),
                ])
                <button type="button" class="about-back" id="about-back">{{ __('site.about.back') }}</button>
            </div>
            <div class="about-video-badge">
                <span>{{ __('site.about.video_playing') }}</span>
                <button type="button" class="about-media-btn" id="about-pause" aria-label="{{ __('site.about.pause_video') }}">
                    <svg class="about-media-btn__pause" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <rect x="6" y="5" width="4" height="14" rx="1.2"></rect>
                        <rect x="14" y="5" width="4" height="14" rx="1.2"></rect>
                    </svg>
                    <svg class="about-media-btn__play" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M8 5.5v13L19 12 8 5.5z"></path>
                    </svg>
                </button>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            var v = document.getElementById('about-video');
            var b = document.getElementById('about-pause');
            if (v && b) {
                function sync() {
                    b.classList.toggle('is-paused', v.paused);
                    b.setAttribute('aria-label', v.paused ? 'Reprendre' : 'Mettre en pause');
                }
                b.addEventListener('click', function () {
                    if (v.paused) { v.play(); } else { v.pause(); }
                });
                v.addEventListener('play', sync);
                v.addEventListener('pause', sync);
                sync();
            }

            var backBtn = document.getElementById('about-back');
            if (backBtn) {
                backBtn.addEventListener('click', function () {
                    if (window.history.length > 1) {
                        window.history.back();
                    } else {
                        window.location.href = '{{ route('home') }}';
                    }
                });
            }
        })();
    </script>
@endpush
