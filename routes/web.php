<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/locale/{locale}', function (string $locale) {
    if (! in_array($locale, ['fr', 'en'], true)) {
        abort(404);
    }

    session(['locale' => $locale]);

    return redirect()->back();
})->name('locale.switch');

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/about', function () {
    $imageExt = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $fallback = 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=700&q=80';

    $pool = collect();
    $photosDir = public_path('photos');
    if (File::isDirectory($photosDir)) {
        $pool = collect(File::files($photosDir))
            ->filter(fn ($file) => in_array(strtolower($file->getExtension()), $imageExt, true))
            ->map(fn ($file) => asset('photos/' . $file->getFilename()))
            ->shuffle()
            ->values();
    }

    // Jusqu'à 7 photos pour le collage, fallback si pas assez
    $collagePhotos = collect(range(0, 6))
        ->map(fn ($i) => $pool->get($i, $fallback))
        ->all();

    return view('pages.about', [
        'activeNav'     => 'about',
        'collagePhotos' => $collagePhotos,
    ]);
})->name('about');

Route::get('/moments', function () {
    $imageExt = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $videoExt = ['mp4', 'webm', 'mov'];
    $media = collect();

    $mapFile = function ($file, string $folder, string $category = 'uncategorized') use ($imageExt, $videoExt) {
        $ext = strtolower($file->getExtension());

        $url = asset($folder . '/' . $file->getFilename());

        if (in_array($ext, $imageExt, true)) {
            return [
                'type'     => 'image',
                'category' => $category,
                'src'      => $url,
                'full'     => $url,
                'poster'   => null,
            ];
        }

        if (in_array($ext, $videoExt, true) && strtolower($file->getFilename()) !== 'bk.mp4') {
            return [
                'type'     => 'video',
                'category' => $category,
                'src'      => $url,
                'full'     => $url,
                'poster'   => null,
            ];
        }

        return null;
    };

    // Photos racine (non catégorisées)
    $photosDir = public_path('photos');
    if (File::isDirectory($photosDir)) {
        $media = $media->merge(
            collect(File::files($photosDir))
                ->map(fn ($f) => $mapFile($f, 'photos', 'uncategorized'))
                ->filter()
        );
    }

    // Sous-dossiers photo par catégorie
    $photoSubcats = ['fashion-week', 'concert-festival', 'brand', 'portrait', 'soiree', 'shoot', 'personal-emotions'];
    foreach ($photoSubcats as $cat) {
        $dir = public_path('photos/' . $cat);
        if (File::isDirectory($dir)) {
            $media = $media->merge(
                collect(File::files($dir))
                    ->map(fn ($f) => $mapFile($f, 'photos/' . $cat, $cat))
                    ->filter()
            );
        }
    }

    // Vidéos racine (non catégorisées)
    $videosDir = public_path('videos');
    if (File::isDirectory($videosDir)) {
        $media = $media->merge(
            collect(File::files($videosDir))
                ->map(fn ($f) => $mapFile($f, 'videos', 'uncategorized'))
                ->filter()
        );
    }

    // Sous-dossiers vidéo par catégorie
    $videoSubcats = ['artist', 'clip', 'brand', 'interview', 'aftermovie', 'life'];
    foreach ($videoSubcats as $cat) {
        $dir = public_path('videos/' . $cat);
        if (File::isDirectory($dir)) {
            $media = $media->merge(
                collect(File::files($dir))
                    ->map(fn ($f) => $mapFile($f, 'videos/' . $cat, $cat))
                    ->filter()
            );
        }
    }

    // Médias déposés sur Cloudinary par le propriétaire du site (si configuré)
    $media = $media->merge(\App\Support\CloudinaryMoments::media());

    // Sépare photos et vidéos, mélange chaque groupe
    $videos = $media->filter(fn ($m) => $m['type'] === 'video')->shuffle()->values();
    $photos = $media->filter(fn ($m) => $m['type'] === 'image')->shuffle()->values();

    // Intercale les vidéos parmi les photos pour qu'aucune vidéo ne soit adjacente
    $result = [];
    $vIdx   = 0;
    $pIdx   = 0;
    $total  = $videos->count() + $photos->count();
    $lastWasVideo = false;

    while (count($result) < $total) {
        $videosLeft = $vIdx < $videos->count();
        $photosLeft = $pIdx < $photos->count();

        if ($lastWasVideo || !$videosLeft) {
            if ($photosLeft) {
                $result[] = $photos[$pIdx++];
                $lastWasVideo = false;
            } else {
                $result[] = $videos[$vIdx++];
                $lastWasVideo = true;
            }
        } else {
            if ($videosLeft && $photosLeft && rand(0, 2) === 0) {
                $result[] = $videos[$vIdx++];
                $lastWasVideo = true;
            } elseif ($photosLeft) {
                $result[] = $photos[$pIdx++];
                $lastWasVideo = false;
            } else {
                $result[] = $videos[$vIdx++];
                $lastWasVideo = true;
            }
        }
    }

    return view('pages.moments', [
        'activeNav' => 'moments',
        'media'     => $result,
    ]);
})->name('moments');

Route::view('/actualites', 'pages.actualites', [
    'activeNav' => 'actualites',
])->name('actualites');

Route::view('/merch', 'pages.merch', [
    'activeNav' => 'merch',
])->name('merch');

Route::get('/management', function () {
    $imageExt = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    // Photos de STON! : public/ston (versionné, déployé avec le code)
    $photos = collect();
    $stonDir = public_path('ston');
    if (File::isDirectory($stonDir)) {
        $photos = collect(File::files($stonDir))
            ->filter(fn ($file) => in_array(strtolower($file->getExtension()), $imageExt, true))
            ->sortBy(fn ($file) => $file->getFilename(), SORT_NATURAL)
            ->map(fn ($file) => asset('ston/' . $file->getFilename()))
            ->values();
    }

    // Titres Spotify de STON! (profil « RƎD STONE ») : [titre, id du track, durée]
    $spotifyArtist = '1KD4iQ30dSoT8pSoWqqTT6';
    $tracks = collect([
        ['NEIL ARMSTRONG',             '3jwQ2D9TdgYWfYn55hNfus', '1:06'],
        ['SONO PER STRADA',            '6z3ff1fX3nblOuUovasS7j', '2:00'],
        ['MY WA!',                     '1NJAlxGlCF8gsM9UXu6x5r', '1:57'],
        ['EGO',                        '2YbUdXCC6H6raTS2u8VqNw', '1:21'],
        ['R.I.P GREGORY FREESTYLE',    '22Rz2GCueMIlrDyfZ4rsnE', '2:17'],
        ['PRETTYFASHION*',             '4VFoEpXdrK3VKjRaxnA1zQ', '2:07'],
        ['BONNIE & CL!DE (N.W.R.#5)',  '1zIrq8pxMuXRUFmV34GPOZ', '1:13'],
        ['H!GH-FLY (N.W.R.#2)',        '3mX0qMkQzLxtl6P9kBkEzQ', '2:37'],
        ['2XCUP!NMYLEAN (N.W.R.#1)',   '3nVaFvKUBYcPGrpaA5WeTf', '2:04'],
        ['ECHAPPATOIRE',               '3zFUlOFzyovyrXoadyPNx3', '1:30'],
    ])->map(fn ($t) => ['title' => $t[0], 'id' => $t[1], 'duration' => $t[2]]);

    // Clips YouTube de STON! (chaîne @Bigfourteen.14) : [titre, id de la vidéo, durée]
    $youtubeChannel = 'https://www.youtube.com/@Bigfourteen.14';
    $videos = collect([
        ['NEIL ARMSTRONG',                  'QAVUhR4Wxao', '1:10'],
        ['PRETTYFASHION (Clip)',            '2gAW6-78pM0', '1:40'],
        ['SONO PER STRADA* (Clip officiel)', 'j84q3-n9u8w', '2:01'],
        ['TRAP$LINK (Clip officiel)',       'SAxSS8jA7pM', '1:30'],
        ['V.D.$ / N.W.R. Bonus track',      'pGhjsxjrs90', '1:39'],
        ['N.W.R #5 (Official video)',       'AGw21oLpRPc', '1:10'],
    ])->map(fn ($v) => ['title' => $v[0], 'id' => $v[1], 'duration' => $v[2]]);

    // Linktree de STON! (non intégrable en iframe : x-frame-options SAMEORIGIN) : [plateforme, compte, url, couleur]
    $linktree = [
        'url'    => 'https://linktr.ee/STONY1420',
        'handle' => '@$to1420',
        'links'  => collect([
            ['Instagram',  '@fourteendopamine', 'https://www.instagram.com/fourteendopamine', '#e1306c'],
            ['TikTok',     '@the14creator',     'https://www.tiktok.com/@the14creator',       '#25f4ee'],
            ['Spotify',    'RƎD STONE',         'https://open.spotify.com/artist/' . $spotifyArtist, '#1ed760'],
            ['YouTube',    '@bigfourteen.14',   $youtubeChannel,                               '#ff0033'],
            ['SoundCloud', 'STON!',             'https://on.soundcloud.com/PHososPgwX4ydzDdzp', '#ff5500'],
        ])->map(fn ($l) => ['name' => $l[0], 'handle' => $l[1], 'url' => $l[2], 'color' => $l[3]]),
    ];

    return view('pages.management', [
        'activeNav'      => 'management',
        'photos'         => $photos,
        'spotifyArtist'  => $spotifyArtist,
        'tracks'         => $tracks,
        'youtubeChannel' => $youtubeChannel,
        'videos'         => $videos,
        'linktree'       => $linktree,
    ]);
})->name('management');

Route::get('/contact', function () {
    return view('pages.contact', ['activeNav' => 'contact']);
})->name('contact');

Route::post('/contact', function (\Illuminate\Http\Request $request) {
    $data = $request->validate([
        'name'    => ['required', 'string', 'max:100'],
        'email'   => ['required', 'email', 'max:150'],
        'message' => ['required', 'string', 'max:2000'],
    ]);

    \Illuminate\Support\Facades\Mail::raw(
        "Nom : {$data['name']}\nEmail : {$data['email']}\n\nMessage :\n{$data['message']}",
        function ($mail) use ($data) {
            $mail->to(env('CONTACT_EMAIL', config('mail.from.address')))
                 ->subject('Contact SAAHEEM — ' . $data['name'])
                 ->replyTo($data['email'], $data['name']);
        }
    );

    return back()->with('contact_success', true);
})->name('contact.send');

Route::get('/stay-weird', function () {
    return view('pages.stay-weird');
})->name('stay-weird');

Route::redirect('/saaheem', '/about');
Route::redirect('/photo', '/moments');
Route::redirect('/clip', '/moments');
Route::redirect('/event', '/');
