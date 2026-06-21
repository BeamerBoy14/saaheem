<style>
    body.page-dark {
        background: #1a0a14;
        color: rgba(255, 255, 255, 0.9);
    }
    body.page-dark .site-header {
        background: rgba(26, 10, 20, 0.82);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }
    body.page-dark .logo { color: #fff; }
    body.page-dark .nav-main a {
        color: rgba(255, 255, 255, 0.85);
        border-bottom-color: transparent;
    }
    body.page-dark .nav-main a:hover,
    body.page-dark .nav-main a.is-active {
        color: #fff;
        border-bottom-color: var(--magenta);
    }
    body.page-dark .socials a { color: #fff; }
    body.page-dark .socials a:hover { color: var(--magenta); }
    body.page-dark .header-auth a { color: rgba(255, 255, 255, 0.55); }
    body.page-dark .header-auth a:hover { color: var(--magenta); }
    body.page-dark .nav-toggle {
        border-color: rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.05);
    }
    body.page-dark .nav-toggle span { background: #fff; }
    @media (max-width: 960px) {
        body.page-dark .nav-main {
            background: #140810;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }
        body.page-dark .nav-main a {
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }
    }
    .dark-page__surface {
        flex: 1;
        position: relative;
        background:
            radial-gradient(ellipse 50% 40% at 85% 10%, rgba(228, 0, 124, 0.12) 0%, transparent 65%),
            radial-gradient(ellipse 45% 35% at 10% 90%, rgba(228, 0, 124, 0.08) 0%, transparent 60%),
            linear-gradient(180deg, #1a0a14 0%, #120610 50%, #1a0a14 100%);
    }
    .dark-page__surface--center {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: calc(100dvh - 72px);
    }
    .dark-page__intro {
        width: 100%;
        max-width: 46rem;
        margin: 0 auto;
        padding: clamp(2rem, 5vw, 3rem) 1rem;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .dark-page__kicker {
        margin: 0 0 0.6rem;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.88);
    }
    .dark-page__title {
        margin: 0 0 1rem;
        font-size: clamp(3.25rem, 14vw, 7.5rem);
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        line-height: 0.95;
    }
    .dark-page__lead {
        margin: 0 auto;
        max-width: 32rem;
        font-size: clamp(0.9rem, 2vw, 1.05rem);
        color: rgba(255, 255, 255, 0.72);
        line-height: 1.7;
        text-align: center;
    }
</style>
