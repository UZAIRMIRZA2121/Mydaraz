@if ($web_config['featured_deals']->count() > 0)
    <section class="signature-product-section pb-0">
        <div class="overflow-hidden">
            <div class="section-title-2">
                <span class="shapetitle text-capitalize">{{ translate('Feature_Deal') }}</span>
                <h2 class="title text-capitalize">{{ translate('Feature_Deal_for_this_season') }}</h2>
            </div>
        </div>
        <div class="signature-product-section-inner">
            <div class="container">
                <div class="signature-wrapper">
                    <div class="signature-products-slider-wrapper">
                        <div class="owl-theme owl-carousel signature-products-slider">
                            @foreach ($web_config['featured_deals'] as $key => $product)
                                <div class="signature-product @if($key % 2 == 1) even-item @endif">
                                    @include('theme-views.partials._signature-product-card', ['product'=>$product])
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="signature-title text-md-end">
                        <div class="pb-4">
                            <h2 class="title text-base text-capitalize mb-2">{{ translate('find_your_best_featured_deal_product') }}</h2>
                            <a href="{{route('products',['data_from'=>'featured_deal','page'=>1])}}"
                               class="text-base text-underline">
                                {{translate('see_all_products')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
