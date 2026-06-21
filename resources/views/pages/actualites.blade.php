@extends('layouts.site')

@section('title', __('site.news.title'))
@section('body_class', 'page-actualites page-dark')

@push('head')
    @include('partials.dark-page-head')
@endpush

@section('content')
    <main class="dark-page__surface dark-page__surface--center" role="main" aria-label="{{ __('site.news.aria') }}">
        <header class="dark-page__intro">
            <p class="dark-page__kicker">{{ __('site.news.kicker') }}</p>
            <h1 class="dark-page__title">{{ __('site.news.heading') }}</h1>
            <p class="dark-page__lead">{{ __('site.news.lead') }}</p>
            <span class="soon-label">{{ __('site.news.soon') }}</span>
            @include('partials.soon-progress', [
                'progressMeta' => __('site.news.loading'),
                'progressAria' => __('site.news.progress'),
            ])
        </header>
    </main>
@endsection
