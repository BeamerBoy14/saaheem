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

        if (in_array($ext, $imageExt, true)) {
            return [
                'type'     => 'image',
                'file'     => $file->getFilename(),
                'folder'   => $folder,
                'category' => $category,
                'mtime'    => $file->getMTime(),
            ];
        }

        if (in_array($ext, $videoExt, true) && strtolower($file->getFilename()) !== 'bk.mp4') {
            return [
                'type'     => 'video',
                'file'     => $file->getFilename(),
                'folder'   => $folder,
                'category' => $category,
                'mtime'    => $file->getMTime(),
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
