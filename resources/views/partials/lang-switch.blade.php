@php
    $current = app()->getLocale();
    $variant = $variant ?? 'default';
@endphp
<nav class="lang-switch lang-switch--{{ $variant }}" aria-label="{{ __('site.lang.label') }}">
    <a
        href="{{ route('locale.switch', ['locale' => 'fr']) }}"
        class="lang-switch__btn{{ $current === 'fr' ? ' is-active' : '' }}"
        hreflang="fr"
        lang="fr"
        @if ($current === 'fr') aria-current="true" @endif
        title="{{ __('site.lang.fr') }}"
    >FR</a>
    <span class="lang-switch__sep" aria-hidden="true">/</span>
    <a
        href="{{ route('locale.switch', ['locale' => 'en']) }}"
        class="lang-switch__btn{{ $current === 'en' ? ' is-active' : '' }}"
        hreflang="en"
        lang="en"
        @if ($current === 'en') aria-current="true" @endif
        title="{{ __('site.lang.en') }}"
    >EN</a>
</nav>
