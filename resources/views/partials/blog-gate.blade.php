{{-- ── Blog password gate : s'ouvre sur tout lien [data-blog-gate] ── --}}
<div id="blog-gate" style="
    display:none;
    position:fixed;inset:0;z-index:99998;
    background:rgba(0,0,0,0.92);
    align-items:center;justify-content:center;
    flex-direction:column;gap:1.5rem;
">
    <p style="font-family:'Press Start 2P',monospace;font-size:clamp(0.6rem,2.5vw,0.85rem);color:rgba(255,255,255,0.5);letter-spacing:0.1em;text-transform:uppercase;">{{ __('site.contact.password') }}</p>
    <input id="blog-gate-input" type="password" autocomplete="off" placeholder="••••••••" style="
        font-family:'Press Start 2P',monospace;
        font-size:clamp(0.75rem,3vw,1rem);
        background:transparent;
        border:none;
        border-bottom:2px solid rgba(228,0,124,0.6);
        color:#fff;
        text-align:center;
        outline:none;
        padding:0.5rem 1rem;
        width:min(280px,80vw);
        letter-spacing:0.15em;
    ">
    <p id="blog-gate-error" style="font-family:'Press Start 2P',monospace;font-size:0.55rem;color:#e4007c;opacity:0;transition:opacity 0.2s;">{{ __('site.contact.wrong') }}</p>
</div>

<script>
(function () {
    var gate    = document.getElementById('blog-gate');
    var input   = document.getElementById('blog-gate-input');
    var error   = document.getElementById('blog-gate-error');
    var URL     = 'https://www.tumblr.com/saaheemwrld';

    function openGate(e) {
        e.preventDefault();
        gate.style.display = 'flex';
        input.value = '';
        error.style.opacity = '0';
        setTimeout(function () { input.focus(); }, 50);
    }

    function closeGate() {
        gate.style.display = 'none';
    }

    function check() {
        if (input.value.trim().toLowerCase() === 'stayweird') {
            closeGate();
            window.open(URL, '_blank', 'noopener,noreferrer');
        } else {
            error.style.opacity = '1';
            input.value = '';
            input.focus();
        }
    }

    document.addEventListener('click', function (e) {
        var link = e.target.closest('[data-blog-gate]');
        if (link) openGate(e);
    });
    gate.addEventListener('click', function (e) { if (e.target === gate) closeGate(); });
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') check();
        if (e.key === 'Escape') closeGate();
    });
})();
</script>
