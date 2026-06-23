<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=press-start-2p:400|montserrat:600,700,800,900&display=swap" rel="stylesheet">
<style>
    :root {
        --font-retro: "Press Start 2P", monospace;
        --retro-fire: linear-gradient(180deg, #ff2b2b 0%, #ff6f00 46%, #ffe600 100%);
        --retro-chrome: linear-gradient(
            180deg,
            #8ecfff 0%,
            #ffffff 35%,
            #2a1810 38%,
            #2a1810 42%,
            #f0d070 52%,
            #7a4520 100%
        );
    }

    html {
        font-size: 10px;
        -webkit-font-smoothing: none;
        -moz-osx-font-smoothing: unset;
        text-rendering: optimizeSpeed;
    }

    body,
    button,
    input,
    textarea,
    select {
        font-family: var(--font-retro);
        font-size: 10px;
        line-height: 1.9;
    }

    .retro-fire {
        background-image: var(--retro-fire);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }

    .retro-chrome {
        background-image: var(--retro-chrome);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }

    h1, h2, h3, h4,
    .logo,
    .dark-page__title,
    .moments-page__title,
    .merch-hero__title,
    .about-copy__title,
    .about-copy__title-about,
    .about-copy__title-me,
    .soon-label {
        background-image: var(--retro-fire);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
        text-shadow: none;
    }

    .nav-main a,
    .dark-page__kicker,
    .moments-page__kicker,
    .about-cta,
    .merch-back,
    .btn-outline,
    .soon-progress-meta,
    .soon-progress-pct,
    .merch-hero__soon-label,
    .about-card__question,
    .about-tape {
        background-image: var(--retro-chrome);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }

    .about-timeline__steps .about-timeline__btn,
    .about-timeline__steps .about-timeline__btn .about-timeline__dot,
    .about-timeline__steps .about-timeline__btn .about-timeline__label {
        background-image: none;
        -webkit-text-fill-color: currentColor;
    }

    .about-timeline__steps .about-timeline__btn.is-active .about-timeline__dot,
    .about-copy--mobile-nav .about-timeline__btn.is-active .about-timeline__dot {
        background-image: none;
        -webkit-text-fill-color: #0a0a0a;
        color: #0a0a0a;
    }

    .about-copy__title-me {
        position: relative;
        display: inline-block;
    }

    .about-copy__title-me::after {
        z-index: -1;
    }

    body.page-dark,
    body.page-home,
    body.page-about,
    body.page-moments,
    body.page-merch,
    body.page-actualites {
        color: rgba(255, 255, 255, 0.88);
    }

    body.page-dark .nav-main a:hover,
    body.page-dark .nav-main a.is-active {
        filter: brightness(1.15) saturate(1.1);
    }

    .about-cta:hover,
    .merch-back:hover,
    .btn-outline:hover {
        filter: brightness(1.12);
    }

    @media (max-width: 640px) {
        body, button, input, textarea, select {
            font-size: 10px;
            line-height: 1.6;
        }
    }
</style>
