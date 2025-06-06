@extends('theme-views.layouts.app')

@section('title',translate('flash_deal_products').' | '.$web_config['company_name'].' '.translate('ecommerce'))

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3">
        @php($deal_banner = getStorageImages(path: $deal['banner_full_url'],type: 'banner',source: theme_asset('assets/img/flash-deals.png')))
        <div class="container">
            <div class="flash-deals-wrapper mw-100 style-2 __bg-img" data-img="{{$deal_banner}}">
                <ul class="countdown"
                    data-countdown="{{$web_config['flash_deals']?$web_config['flash_deals']['end_date']:''}}">
                    <li>
                        <h6 class="days">00</h6>
                        <span class="text days_text">{{translate('days')}}</span>
                    </li>
                    <li>
                        <h6 class="hours">00</h6>
                        <span class="text hours_text">{{translate('hours')}}</span>
                    </li>
                    <li>
                        <h6 class="minutes">00</h6>
                        <span class="text minutes_text">{{translate('minutes')}}</span>
                    </li>
                    <li>
                        <h6 class="seconds">00</h6>
                        <span class="text seconds_text">{{translate('seconds')}}</span>
                    </li>
                </ul>
            </div>
            <div class="mt-4 mb-3">
                <ul class="breadcrumb">
                    <li>
                        <a href="{{route('home')}}" class="">{{translate('home')}}</a>
                    </li>
                    <li>
                        <a href="javascript:" class="text-capitalize text-base">{{translate('flash_deals')}}</a>
                    </li>
                </ul>
            </div>
            <hr>
            <div class="row g-3 mt-0">
                @foreach($flashDealProducts as $flashDealProduct)
                    <div class="col-6 col-md-4 col-lg-3 col-xxl-2">
                        @include('theme-views.partials._product-medium-card',['product'=> $flashDealProduct])
                    </div>
                @endforeach
            </div>
        </div>
    </main>

@endsection

