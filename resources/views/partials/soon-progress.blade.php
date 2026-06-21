@php
    $progressMeta = $progressMeta ?? __('site.soon.loading');
    $progressAria = $progressAria ?? __('site.soon.progress');
@endphp
<style>
    .soon-label {
        display: block;
        margin: clamp(0.5rem, 2vw, 1rem) 0 0;
        font-size: clamp(1.1rem, 4.5vw, 2.35rem);
        font-weight: 700;
        letter-spacing: 0.28em;
        text-transform: uppercase;
        line-height: 1.2;
    }
    .soon-label::after {
        content: "";
        display: block;
        width: min(12rem, 50vw);
        height: 2px;
        margin: clamp(0.65rem, 2vw, 1rem) auto 0;
        background: linear-gradient(90deg, transparent, var(--magenta), transparent);
        box-shadow: 0 0 14px rgba(228, 0, 124, 0.6);
    }
    .soon-progress-wrap {
        margin: clamp(1.5rem, 4vw, 2.25rem) auto 0;
        width: min(26rem, 88vw);
    }
    .soon-progress-meta {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        gap: 0.75rem;
        margin-bottom: 0.45rem;
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.7);
    }
    .soon-progress-pct {
        color: var(--magenta);
        font-variant-numeric: tabular-nums;
    }
    .soon-progress {
        height: 6px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.18);
        overflow: hidden;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.25);
    }
    .soon-progress__fill {
        display: block;
        height: 100%;
        width: 0;
        border-radius: inherit;
        background: linear-gradient(90deg, #c2006a 0%, var(--magenta) 55%, #ff4da6 100%);
        box-shadow: 0 0 12px rgba(228, 0, 124, 0.65);
        transition: width 0.35s ease;
    }
    @media (prefers-reduced-motion: reduce) {
        .soon-progress__fill { transition: none; }
    }
</style>
<div class="soon-progress-wrap" data-soon-progress data-soon-aria="{{ $progressAria }}">
    <div class="soon-progress-meta">
        <span>{{ $progressMeta }}</span>
        <span class="soon-progress-pct" data-soon-progress-pct>0%</span>
    </div>
    <div
        class="soon-progress"
        role="progressbar"
        aria-valuemin="0"
        aria-valuemax="100"
        aria-valuenow="0"
        aria-label="{{ $progressAria }}"
        data-soon-progress-bar
    >
        <span class="soon-progress__fill" data-soon-progress-fill></span>
    </div>
</div>
@once
    @push('scripts')
        <script>
            (function () {
                var blocks = document.querySelectorAll('[data-soon-progress]');
                if (!blocks.length) return;

                var duration = 14000;
                var target = 90;
                var pauseMs = 700;
                var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

                blocks.forEach(function (block) {
                    var bar = block.querySelector('[data-soon-progress-bar]');
                    var fill = block.querySelector('[data-soon-progress-fill]');
                    var pct = block.querySelector('[data-soon-progress-pct]');
                    if (!bar || !fill || !pct) return;

                    function setProgress(value) {
                        fill.style.width = value + '%';
                        pct.textContent = value + '%';
                        bar.setAttribute('aria-valuenow', String(value));
                    }

                    function runCycle() {
                        if (reducedMotion) {
                            setProgress(target);
                            return;
                        }
                        var start = null;
                        function tick(ts) {
                            if (!start) start = ts;
                            var p = Math.min(1, (ts - start) / duration);
                            var eased = 1 - Math.pow(1 - p, 2.2);
                            setProgress(Math.round(eased * target));
                            if (p < 1) {
                                requestAnimationFrame(tick);
                            } else {
                                setTimeout(function () {
                                    fill.style.transition = 'width 0.25s ease';
                                    setProgress(0);
                                    setTimeout(function () {
                                        fill.style.transition = 'width 0.35s ease';
                                        runCycle();
                                    }, pauseMs);
                                }, pauseMs);
                            }
                        }
                        requestAnimationFrame(tick);
                    }

                    runCycle();
                });
            })();
        </script>
    @endpush
@endonce
