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

    $stepImages = collect(range(0, 3))->map(function ($index) use ($pool, $fallback) {
        if ($pool->isEmpty()) {
            return $fallback;
        }

        return $pool[$index % $pool->count()];
    })->all();

    if ($pool->count() >= 4) {
        $stepImages = $pool->take(4)->all();
    }

    return view('pages.about', [
        'activeNav' => 'about',
        'stepImages' => $stepImages,
    ]);
})->name('about');

Route::get('/moments', function () {
    $imageExt = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $videoExt = ['mp4', 'webm', 'mov'];
    $media = collect();

    $mapFile = function ($file, string $folder) use ($imageExt, $videoExt) {
        $ext = strtolower($file->getExtension());

        if (in_array($ext, $imageExt, true)) {
            return [
                'type' => 'image',
                'file' => $file->getFilename(),
                'folder' => $folder,
                'mtime' => $file->getMTime(),
            ];
        }

        if (in_array($ext, $videoExt, true) && strtolower($file->getFilename()) !== 'bk.mp4') {
            return [
                'type' => 'video',
                'file' => $file->getFilename(),
                'folder' => $folder,
                'mtime' => $file->getMTime(),
            ];
        }

        return null;
    };

    $photosDir = public_path('photos');
    if (File::isDirectory($photosDir)) {
        $media = $media->merge(
            collect(File::files($photosDir))
                ->map(fn ($file) => $mapFile($file, 'photos'))
                ->filter()
        );
    }

    $videosDir = public_path('videos');
    if (File::isDirectory($videosDir)) {
        $media = $media->merge(
            collect(File::files($videosDir))
                ->map(fn ($file) => $mapFile($file, 'videos'))
                ->filter()
        );
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
            // Force une photo après chaque vidéo
            if ($photosLeft) {
                $result[] = $photos[$pIdx++];
                $lastWasVideo = false;
            } else {
                // Plus de photos, on place les vidéos restantes une par une séparées si possible
                $result[] = $videos[$vIdx++];
                $lastWasVideo = true;
            }
        } else {
            // Alterne aléatoirement photo/vidéo (33 % de chance de placer une vidéo)
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
        'media' => $result,
    ]);
})->name('moments');

Route::view('/actualites', 'pages.actualites', [
    'activeNav' => 'actualites',
])->name('actualites');

Route::view('/merch', 'pages.merch', [
    'activeNav' => 'merch',
])->name('merch');

Route::redirect('/saaheem', '/about');
Route::redirect('/photo', '/moments');
Route::redirect('/clip', '/moments');
Route::redirect('/event', '/');
