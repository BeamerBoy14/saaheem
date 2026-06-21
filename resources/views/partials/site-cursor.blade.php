<style>
    @media (hover: hover) and (pointer: fine) {
        html.has-game-cursor,
        html.has-game-cursor body,
        html.has-game-cursor body * {
            cursor: none !important;
        }
        html.has-game-cursor input,
        html.has-game-cursor textarea,
        html.has-game-cursor select,
        html.has-game-cursor [contenteditable="true"] {
            cursor: none !important;
        }
        html.has-game-cursor .site-cursor {
            position: fixed;
            top: 0;
            left: 0;
            width: 44px;
            height: 44px;
            margin: -22px 0 0 -22px;
            pointer-events: none;
            z-index: 99999;
            opacity: 0;
            transition: opacity 0.2s ease;
            will-change: transform;
        }
        html.has-game-cursor .site-cursor.is-visible {
            opacity: 1;
        }
        html.has-game-cursor .site-cursor__ring {
            position: absolute;
            inset: 0;
            border: 2px solid var(--magenta);
            border-radius: 50%;
            box-shadow:
                0 0 12px rgba(228, 0, 124, 0.55),
                inset 0 0 8px rgba(228, 0, 124, 0.2);
            transition: border-color 0.15s ease, box-shadow 0.15s ease, transform 0.12s ease;
        }
        html.has-game-cursor .site-cursor.is-hover .site-cursor__ring {
            border-color: #fff;
            box-shadow:
                0 0 18px rgba(228, 0, 124, 0.85),
                0 0 4px rgba(255, 255, 255, 0.6);
        }
        html.has-game-cursor .site-cursor.is-hover:not(.is-clicking) .site-cursor__ring {
            transform: scale(1.18);
        }
        html.has-game-cursor .site-cursor.is-clicking .site-cursor__ring {
            transform: scale(0.82);
        }
        html.has-game-cursor .site-cursor__dot {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            margin: -2.5px 0 0 -2.5px;
            background: var(--magenta);
            border-radius: 50%;
            box-shadow: 0 0 6px rgba(228, 0, 124, 0.9);
        }
        html.has-game-cursor .site-cursor__cross-h,
        html.has-game-cursor .site-cursor__cross-v {
            position: absolute;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 4px rgba(228, 0, 124, 0.6);
        }
        html.has-game-cursor .site-cursor__cross-h {
            top: 50%;
            left: 6px;
            right: 6px;
            height: 1px;
            margin-top: -0.5px;
        }
        html.has-game-cursor .site-cursor__cross-v {
            left: 50%;
            top: 6px;
            bottom: 6px;
            width: 1px;
            margin-left: -0.5px;
        }
    }
    @media (prefers-reduced-motion: reduce) {
        html.has-game-cursor .site-cursor {
            display: none !important;
        }
    }
</style>
<div class="site-cursor" id="site-cursor" aria-hidden="true">
    <span class="site-cursor__ring"></span>
    <span class="site-cursor__cross-h"></span>
    <span class="site-cursor__cross-v"></span>
    <span class="site-cursor__dot"></span>
</div>
<script>
    (function () {
        if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        document.documentElement.classList.add('has-game-cursor');

        var cursor = document.getElementById('site-cursor');
        if (!cursor) return;

        var x = -100;
        var y = -100;
        var visible = false;
        var hoverSelector = 'a, button, summary, label[for], .about-timeline__btn, .about-cta, .btn-outline, .nav-toggle, .hero-lets-go, .hero-nav a, [role="button"], [role="tab"]';

        function move(clientX, clientY) {
            x = clientX;
            y = clientY;
            cursor.style.transform = 'translate3d(' + x + 'px, ' + y + 'px, 0)';
        }

        document.addEventListener('mousemove', function (e) {
            if (!visible) {
                visible = true;
                cursor.classList.add('is-visible');
            }
            move(e.clientX, e.clientY);
            var target = e.target.closest(hoverSelector);
            cursor.classList.toggle('is-hover', !!target && !target.disabled);
        }, { passive: true });

        document.addEventListener('mouseleave', function () {
            visible = false;
            cursor.classList.remove('is-visible');
        });

        document.addEventListener('mousedown', function () {
            cursor.classList.add('is-clicking');
        });
        document.addEventListener('mouseup', function () {
            cursor.classList.remove('is-clicking');
        });
    })();
</script>
