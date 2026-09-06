@extends('layouts.site')

@section('title', __('site.contact.title'))
@section('body_class', 'page-contact page-dark')

@push('head')
    @include('partials.dark-page-head')
    <style>
        .contact-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: grayscale(100%) brightness(0.45) blur(6px);
            transform: scale(1.04);
            pointer-events: none;
        }
        .contact-bg-veil {
            position: fixed;
            inset: 0;
            z-index: 1;
            background: rgba(0, 0, 0, 0.45);
            pointer-events: none;
        }
        body.page-contact { overflow: hidden; }
        .contact-page {
            position: relative;
            z-index: 2;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: clamp(2rem, 6vw, 4rem) 1.25rem;
        }
        .contact-card {
            width: 100%;
            max-width: 480px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: clamp(2rem, 6vw, 3rem) clamp(1.5rem, 5vw, 2.5rem);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .contact-card__title {
            font-family: var(--font-retro, 'Press Start 2P', monospace);
            font-size: clamp(0.75rem, 3vw, 1rem);
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin: 0 0 0.4rem;
        }
        .contact-card__sub {
            font-size: 2rem;
            color: rgba(255, 255, 255, 0.4);
            margin: 0 0 2rem;
            line-height: 1.5;
        }
        .contact-field {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            margin-bottom: 1.4rem;
        }
        .contact-field label {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.45);
        }
        .contact-field input,
        .contact-field textarea {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 8px;
            color: #fff;
            font-size: 0.92rem;
            padding: 0.75rem 1rem;
            outline: none;
            transition: border-color 0.2s;
            font-family: inherit;
            width: 100%;
            box-sizing: border-box;
            resize: none;
        }
        .contact-field input::placeholder,
        .contact-field textarea::placeholder { color: rgba(255,255,255,0.2); }
        .contact-field input:focus,
        .contact-field textarea:focus { border-color: var(--magenta); }
        .contact-submit {
            width: 100%;
            padding: 0.85rem 1rem;
            background: var(--magenta);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: var(--font-retro, 'Press Start 2P', monospace);
            font-size: clamp(0.55rem, 2vw, 0.72rem);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s;
            margin-top: 0.5rem;
        }
        .contact-submit:hover { opacity: 0.85; transform: translateY(-1px); }
        .contact-submit:active { transform: translateY(0); }
        .contact-field input.is-invalid,
        .contact-field textarea.is-invalid { border-color: var(--magenta); }
        .contact-error {
            font-size: 0.7rem;
            color: var(--magenta);
            letter-spacing: 0.05em;
        }
        .contact-success {
            text-align: center;
            padding: 2rem 0 1rem;
            font-family: var(--font-retro, 'Press Start 2P', monospace);
            font-size: clamp(0.6rem, 2vw, 0.8rem);
            color: #fff;
            letter-spacing: 0.12em;
        }
    </style>
@endpush

@section('content')
    <video class="contact-bg" id="contact-bg-vid" autoplay muted loop playsinline preload="auto" aria-hidden="true">
        <source src="{{ asset('videos/bk.mp4') }}" type="video/mp4">
    </video>
    <div class="contact-bg-veil"></div>
    <main class="contact-page" role="main">
        <div class="contact-card">
            <h1 class="contact-card__title">{{ __('site.contact.heading') }}</h1>
            <p class="contact-card__sub">🦋</p>

            @if (session('contact_success'))
                <div class="contact-success">
                    Message envoyé ✓
                </div>
            @else
            <form method="POST" action="{{ route('contact.send') }}">
                @csrf
                <div class="contact-field">
                    <label for="contact-name">Nom</label>
                    <input id="contact-name" type="text" name="name" placeholder="Ton nom"
                        value="{{ old('name') }}"
                        class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
                    @error('name')<span class="contact-error">{{ $message }}</span>@enderror
                </div>
                <div class="contact-field">
                    <label for="contact-email">Email</label>
                    <input id="contact-email" type="email" name="email" placeholder="ton@email.com"
                        value="{{ old('email') }}"
                        class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                    @error('email')<span class="contact-error">{{ $message }}</span>@enderror
                </div>
                <div class="contact-field">
                    <label for="contact-msg">Message</label>
                    <textarea id="contact-msg" name="message" rows="5" placeholder="Ton message..."
                        class="{{ $errors->has('message') ? 'is-invalid' : '' }}">{{ old('message') }}</textarea>
                    @error('message')<span class="contact-error">{{ $message }}</span>@enderror
                </div>
                <button type="submit" class="contact-submit">Envoyer</button>
            </form>
            @endif
        </div>
    </main>
    <script>
        var v = document.getElementById('contact-bg-vid');
        v.addEventListener('canplay', function () { v.playbackRate = 0.8; });
    </script>
@endsection
