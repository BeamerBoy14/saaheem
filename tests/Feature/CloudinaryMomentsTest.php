<?php

namespace Tests\Feature;

use App\Support\CloudinaryMoments;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CloudinaryMomentsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.cloudinary.cloud_name' => 'demo',
            'services.cloudinary.api_key'    => 'key',
            'services.cloudinary.api_secret' => 'secret',
            'services.cloudinary.folder'     => 'Photo,Video',
            'services.cloudinary.cache_ttl'  => 600,
        ]);
        Cache::flush();
    }

    public function test_disabled_without_credentials(): void
    {
        config(['services.cloudinary.api_secret' => null]);
        Http::fake();

        $this->assertSame([], CloudinaryMoments::media());
        Http::assertNothingSent();
    }

    public function test_maps_images_and_videos_with_category_from_folder(): void
    {
        Http::fake([
            'api.cloudinary.com/*' => Http::response(['resources' => [
                ['public_id' => 'abc 1', 'resource_type' => 'image', 'asset_folder' => 'Photo/Fashion Week'],
                ['public_id' => 'clip_2', 'resource_type' => 'video', 'asset_folder' => 'Video/clip'],
                ['public_id' => 'root', 'resource_type' => 'image', 'asset_folder' => 'Photo'],
                ['public_id' => 'doc', 'resource_type' => 'raw', 'asset_folder' => 'Photo'],
            ]]),
        ]);

        $items = CloudinaryMoments::media();

        $this->assertCount(3, $items);
        $this->assertSame('image', $items[0]['type']);
        $this->assertSame('fashion-week', $items[0]['category']);
        $this->assertSame('https://res.cloudinary.com/demo/image/upload/f_auto,q_auto,c_limit,w_800/abc%201', $items[0]['src']);
        $this->assertSame('video', $items[1]['type']);
        $this->assertSame('clip', $items[1]['category']);
        $this->assertStringEndsWith('/video/upload/q_auto,c_limit,w_720/clip_2.mp4', $items[1]['src']);
        $this->assertStringEndsWith('/video/upload/so_0,q_auto,c_limit,w_720/clip_2.jpg', $items[1]['poster']);
        $this->assertSame('uncategorized', $items[2]['category']);
    }

    public function test_falls_back_to_fixed_folder_search_when_dynamic_finds_nothing(): void
    {
        Http::fakeSequence('api.cloudinary.com/*')
            ->push(['resources' => []])
            ->push(['resources' => [
                ['public_id' => 'Photo/brand/x', 'resource_type' => 'image', 'folder' => 'Photo/brand'],
            ]]);

        $items = CloudinaryMoments::media();

        $this->assertCount(1, $items);
        $this->assertSame('brand', $items[0]['category']);
        $this->assertStringEndsWith('/Photo/brand/x', $items[0]['src']);
    }

    public function test_api_error_returns_nothing_and_is_not_cached(): void
    {
        // 1re visite : les deux recherches échouent ; 2e visite : l'API répond
        Http::fakeSequence('api.cloudinary.com/*')
            ->push('nope', 401)
            ->push('nope', 401)
            ->push(['resources' => [
                ['public_id' => 'ok', 'resource_type' => 'image', 'asset_folder' => 'Photo'],
            ]]);

        $this->assertSame([], CloudinaryMoments::media());
        $this->assertCount(1, CloudinaryMoments::media());
    }

    public function test_results_are_cached(): void
    {
        Http::fake(['api.cloudinary.com/*' => Http::response(['resources' => [
            ['public_id' => 'ok', 'resource_type' => 'image', 'asset_folder' => 'Photo'],
        ]])]);

        CloudinaryMoments::media();
        CloudinaryMoments::media();

        Http::assertSentCount(1);
    }

    public function test_moments_page_renders_cloudinary_media(): void
    {
        Http::fake(['api.cloudinary.com/*' => Http::response(['resources' => [
            ['public_id' => 'pic', 'resource_type' => 'image', 'asset_folder' => 'Photo/shoot'],
        ]])]);

        $this->get('/moments')
            ->assertOk()
            ->assertSee('res.cloudinary.com/demo/image/upload/f_auto,q_auto,c_limit,w_800/pic', false)
            ->assertSee('data-media-cat="shoot"', false);
    }
}
