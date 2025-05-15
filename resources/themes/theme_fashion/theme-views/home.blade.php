@extends('theme-views.layouts.app')

@section('title', $web_config['company_name'].' '.translate('online_shopping').' | '.$web_config['company_name'].' '.translate('ecommerce'))

@push('css_or_js')
    <meta property="og:image" content="{{$web_config['web_logo']['path']}}"/>
    <meta property="og:title" content="Welcome To {{$web_config['company_name']}} Home"/>
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta name="description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
    <meta property="og:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">

    <meta property="twitter:card" content="{{$web_config['web_logo']['path']}}"/>
    <meta property="twitter:title" content="Welcome To {{$web_config['company_name']}} Home"/>
    <meta property="twitter:url" content="{{ config('app.url') }}">
    <meta property="twitter:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
@endpush

@section('content')

    @include('theme-views.partials._banner-section')

    <div class="container d-none d-xl-block">
        @include('theme-views.layouts.partials._search-form-partials')
    </div>

    @if ($categories->count() > 0)
        @include('theme-views.partials._categories')
    @endif

    @if ($bannerTypePromoBannerMiddleTop)
        <div class="container d-sm-none mt-3">
            <a href="{{ $bannerTypePromoBannerMiddleTop['url'] }}" target="_blank" class="img1 promo-1">
                <img loading="lazy" class="img-fluid" alt="{{ translate('banner') }}" src="{{ getStorageImages(path: $bannerTypePromoBannerMiddleTop['photo_full_url'], type: 'banner') }}">
            </a>
        </div>
    @endif

    @if ($flashDeal['flashDeal'] && $flashDeal['flashDealProducts']  && count($flashDeal['flashDealProducts']) > 0)
        @include('theme-views.partials._flash-deals')
    @endif

    @include('theme-views.partials._clearance-sale')

    @if ($bannerTypePromoBannerLeft)
        <div class="container d-sm-none overflow-hidden pt-4">
            <a href="{{ $bannerTypePromoBannerLeft['url'] }}" target="_blank" class="img3 img-fluid">
                <img loading="lazy" src="{{ getStorageImages(path: $bannerTypePromoBannerLeft['photo_full_url'], type:'banner') }}"
                class="img-fluid" alt="{{ translate('banner') }}">
            </a>
        </div>
    @endif

    @include('theme-views.partials._recommended-product')

    @if ($bannerTypePromoBannerLeft && $bannerTypePromoBannerMiddleTop && $bannerTypePromoBannerMiddleBottom && $bannerTypePromoBannerRight)
        @include('theme-views.partials._promo-banner')
    @endif

    @include('theme-views.partials._deal-of-the-day')

    @if ($bannerTypePromoBannerMiddleBottom)
        <div class="container d-sm-none overflow-hidden pt-4">
            <a href="{{ $bannerTypePromoBannerMiddleBottom['url'] }}" target="_blank" class="img2">
                <img loading="lazy" src="{{ getStorageImages(path: $bannerTypePromoBannerMiddleBottom['photo_full_url'], type:'banner') }}"
                class="img-fluid" alt="{{ translate('banner') }}">
            </a>
        </div>
    @endif

    @include('theme-views.partials.__featured-product')

    @include('theme-views.partials._all-products-home')

    @include('theme-views.partials._signature-product')

    @if ($web_config['business_mode'] == 'multi' && count($topVendorsList) > 0)
        @include('theme-views.partials._top-stores')
    @endif

    @if ($bannerTypePromoBannerRight)
        <div class="container d-sm-none overflow-hidden pt-4">
            <a href="{{ $bannerTypePromoBannerRight['url'] }}" target="_blank" class="d-block promotional-banner">
                <img loading="lazy" src="{{ getStorageImages(path: $bannerTypePromoBannerRight['photo_full_url'], type:'banner') }}"
                class="w-100 img-fluid" alt="{{ translate('banner') }}">
            </a>
        </div>
    @endif

    @include('theme-views.partials._most-demanded-product')

    @if ($web_config['business_mode'] == 'multi' && getCustomerFromQuery() && count($recentOrderShopList)>0)
        @include('theme-views.partials._recent-ordered-shops')
    @endif

    @if ($bannerTypePromoBannerBottom)
        <div class="container">
            <div class="mt-32px">
                <a href="{{ $bannerTypePromoBannerBottom->url }}" target="_blank" class="d-block promotional-banner">
                    <img loading="lazy" class="w-100 rounded aspect-ratio-8-1" alt="{{ translate('banner') }}"
                         src="{{ getStorageImages(path: $bannerTypePromoBannerBottom['photo_full_url'], type:'banner') }}">
                </a>
            </div>
        </div>
    @endif

    @if ($web_config['business_mode'] == 'multi')
        @include('theme-views.partials._other-stores')
    @endif

    @include('theme-views.partials._how-to-section')

@endsection

@if ($bannerTypeMainBanner->count() <= 1)
@push('script')
    <script src="{{ theme_asset('assets/js/home-blade.js') }}"></script>
@endpush
@endif
