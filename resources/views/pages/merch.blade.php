@extends('layouts.site')

@section('title', __('site.merch.title'))
@section('body_class', 'page-merch page-dark')

@push('head')
    @include('partials.dark-page-head')
    <style>
        .merch-page {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
        }
        .merch-hero {
            position: relative;
            flex: 1;
            min-height: calc(100dvh - 72px);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #1a0a14;
        }
        .merch-hero__media { position: absolute; inset: 0; }
        .merch-hero__media video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .merch-hero__overlay {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 70% 55% at 50% 45%, rgba(0,0,0,.45) 0%, transparent 70%),
                linear-gradient(0deg, rgba(0,0,0,.55) 0%, transparent 40%, transparent 60%, rgba(0,0,0,.5) 100%);
        }
        .merch-hero__inner {
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
        .merch-hero__title {
            margin: 0;
            font-size: clamp(4.5rem, 18vw, 11rem);
            font-weight: 800;
            letter-spacing: 0.04em;
            line-height: 0.9;
            color: var(--white);
            text-transform: uppercase;
            text-shadow: 0 4px 40px rgba(0, 0, 0, 0.45);
        }
        .merch-hero__soon {
            margin: clamp(1rem, 2.5vw, 1.5rem) 0 0;
            max-width: 32rem;
            font-size: clamp(0.85rem, 2vw, 1.05rem);
            font-weight: 500;
            line-height: 1.7;
            letter-spacing: 0.04em;
            color: rgba(255, 255, 255, 0.9);
        }
        .merch-back {
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
        .merch-back:hover {
            background: var(--magenta);
            border-color: var(--magenta);
            box-shadow: 0 0 24px rgba(228, 0, 124, 0.45);
        }
        .merch-back:focus-visible {
            outline: 2px solid var(--magenta);
            outline-offset: 3px;
        }
        .merch-video-badge {
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
        .merch-video-badge::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--magenta);
        }
        .merch-media-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.5);
            background: rgba(255,255,255,.12);
            color: var(--white);
            cursor: pointer;
            display: grid;
            grid-template-columns: 1fr;
            grid-template-rows: 1fr;
            padding: 0;
        }
        .merch-media-btn > svg {
            grid-area: 1 / 1;
            width: 16px;
            height: 16px;
            justify-self: center;
            align-self: center;
        }
        .merch-media-btn > .merch-media-btn__play { display: none; margin-left: 2px; }
        .merch-media-btn.is-paused > .merch-media-btn__pause { display: none; }
        .merch-media-btn.is-paused > .merch-media-btn__play { display: block; }
        @media (max-width: 640px) {
            .merch-hero__inner { width: calc(100% - 1.5rem); }
        }
    </style>
@endpush

@section('content')
    <div class="merch-page">
        <section class="merch-hero" aria-labelledby="merch-hero-title">
            <div class="merch-hero__media" aria-hidden="true">
                <video id="merch-video" autoplay muted loop playsinline>
                    <source src="{{ asset('videos/bk.mp4') }}" type="video/mp4">
                </video>
            </div>
            <div class="merch-hero__overlay" aria-hidden="true"></div>
            <div class="merch-hero__inner">
                <h1 class="merch-hero__title" id="merch-hero-title">{{ __('site.merch.heading') }}</h1>
                <span class="soon-label">{{ __('site.merch.soon') }}</span>
                <p class="merch-hero__soon">{{ __('site.merch.lead') }}</p>
                @include('partials.soon-progress', [
                    'progressMeta' => __('site.merch.loading'),
                    'progressAria' => __('site.merch.progress'),
                ])
                <button type="button" class="merch-back" id="merch-back">{{ __('site.merch.back') }}</button>
            </div>
            <div class="merch-video-badge">
                <span>{{ __('site.merch.video_playing') }}</span>
                <button type="button" class="merch-media-btn" id="merch-pause" aria-label="{{ __('site.merch.pause_video') }}">
                    <svg class="merch-media-btn__pause" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <rect x="6" y="5" width="4" height="14" rx="1.2"></rect>
                        <rect x="14" y="5" width="4" height="14" rx="1.2"></rect>
                    </svg>
                    <svg class="merch-media-btn__play" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
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
            var videoLabels = {
                play: @json(__('site.merch.play_video')),
                pause: @json(__('site.merch.pause_video')),
            };
            var v = document.getElementById('merch-video');
            var b = document.getElementById('merch-pause');
            if (v && b) {
                function sync() {
                    b.classList.toggle('is-paused', v.paused);
                    b.setAttribute('aria-label', v.paused ? videoLabels.play : videoLabels.pause);
                }
                b.addEventListener('click', function () {
                    if (v.paused) { v.play(); } else { v.pause(); }
                });
                v.addEventListener('play', sync);
                v.addEventListener('pause', sync);
                sync();
            }

            var backBtn = document.getElementById('merch-back');
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
