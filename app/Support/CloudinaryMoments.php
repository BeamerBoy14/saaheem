<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Médias de la page Moments hébergés sur Cloudinary.
 *
 * Le propriétaire du site dépose ses fichiers dans le Media Library Cloudinary :
 *   Photo/…, Photo/<catégorie>/…, Video/…, Video/<catégorie>/…  (dossiers racine configurables)
 * Le type (image / vidéo) vient de Cloudinary ; la catégorie est le sous-dossier
 * (« Fashion Week » → fashion-week). Un fichier posé à la racine n'a pas de catégorie.
 * Les résultats sont mis en cache pour ne pas interroger l'API à chaque visite.
 */
class CloudinaryMoments
{
    public static function enabled(): bool
    {
        $c = config('services.cloudinary');

        return ! empty($c['cloud_name']) && ! empty($c['api_key']) && ! empty($c['api_secret']);
    }

    /**
     * @return array<int, array{type: string, category: string, src: string, full: string, poster: ?string}>
     */
    public static function media(): array
    {
        if (! self::enabled()) {
            return [];
        }

        // En cas d'échec de l'API, null n'est pas gardé en cache : nouvel essai à la visite suivante
        $resources = Cache::remember('cloudinary.moments', config('services.cloudinary.cache_ttl'), function () {
            return self::fetch();
        });

        return collect($resources ?? [])
            ->map(fn (array $r) => self::toItem($r))
            ->filter()
            ->values()
            ->all();
    }

    /** @return string[] dossiers racine, ex. ["Photo", "Video"] */
    private static function roots(): array
    {
        return array_values(array_filter(array_map(
            fn ($f) => trim($f, " /"),
            explode(',', (string) config('services.cloudinary.folder'))
        )));
    }

    /**
     * Liste brute des fichiers des dossiers racine (et sous-dossiers), via la Search API.
     */
    private static function fetch(): ?array
    {
        $c = config('services.cloudinary');

        // Comptes récents : "dynamic folders" (asset_folder) ; anciens comptes : "fixed folders" (folder)
        $expressions = array_map(function ($field) {
            return implode(' OR ', array_map(
                fn ($root) => $field . '="' . $root . '" OR ' . $field . ':' . $root . '/*',
                self::roots()
            ));
        }, ['asset_folder', 'folder']);

        foreach ($expressions as $expression) {
            $resources = [];
            $cursor = null;

            do {
                $response = Http::withBasicAuth($c['api_key'], $c['api_secret'])
                    ->timeout(10)
                    ->post("https://api.cloudinary.com/v1_1/{$c['cloud_name']}/resources/search", array_filter([
                        'expression'  => $expression,
                        'max_results' => 500,
                        'sort_by'     => [['created_at' => 'desc']],
                        'next_cursor' => $cursor,
                    ]));

                if (! $response->successful()) {
                    Log::warning('Cloudinary search failed', ['status' => $response->status(), 'body' => $response->body()]);
                    break;
                }

                $resources = array_merge($resources, $response->json('resources', []));
                $cursor = $response->json('next_cursor');
            } while ($cursor);

            if ($resources) {
                return $resources;
            }
        }

        return null;
    }

    private static function toItem(array $r): ?array
    {
        $type = $r['resource_type'] ?? null;
        if (! in_array($type, ['image', 'video'], true)) {
            return null;
        }

        $folder = trim($r['asset_folder'] ?? $r['folder'] ?? '', '/');
        $category = $folder === '' || in_array($folder, self::roots(), true)
            ? 'uncategorized'
            : Str::slug(basename($folder));

        $base = 'https://res.cloudinary.com/' . config('services.cloudinary.cloud_name') . '/' . $type . '/upload/';
        $id = implode('/', array_map('rawurlencode', explode('/', $r['public_id'])));

        if ($type === 'image') {
            return [
                'type'     => 'image',
                'category' => $category,
                'src'      => $base . 'f_auto,q_auto,c_limit,w_800/' . $id,
                'full'     => $base . 'f_auto,q_auto,c_limit,w_2000/' . $id,
                'poster'   => null,
            ];
        }

        // Vidéos : MP4 H.264 redimensionné, lu partout (iPhone compris)
        return [
            'type'     => 'video',
            'category' => $category,
            'src'      => $base . 'q_auto,c_limit,w_720/' . $id . '.mp4',
            'full'     => $base . 'q_auto,c_limit,w_1280/' . $id . '.mp4',
            'poster'   => $base . 'so_0,q_auto,c_limit,w_720/' . $id . '.jpg',
        ];
    }
}
