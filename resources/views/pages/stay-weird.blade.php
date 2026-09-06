<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stay Weird — SAAHEEM</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { width: 100%; height: 100%; background: #000; display: flex; align-items: center; justify-content: center; }
        video {
            width: 90%;
            max-width: 900px;
            max-height: 90vh;
            border-radius: 12px;
        }
        @media (max-width: 640px) {
            video {
                width: 100%;
                max-height: 85vh;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <a href="{{ route('home') }}" aria-label="Retour accueil" style="
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 10;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
    "></a>
    <video id="sw-vid" autoplay muted loop playsinline style="touch-action: none;">
        <source src="{{ asset('photos/f065d4e1-b563-42dd-aeb8-f8f749609e2a.mp4') }}" type="video/mp4">
    </video>
    <script>
        var vid = document.getElementById('sw-vid');
        var btn = document.querySelector('a[aria-label]');
        var pausing = false;

        function inBtn(touch) {
            var r = btn.getBoundingClientRect();
            return touch.clientX >= r.left && touch.clientX <= r.right &&
                   touch.clientY >= r.top  && touch.clientY <= r.bottom;
        }

        document.addEventListener('touchstart', function(e) {
            if (inBtn(e.touches[0])) return;
            pausing = true;
            vid.pause();
        }, { passive: true });

        document.addEventListener('touchend', function(e) {
            if (!pausing) return;
            pausing = false;
            vid.play();
        }, { passive: true });
    </script>
</body>
</html>
