<div class="close-sidebar d-lg-none">
    <i class="bi bi-x-lg"></i>
</div>

<div class="filter-header d-none d-lg-flex justify-content-between align-items-center">
    <h5 class="subtitle text-base"><i class="bi bi-funnel"></i><span>{{ translate('filter') }}</span></h5>
    <button type="button" class="btn btn-soft-base fashion_products_list_form_reset">
        <i class="bi bi-arrow-repeat"></i> {{ translate('reset') }}
    </button>
</div>

@if(!Request::is('/'))
    <div class="d-lg-none mb-4 text-capitalize">
        <h5 class="mb-2">{{ translate('filter_by') }}</h5>
        <div class="position-relative select2-prev-icon d-lg-none">
            <i class="bi bi-sort-up"></i>
            <select class="select2-init-js form-control size-40px filter_select_input filter_by_product_list_mobile"
                    data-primary_select="{{translate('sort_by')}} : {{translate('default')}}">
                <option value="default">{{translate('sort_by')}} : {{translate('default')}}</option>
                <option value="latest" {{ request('data_from') == 'latest' ? 'selected':'' }}>{{translate('sort_by')}}
                    : {{translate('latest')}}</option>
                <option value="a-z">{{translate('sort_by')}} : {{translate('a_to_z_order')}}</option>
                <option value="z-a">{{translate('sort_by')}} : {{translate('z_to_a_Order')}}</option>
                <option value="low-high">{{translate('sort_by')}} : {{translate('low_to_high_price')}}
                </option>
                <option value="high-low">{{translate('sort_by')}} : {{translate('high_to_low_price')}}
                </option>
            </select>
        </div>
    </div>
@endif

<div class="widget">
    <div class="widget-header open">
        <h5 class="title text-capitalize">{{ translate('price_range') }}</h5>
        <div class="chevron-icon">
            <i class="bi bi-chevron-down"></i>
        </div>
    </div>
    <div class="widget-body">
        <div class="widget-body-inner">
            <div class="price-range-slider px-3">
                <div id="input-slider"></div>
                <div class="d-flex justify-content-between mt-3">
                    <div>{{session('currency_symbol')}}{{ ('0.00') }}</div>
                    <div>{{session('currency_symbol')}}{{ ('10000,00,00,000') }}</div>
                </div>
            </div>
        </div>
    </div>
    <input type="number" class="form-control" name="min_price" id="price-range-start" hidden>
    <input type="number" class="form-control" name="max_price" id="price-range-end" hidden>
</div>

@if(isset($publishingHouses) && $web_config['digital_product_setting'])
    <div class="widget">
        <div class="widget-header open">
            <h5 class="title text-capitalize">{{ translate('Product_Type') }}</h5>
            <div class="chevron-icon">
                <i class="bi bi-chevron-down"></i>
            </div>
        </div>
        <div class="widget-body">
            <div class="widget-body-inner">
                <div class="border rounded p-2">
                    <div class="d-flex gap-2">
                        <div class="flex-middle gap-2">
                            <i class="bi bi-sort-up-alt"></i>
                            <span class="d-none d-sm-inline-block">{{ translate('Product_Type') }} </span>
                        </div>
                        <div class="dropdown product_type_sorting">
                            <button type="button" class="border-0 bg-transparent dropdown-toggle text-dark p-0 custom-pe-3 filter-on-product-type-button" data-bs-toggle="dropdown" aria-expanded="false">
                                @if (request('product_type') == 'digital')
                                    {{ translate('digital') }}
                                @elseif (request('product_type') == 'physical')
                                    {{ translate('physical') }}
                                @else
                                    {{ translate('All') }}
                                @endif
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end py-0 filter-on-product-type" id="sort-by-list" style="">
                                <li class="selected filter-on-product-type-change cursor-pointer" data-value="all">
                                    {{ translate('All') }}
                                    <input type="radio" name="product_type" id="product_type_all" value="all" {{ request('product_type') == 'all' ? 'checked' : '' }} hidden>
                                </li>
                                <li class="filter-on-product-type-change cursor-pointer" data-value="physical">
                                    {{ translate('physical') }}
                                    <input type="radio" name="product_type" id="product_type_physical" value="physical" {{ request('product_type') == 'physical' ? 'checked' : '' }} hidden>
                                </li>
                                <li class="filter-on-product-type-change cursor-pointer" data-value="digital">
                                    {{ translate('digital') }}
                                    <input type="radio" name="product_type" id="product_type_digital" value="digital" {{ request('product_type') == 'digital' ? 'checked' : '' }} hidden>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@isset($categories)
    <div class="widget">
        <div class="widget-header open">
            <h5 class="title text-capitalize">{{ translate('all_categories') }}</h5>
            <div class="chevron-icon">
                <i class="bi bi-chevron-down"></i>
            </div>
        </div>
        <div class="widget-body">
            <div class="widget-body-inner">
                <div class="all-categories">

                    @foreach($categories as $category)
                        <div class="form--check">
                            <label class="form--check-inner category_class_for_tag_{{ $category['id'] }}">
                                <input type="checkbox" name="category_ids[]"
                                       value="{{$category['id']}}" {{ request('category_id') == $category['id'] ? 'checked' : '' }}>
                                <span class="check-icon"><i class="bi bi-check"></i></span>
                                <span class="form-check-label">{{$category['name']}}</span>
                                <span class="badge badge-soft-base ms-auto">{{ isset($category->product_count)?$category->product_count:'0' }}</span>
                            </label>
                            @if ($category->childes->count() > 0)
                                <div class="form-check-subgroup">
                                    @foreach($category->childes as $child)
                                        <div class="form--check">
                                            <label class="form--check-inner category_class_for_tag_{{ $child['id'] }}">
                                                <input type="checkbox" name="sub_category_ids[]" value="{{$child['id']}}">
                                                <span class="check-icon"><i class="bi bi-check"></i></span>
                                                <span class="form-check-label">{{$child['name']}}</span>
                                                <span class="badge badge-soft-base ms-auto">{{ isset($child->sub_category_product_count)?$child->sub_category_product_count:'0' }}</span>
                                            </label>
                                            @if ($child->childes->count() > 0)
                                                <div class="form-check-subgroup">
                                                    @foreach($child->childes as $ch)
                                                        <div class="form--check">
                                                            <label class="form--check-inner category_class_for_tag_{{ $ch['id'] }}">
                                                                <input type="checkbox" name="sub_sub_category_ids[]"
                                                                       value="{{$ch['id']}}">
                                                                <span class="check-icon"><i
                                                                            class="bi bi-check"></i></span>
                                                                <span class="form-check-label">{{$ch['name']}}</span>
                                                                <span class="badge badge-soft-base ms-auto">{{ isset($ch->sub_sub_category_product_count)?$ch->sub_sub_category_product_count:'0' }}</span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endisset

@if($web_config['brand_setting'])
    @isset($activeBrands)
        <div class="widget product-type-physical-section">
            <div class="widget-header open">
                <h5 class="title">{{ translate('brands') }}</h5>
                <div class="chevron-icon">
                    <i class="bi bi-chevron-down"></i>
                </div>
            </div>
            <div class="widget-body">
                <div class="widget-body-inner">
                    <div class="all-brands">
                        @foreach($activeBrands as $brand)
                            <div class="form--check">
                                <label class="form--check-inner brand_class_for_tag_{{ $brand['id'] }}">
                                    <input type="checkbox" name="brand_ids[]" class="product-type-physical-checkbox"
                                           value="{{ $brand['id'] }}" {{ request('brand_id') == $brand['id'] ? 'checked' : '' }}>
                                    <span class="check-icon"><i class="bi bi-check"></i></span>
                                    <span class="form-check-label">{{ $brand['name'] }}</span>
                                    <span class="badge badge-soft-base ms-auto">{{ (isset($brand->count)?$brand->count:$brand->brand_products_count) ?? 0 }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endisset
@endif

@if(isset($publishingHouses) && $web_config['digital_product_setting'] && count($publishingHouses) > 0)
    <div class="widget product-type-digital-section">
        <div class="widget-header open">
            <h5 class="title">{{ translate('Publishing_House') }}</h5>
            <div class="chevron-icon">
                <i class="bi bi-chevron-down"></i>
            </div>
        </div>
        <div class="widget-body">
            <div class="widget-body-inner">
                <div class="all-brands">
                    @foreach($publishingHouses as $publishingHouseItem)
                        <div class="form--check">
                            <label class="form--check-inner publishing_house_class_for_tag_{{ $publishingHouseItem['id'] }}">
                                <input type="checkbox" name="publishing_house_ids[]" class="product-type-digital-checkbox"
                                       value="{{ $publishingHouseItem['id'] }}"
                                    {{ request()->has('publishing_house_id') && request('publishing_house_id') == $publishingHouseItem['id'] ? 'checked' : '' }}
                                >
                                <span class="check-icon"><i class="bi bi-check"></i></span>
                                <span class="form-check-label">{{ $publishingHouseItem['name'] }}</span>
                                <span class="badge badge-soft-base ms-auto">
                                    {{ $publishingHouseItem['publishing_house_products_count'] }}
                                </span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

@if($web_config['digital_product_setting'] && isset($digitalProductAuthors) && count($digitalProductAuthors) > 0)
    <div class="widget product-type-digital-section">
        <div class="widget-header open">
            <h5 class="title">
                {{ translate('authors') }}/{{ translate('Creator') }}/{{ translate('Artist') }}
            </h5>
            <div class="chevron-icon">
                <i class="bi bi-chevron-down"></i>
            </div>
        </div>
        <div class="widget-body">
            <div class="widget-body-inner">
                <div class="all-brands">
                    @foreach($digitalProductAuthors as $productAuthor)
                        <div class="form--check">
                            <label class="form--check-inner authors_id_class_for_tag_{{ $productAuthor['id'] }}">
                                <input type="checkbox" name="author_ids[]" class="product-type-digital-checkbox"
                                       value="{{ $productAuthor['id'] }}" {{ request()->has('author_id') && request('author_id') == $productAuthor['id'] ? 'checked' : '' }}>
                                <span class="check-icon"><i class="bi bi-check"></i></span>
                                <span class="form-check-label">{{ $productAuthor['name'] }}</span>
                                <span class="badge badge-soft-base ms-auto">
                                        {{ $productAuthor['digital_product_author_count'] }}
                                    </span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

<div class="widget">
    <div class="widget-header open">
        <h5 class="title text-capitalize">{{ translate('by_review_rating') }}</h5>
        <div class="chevron-icon">
            <i class="bi bi-chevron-down"></i>
        </div>
    </div>
    <div class="widget-body">
        <div class="widget-body-inner">
            <div class="review-rating-group">
                <label>
                    <input type="checkbox" name="rating[]" value="1">
                    <span class="review_class_for_tag_1">
                        <i class="bi bi-star-fill text-star"></i>
                        <span>{{ '1' }}</span>
                    </span>
                </label>
                <label>
                    <input type="checkbox" name="rating[]" value="2">
                    <span class="review_class_for_tag_2">
                        <i class="bi bi-star-fill text-star"></i>
                        <span>{{ '2' }}</span>
                    </span>
                </label>
                <label>
                    <input type="checkbox" name="rating[]" value="3">
                    <span class="review_class_for_tag_3">
                        <i class="bi bi-star-fill text-star"></i>
                        <span>{{ '3' }}</span>
                    </span>
                </label>
                <label>
                    <input type="checkbox" name="rating[]" value="4">
                    <span class="review_class_for_tag_4">
                        <i class="bi bi-star-fill text-star"></i>
                        <span>{{ '4' }}</span>
                    </span>
                </label>
                <label>
                    <input type="checkbox" name="rating[]" value="5">
                    <span class="review_class_for_tag_5">
                        <i class="bi bi-star-fill text-star"></i>
                        <span>{{ '5' }}</span>
                    </span>
                </label>
            </div>
        </div>
    </div>
</div>

@isset($colors)
    <div class="widget product-type-physical-section">
        <div class="widget-header open">
            <h5 class="title">{{ translate('color') }}</h5>
            <div class="chevron-icon">
                <i class="bi bi-chevron-down"></i>
            </div>
        </div>
        <div class="widget-body">
            <div class="widget-body-inner">
                <div class="check-color-group">
                    @foreach ($colors as $color)
                        <label>
                            <input type="checkbox" name="color_ids[]" value="{{ $color }}">
                            <span style="--base:{{ $color }}">
                        <i class="bi bi-check"></i>
                    </span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endisset

<span class="products-search-data-backup"
      data-page="{{ request('page') ?? 1 }}"
      data-brand="{{ request('brand_id') ?? '' }}"
      data-category="{{ request('category_id') ?? '' }}"
      data-name="{{ request('search') ?? request('name') }}"
      data-from="{{ request('data_from') }}"
      data-sort="{{ request('sort_by') }}"
      data-min-price="{{ request('min_price') }}"
      data-max-price="{{ request('max_price') }}"
      data-publishing-house-id="{{ request('publishing_house_id') }}"
      data-author-id="{{ request('author_id') }}"
      data-product-type="{{ request('product_type') ?? 'all' }}"
      data-message="{{ translate('items_found') }}"
></span>
