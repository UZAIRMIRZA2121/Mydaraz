@php($announcement = getWebConfig(name: 'announcement'))
@php($businessMode = getWebConfig(name: 'business_mode'))
@if (isset($announcement) && $announcement['status'] == 1)
    <div class="text-center position-relative px-4 py-1 d--none" id="announcement"
        style="background-color: {{ $announcement['color'] }};color:{{ $announcement['text_color'] }}">
        <span>{{ $announcement['announcement'] }} </span>
        <span class="__close-announcement web-announcement-slideUp">X</span>
    </div>
@endif
<!-- Custom CSS -->
<style>
    /* Custom Dropdown Styling */
    .category-dropdown {
        position: relative;
    }
    .custom-dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background: white;
        border: 1px solid #ddd;
        border-radius: 5px;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        padding: 0;
        margin: 0;
        list-style: none;
    }
    .custom-dropdown-menu li {
        padding: 8px 15px;
        cursor: pointer;
    }
    .custom-dropdown-menu li:hover {
        background: #f1f1f1;
    }
</style>

<header class="rtl __inline-10">
    <div class="topbar" style="background-color: #fbf5f5; position: relative;">
        <button class="close-btn text-danger" style="position: absolute; left: 10px; top: 0px; background: none; border: none; font-size: 16px; cursor: pointer;">&times;</button>
        <p class="text-center d-block m-auto" style="color: #fc4d05"><b>Get 50% discount for specific products.</b></p>
    </div>
    <script>
        document.querySelector('.close-btn').addEventListener('click', function () {
            document.querySelector('.topbar').classList.add('d-none');
        });
    </script>
    
<!-- JavaScript to Handle Dropdown -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const categoryButton = document.getElementById("categoryDropdown");
        const dropdownMenu = document.getElementById("categoryDropdownMenu");
        const categoryItems = document.querySelectorAll(".category-select");
        const selectedCategoryInput = document.getElementById("selectedCategory");

        categoryItems.forEach(item => {
            item.addEventListener("click", function (event) {
                event.preventDefault();
                categoryButton.textContent = this.textContent; // Change button text
                selectedCategoryInput.value = this.getAttribute("data-value"); // Set hidden input value
            });
        });

        // Show/Hide dropdown on button click
        categoryButton.addEventListener("click", function () {
            dropdownMenu.classList.toggle("show");
        });

        // Close dropdown when clicking outside
        document.addEventListener("click", function (event) {
            if (!categoryButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.remove("show");
            }
        });
    });
</script>
    @php($categories = \App\Utils\CategoryManager::getCategoriesWithCountingAndPriorityWiseSorting(dataLimit: 11))

    <div class="navbar-sticky bg-light mobile-head">
        <div class="navbar navbar-expand-md navbar-light">
            <div class="container ">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand d-none d-sm-block mr-3 flex-shrink-0 __min-w-7rem" href="{{ route('home') }}">
                    <img class="__inline-11" src="{{ getStorageImages(path: $web_config['web_logo'], type: 'logo') }}"
                        alt="{{ $web_config['company_name'] }}">
                </a>
                <a class="navbar-brand d-sm-none" href="{{ route('home') }}">
                    <img class="mobile-logo-img __inline-12"
                        src="{{ getStorageImages(path: $web_config['mob_logo'], type: 'logo') }}"
                        alt="{{ $web_config['company_name'] }}" />
                </a>
               <!-- jQuery CDN -->
<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Search Bar with Category Dropdown -->
<div class="input-group-overlay mx-lg-4 search-form-mobile text-align-direction">
    <form action="{{ route('products') }}" method="GET" class="d-flex border rounded overflow-hidden">
        
        <!-- Custom Category Dropdown -->
        <div class="category-dropdown position-relative">
            <button type="button" class="btn btn-light border-0 px-3 d-flex align-items-center" id="categoryButton">
                <span id="selectedCategory">All Categories</span>
                <i class="czi-arrow-down ms-2"></i>
            </button>

         

            <input type="hidden" name="category_id" id="categoryInput" value="">
        </div>

        <!-- Search Input -->
        <input class="form-control border-0" type="search" autocomplete="off" placeholder="Search for items..." name="name" value="{{ request('name') }}">
        
        <!-- Search Button -->
        <button class="btn btn-primary px-4" type="submit">
            <i class="czi-search text-white"></i>
        </button>
    </form>
       <!-- Dropdown Menu Moved Inside -->
       <ul class="custom-dropdown-menu" id="categoryMenu">
        <li><a class="dropdown-item category-option" data-value="" href="#">All Categories</a></li>
        @foreach ($categories as $category)
            <li><a class="dropdown-item category-option" data-value="{{ $category['id'] }}" href="#">{{ $category['name'] }}</a></li>
        @endforeach
    </ul>
</div>


                <div class="navbar-toolbar d-flex flex-shrink-0 align-items-center">
                    <a class="navbar-tool navbar-stuck-toggler" href="#">
                        <span class="navbar-tool-tooltip">{{ translate('expand_Menu') }}</span>
                        <div class="navbar-tool-icon-box">
                            <i class="navbar-tool-icon czi-menu open-icon"></i>
                            <i class="navbar-tool-icon czi-close close-icon"></i>
                        </div>
                    </a>
                    <div
                        class="navbar-tool open-search-form-mobile d-lg-none {{ Session::get('direction') === 'rtl' ? 'mr-md-3' : 'ml-md-3' }}">
                        <a class="navbar-tool-icon-box bg-secondary" href="javascript:">
                            <i class="tio-search"></i>
                        </a>
                    </div>

                    <div>
                        @php($currency_model = getWebConfig(name: 'currency_model'))
                        @if ($currency_model == 'multi_currency')
                            <div class="topbar-text dropdown disable-autohide mr-4">
                                <a class="topbar-link dropdown-toggle" href="#" data-toggle="dropdown">
                                    <span>{{ session('currency_code') }} {{ session('currency_symbol') }}</span>
                                </a>
                                <ul
                                    class="text-align-direction dropdown-menu dropdown-menu-{{ Session::get('direction') === 'rtl' ? 'right' : 'left' }} min-width-160px">
                                    @foreach (\App\Models\Currency::where('status', 1)->get() as $key => $currency)
                                        <li class="dropdown-item cursor-pointer get-currency-change-function"
                                            data-code="{{ $currency['code'] }}">
                                            {{ $currency->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
        
                        <div class="topbar-text dropdown disable-autohide  __language-bar text-capitalize">
                            <a class="topbar-link dropdown-toggle" href="#" data-toggle="dropdown">
                                @foreach ($web_config['language'] as $data)
                                    @if ($data['code'] == getDefaultLanguage())
                                    <span class="mr-2 text-capitalize">{{ strtoupper(substr($data['name'], 0, 2)) }}</span>
                                        <img class="" width="20"
                                            src="{{ theme_asset(path: 'public/assets/front-end/img/flags/' . $data['code'] . '.png') }}"
                                            alt="{{ $data['name'] }}">
                                       
                                    @endif
                                @endforeach
                            </a>
                            <ul
                                class="text-align-direction dropdown-menu dropdown-menu-{{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}">
                                @foreach ($web_config['language'] as $key => $data)
                                    @if ($data['status'] == 1)
                                        <li class="change-language" data-action="{{ route('change-language') }}"
                                            data-language-code="{{ $data['code'] }}">
                                           
                                            <a class="dropdown-item pb-1" href="javascript:">
                                                <img class="mr-2 " width="20"
                                                    src="{{ theme_asset(path: 'public/assets/front-end/img/flags/' . $data['code'] . '.png') }}"
                                                    alt="{{ $data['name'] }}" />
                                                    <span class="text-capitalize">{{ $data['name'] }}</span>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div
                        class="navbar-tool dropdown d-none d-md-block {{ Session::get('direction') === 'rtl' ? 'mr-md-3' : 'ml-md-3' }}">
                        <a class="navbar-tool-icon-box bg-secondary dropdown-toggle" href="{{ route('wishlists') }}">
                            <span class="navbar-tool-label">
                                <span class="countWishlist">
                                    {{ session()->has('wish_list') ? count(session('wish_list')) : 0 }}
                                </span>
                            </span>
                            <i class="navbar-tool-icon czi-heart"></i>
                        </a>
                    </div>

                    <div id="cart_items">
                        @include('layouts.front-end.partials._cart')
                    </div>
                    @if (auth('customer')->check())
                        <div class="dropdown">
                            <a class="navbar-tool ml-3" type="button" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <div class="navbar-tool-icon-box bg-secondary">
                                    <div class="navbar-tool-icon-box bg-secondary">
                                        <img class="img-profile rounded-circle __inline-14" alt=""
                                            src="{{ getStorageImages(path: auth('customer')->user()->image_full_url, type: 'avatar') }}">
                                    </div>
                                </div>
                                <div class="navbar-tool-text">
                                    <small>
                                        {{ translate('hello') }},
                                        {{ Str::limit(auth('customer')->user()->f_name, 10) }}
                                    </small>
                                    {{ translate('dashboard') }}
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}"
                                aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('account-oder') }}">
                                    {{ translate('my_Order') }} </a>
                                <a class="dropdown-item" href="{{ route('user-account') }}">
                                    {{ translate('my_Profile') }}</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item"
                                    href="{{ route('customer.auth.logout') }}">{{ translate('logout') }}</a>
                            </div>
                        </div>
                    @else
                        <div class="dropdown">
                            <a class="navbar-tool {{ Session::get('direction') === 'rtl' ? 'mr-md-3' : 'ml-md-3' }}"
                                type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="navbar-tool-icon-box bg-secondary">
                                    <div class="navbar-tool-icon-box bg-secondary">
                                        <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M4.25 4.41675C4.25 6.48425 5.9325 8.16675 8 8.16675C10.0675 8.16675 11.75 6.48425 11.75 4.41675C11.75 2.34925 10.0675 0.666748 8 0.666748C5.9325 0.666748 4.25 2.34925 4.25 4.41675ZM14.6667 16.5001H15.5V15.6667C15.5 12.4509 12.8825 9.83341 9.66667 9.83341H6.33333C3.11667 9.83341 0.5 12.4509 0.5 15.6667V16.5001H14.6667Z"
                                                fill="#1B7FED" />
                                        </svg>

                                    </div>
                                </div>
                            </a>
                            <div class="text-align-direction dropdown-menu __auth-dropdown dropdown-menu-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}"
                                aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('customer.auth.login') }}">
                                    <i class="fa fa-sign-in mr-2"></i> {{ translate('sign_in') }}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('customer.auth.sign-up') }}">
                                    <i class="fa fa-user-circle mr-2"></i>{{ translate('sign_up') }}
                                </a>
                            </div>
                        </div>

                        <div class=" d-none d-lg-block">
                            @if ($businessMode == 'multi')
                                @if (getWebConfig(name: 'seller_registration'))
                                    <div class="dropdown show mx-2 ">
                                        <a class="btn  bg-light dropdown-toggle" href="#" role="button"
                                            id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Vendor
                                        </a>

                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <a class="dropdown-item"
                                                href="{{ route('vendor.auth.registration.index') }}">
                                                {{ translate('become_a_vendor') }}</a>
                                            <a class="dropdown-item" href="{{ route('vendor.auth.login') }}">
                                                {{ translate('vendor_login') }}</a>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="navbar navbar-expand-md navbar-stuck-menu">
            <div class="container px-10px">
                <div class="collapse navbar-collapse text-align-direction" id="navbarCollapse">
                    <div class="w-100 d-md-none text-align-direction">
                        <button class="navbar-toggler p-0" type="button" data-toggle="collapse"
                            data-target="#navbarCollapse">
                            <i class="tio-clear __text-26px"></i>
                        </button>
                    </div>
                    <ul class="navbar-nav d-block d-md-none">
                        <li class="nav-item dropdown {{ request()->is('/') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('home') }}">{{ translate('home') }}</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown d-none d-md-block {{ request()->is('/') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('home') }}">{{ translate('home') }}</a>
                        </li>
                        <li class="nav-item dropdown d-none d-md-block {{ request()->is('/') || request()->routeIs('home') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('home') }}#new-arrival-section">{{ translate('New Arrivals') }}</a>
                        </li>
                        
                        @if ($web_config['featured_deals'] && count($web_config['featured_deals']) > 0)
                        <li class="nav-item dropdown d-none d-md-block {{ request()->is('/') ? 'active' : '' }}">
                            <a class="nav-link" href="#featured_deal">{{ translate('Featured Deal') }}</a>
                        </li>
                        @endif
                        {{-- @if (getWebConfig(name: 'product_brand'))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#"
                                    data-toggle="dropdown">{{ translate('brand') }}</a>
                                <ul
                                    class="text-align-direction dropdown-menu __dropdown-menu-sizing dropdown-menu-{{ Session::get('direction') === 'rtl' ? 'right' : 'left' }} scroll-bar">
                                    @php($brandIndex = 0)
                                    @foreach (\App\Utils\BrandManager::getActiveBrandWithCountingAndPriorityWiseSorting() as $brand)
                                        @php($brandIndex++)
                                        @if ($brandIndex < 10)
                                            <li class="__inline-17">
                                                <div>
                                                    <a class="dropdown-item"
                                                        href="{{ route('products', ['brand_id' => $brand['id'], 'data_from' => 'brand', 'page' => 1]) }}">
                                                        {{ $brand['name'] }}
                                                    </a>
                                                </div>
                                                <div class="align-baseline">
                                                    @if ($brand['brand_products_count'] > 0)
                                                        <span class="count-value px-2">(
                                                            {{ $brand['brand_products_count'] }} )</span>
                                                    @endif
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                    <li class="__inline-17">
                                        <div>
                                            <a class="dropdown-item web-text-primary" href="{{ route('brands') }}">
                                                {{ translate('view_more') }}
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        @endif --}}

                        @if ($web_config['discount_product'] > 0)
                            <li class="nav-item dropdown {{ request()->is('/') ? 'active' : '' }}">
                                <a class="nav-link text-capitalize"
                                    href="{{ route('products', ['data_from' => 'discounted', 'page' => 1]) }}">
                                    {{ translate('discounted_products') }}
                                </a>
                            </li>
                        @endif

                        @if ($web_config['digital_product_setting'] && count($web_config['publishing_houses']) == 1)
                            <li class="nav-item dropdown d-none d-md-block {{ request()->is('/') ? 'active' : '' }}">
                                <a class="nav-link"
                                    href="{{ route('products', ['publishing_house_id' => 0, 'product_type' => 'digital', 'page' => 1]) }}">
                                    {{ translate('Publication_House') }}
                                </a>
                            </li>
                        @elseif ($web_config['digital_product_setting'] && count($web_config['publishing_houses']) > 1)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                                    {{ translate('Publication_House') }}
                                </a>
                                <ul
                                    class="text-align-direction dropdown-menu __dropdown-menu-sizing dropdown-menu-{{ Session::get('direction') === 'rtl' ? 'right' : 'left' }} scroll-bar">
                                    @php($publishingHousesIndex = 0)
                                    @foreach ($web_config['publishing_houses'] as $publishingHouseItem)
                                        @if ($publishingHousesIndex < 10 && $publishingHouseItem['name'] != 'Unknown')
                                            @php($publishingHousesIndex++)
                                            <li class="__inline-17">
                                                <div>
                                                    <a class="dropdown-item"
                                                        href="{{ route('products', ['publishing_house_id' => $publishingHouseItem['id'], 'product_type' => 'digital', 'page' => 1]) }}">
                                                        {{ $publishingHouseItem['name'] }}
                                                    </a>
                                                </div>
                                                <div class="align-baseline">
                                                    @if ($publishingHouseItem['publishing_house_products_count'] > 0)
                                                        <span class="count-value px-2">(
                                                            {{ $publishingHouseItem['publishing_house_products_count'] }}
                                                            )</span>
                                                    @endif
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                    <li class="__inline-17">
                                        <div>
                                            <a class="dropdown-item web-text-primary"
                                                href="{{ route('products', ['product_type' => 'digital', 'page' => 1]) }}">
                                                {{ translate('view_more') }}
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @php($businessMode = getWebConfig(name: 'business_mode'))
                        @if ($businessMode == 'multi')
                            <li class="nav-item dropdown {{ request()->is('/') ? 'active' : '' }}">
                                <a class="nav-link text-capitalize"
                                    href="{{ route('vendors') }}">{{ translate('all_vendors') }}</a>
                            </li>
                        @endif

                        @if (auth('customer')->check())
                            <li class="nav-item d-md-none">
                                <a href="{{ route('user-account') }}" class="nav-link text-capitalize">
                                    {{ translate('user_profile') }}
                                </a>
                            </li>
                            <li class="nav-item d-md-none">
                                <a href="{{ route('wishlists') }}" class="nav-link">
                                    {{ translate('Wishlist') }}
                                </a>
                            </li>
                        @else
                            <li class="nav-item d-md-none">
                                <a class="dropdown-item pl-2" href="{{ route('customer.auth.login') }}">
                                    <i class="fa fa-sign-in mr-2"></i> {{ translate('sign_in') }}
                                </a>
                                <div class="dropdown-divider"></div>
                            </li>
                            <li class="nav-item d-md-none">
                                <a class="dropdown-item pl-2" href="{{ route('customer.auth.sign-up') }}">
                                    <i class="fa fa-user-circle mr-2"></i>{{ translate('sign_up') }}
                                </a>
                            </li>
                        @endif





                        {{-- @if ($businessMode == 'multi')
                            @if (getWebConfig(name: 'seller_registration'))
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#"
                                        data-toggle="dropdown">{{ translate('Vendor') }}</a>
                                    <ul
                                        class="text-align-direction dropdown-menu __dropdown-menu-sizing dropdown-menu-{{ Session::get('direction') === 'rtl' ? 'right' : 'left' }} scroll-bar">


                                        <li class="__inline-17">
                                            <div>
                                                <a class="dropdown-item"
                                                    href="{{ route('vendor.auth.registration.index') }}">
                                                    {{ translate('become_a_vendor') }}
                                                </a>
                                                <a class="dropdown-item" href="{{ route('vendor.auth.login') }}">
                                                    {{ translate('vendor_login') }}
                                                </a>
                                            </div>

                                        </li>
                                    </ul>
                                </li>
                            @endif
                        @endif --}}


                    </ul>

                    @if (auth('customer')->check())
                        <div class="logout-btn mt-auto d-md-none">
                            <hr>
                            <a href="{{ route('customer.auth.logout') }}" class="nav-link">
                                <strong class="text-base">{{ translate('logout') }}</strong>
                            </a>
                        </div>
                    @endif

                </div>
                <!-- Latest Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

                <div>
                    <ul class="navbar-nav d-flex justify-content-end w-100">
                        {{-- <li class="nav-item dropdown d-none d-md-block {{ request()->is('/') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('home') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none" class="svg replaced-svg">
                                    <path d="M7.53828 10.1086C5.95991 10.1086 4.67891 8.82759 4.67891 7.24922C4.67891 5.67084 5.95991 4.38984 7.53828 4.38984C9.11666 4.38984 10.3977 5.67084 10.3977 7.24922C10.3977 8.82759 9.11666 10.1086 7.53828 10.1086ZM7.53828 5.53359C6.59469 5.53359 5.82266 6.30563 5.82266 7.24922C5.82266 8.19281 6.59469 8.96484 7.53828 8.96484C8.48188 8.96484 9.25391 8.19281 9.25391 7.24922C9.25391 6.30563 8.48188 5.53359 7.53828 5.53359ZM8.11016 2.67422V0.958594C8.11016 0.644063 7.85281 0.386719 7.53828 0.386719C7.22375 0.386719 6.96641 0.644063 6.96641 0.958594V2.67422C6.96641 2.98875 7.22375 3.24609 7.53828 3.24609C7.85281 3.24609 8.11016 2.98875 8.11016 2.67422ZM8.11016 13.5398V11.8242C8.11016 11.5097 7.85281 11.2523 7.53828 11.2523C7.22375 11.2523 6.96641 11.5097 6.96641 11.8242V13.5398C6.96641 13.8544 7.22375 14.1117 7.53828 14.1117C7.85281 14.1117 8.11016 13.8544 8.11016 13.5398ZM3.53516 7.24922C3.53516 6.93469 3.27781 6.67734 2.96328 6.67734H1.24766C0.933125 6.67734 0.675781 6.93469 0.675781 7.24922C0.675781 7.56375 0.933125 7.82109 1.24766 7.82109H2.96328C3.27781 7.82109 3.53516 7.56375 3.53516 7.24922ZM14.4008 7.24922C14.4008 6.93469 14.1434 6.67734 13.8289 6.67734H12.1133C11.7988 6.67734 11.5414 6.93469 11.5414 7.24922C11.5414 7.56375 11.7988 7.82109 12.1133 7.82109H13.8289C14.1434 7.82109 14.4008 7.56375 14.4008 7.24922ZM4.51306 4.224C4.73609 4.00097 4.73609 3.64069 4.51306 3.41766L3.36931 2.27391C3.14628 2.05088 2.786 2.05088 2.56297 2.27391C2.33994 2.49694 2.33994 2.85722 2.56297 3.08025L3.70672 4.224C3.82109 4.33838 3.96406 4.38984 4.11275 4.38984C4.26144 4.38984 4.40441 4.33266 4.51878 4.224H4.51306ZM12.5193 12.2303C12.7423 12.0072 12.7423 11.6469 12.5193 11.4239L11.3756 10.2802C11.1525 10.0571 10.7923 10.0571 10.5692 10.2802C10.3462 10.5032 10.3462 10.8635 10.5692 11.0865L11.713 12.2303C11.8273 12.3446 11.9703 12.3961 12.119 12.3961C12.2677 12.3961 12.4107 12.3389 12.525 12.2303H12.5193ZM3.36931 12.2303L4.51306 11.0865C4.73609 10.8635 4.73609 10.5032 4.51306 10.2802C4.29003 10.0571 3.92975 10.0571 3.70672 10.2802L2.56297 11.4239C2.33994 11.6469 2.33994 12.0072 2.56297 12.2303C2.67734 12.3446 2.82031 12.3961 2.969 12.3961C3.11769 12.3961 3.26066 12.3389 3.37503 12.2303H3.36931ZM11.3756 4.224L12.5193 3.08025C12.7423 2.85722 12.7423 2.49694 12.5193 2.27391C12.2963 2.05088 11.936 2.05088 11.713 2.27391L10.5692 3.41766C10.3462 3.64069 10.3462 4.00097 10.5692 4.224C10.6836 4.33838 10.8266 4.38984 10.9753 4.38984C11.1239 4.38984 11.2669 4.33266 11.3813 4.224H11.3756Z" fill="currentColor"></path>
                                    </svg> Light
                            </a>
                        </li> --}}


                        @if(auth()->guard('seller')->check())
                        {{-- 
                        <div class="wa-widget-send-button">
                            <a style="background-color: #fff;" class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle media align-items-center gap-3 navbar-dropdown-account-wrapper dropdown-toggle-left-arrow dropdown-toggle-empty" href="{{ route('vendor.messages.index', ['type' => 'admin']) }}">
                                <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_5926_1152)">
                                        <path d="M16.6666 2.16699H3.33329C2.41663 2.16699 1.67496 2.91699 1.67496 3.83366L1.66663 18.8337L4.99996 15.5003H16.6666C17.5833 15.5003 18.3333 14.7503 18.3333 13.8337V3.83366C18.3333 2.91699 17.5833 2.16699 16.6666 2.16699ZM4.99996 8.00033H15V9.66699H4.99996V8.00033ZM11.6666 12.167H4.99996V10.5003H11.6666V12.167ZM15 7.16699H4.99996V5.50033H15V7.16699Z" fill="#073B74"></path>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_5926_1152">
                                            <rect width="20" height="20" fill="white" transform="translate(0 0.5)"></rect>
                                        </clipPath>
                                    </defs>
                                </svg>
                            </a>
                        </div>
                         --}}
                        <li class="nav-item dropdown d-none d-md-block {{ request()->is('/') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('vendor.messages.index', ['type' => 'admin']) }}">
                                <i class="fas fa-phone-alt me-1"></i> Support
                            </a>
                        </li>
                    @endif
                    @if (auth('customer')->check())
                    <li class="nav-item dropdown d-none d-md-block {{ request()->is('/') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('chat', ['type' => 'admin']) }}">
                            <i class="fas fa-phone-alt me-1"></i> Support
                        </a>
                    </li>
                @endif
                
                     
                    </ul>
                    
                </div>


            </div>
        </div>

        <div class="megamenu-wrap">
            <div class="container">
                <div class="category-menu-wrap">
                    <ul class="category-menu">
                        @foreach ($categories as $key => $category)
                            <li>
                                <a
                                    href="{{ route('products', ['category_id' => $category['id'], 'data_from' => 'category', 'page' => 1]) }}">{{ $category->name }}</a>
                                @if ($category->childes->count() > 0)
                                    <div class="mega_menu z-2">
                                        @foreach ($category->childes as $sub_category)
                                            <div class="mega_menu_inner">
                                                <h6>
                                                    <a
                                                        href="{{ route('products', ['category_id' => $sub_category['id'], 'data_from' => 'category', 'page' => 1]) }}">{{ $sub_category->name }}</a>
                                                </h6>
                                                @if ($sub_category->childes->count() > 0)
                                                    @foreach ($sub_category->childes as $sub_sub_category)
                                                        <div>
                                                            <a
                                                                href="{{ route('products', ['category_id' => $sub_sub_category['id'], 'data_from' => 'category', 'page' => 1]) }}">{{ $sub_sub_category->name }}</a>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </li>
                        @endforeach
                        <li class="text-center">
                            <a href="{{ route('categories') }}"
                                class="text-primary font-weight-bold justify-content-center">
                                {{ translate('View_All') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>

@push('script')
    <script>
        "use strict";

        $(".category-menu").find(".mega_menu").parents("li")
            .addClass("has-sub-item").find("> a")
            .append("<i class='czi-arrow-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}'></i>");
    </script>
    
<!-- jQuery Script -->
<script>
    $(document).ready(function () {
        // Toggle dropdown on button click
        $("#categoryButton").click(function (event) {
            event.stopPropagation(); // Prevents click from closing immediately
            $("#categoryMenu").toggle(); // Show/hide dropdown
        });

        // Close dropdown when clicking outside
        $(document).click(function (event) {
            if (!$(event.target).closest(".category-dropdown").length) {
                $("#categoryMenu").hide();
            }
        });

        // Update button text and hidden input when selecting a category
        $(".category-option").click(function (event) {
            event.preventDefault();
            var selectedText = $(this).text();
            var selectedValue = $(this).data("value");

            $("#selectedCategory").text(selectedText); // Update button text
            $("#categoryInput").val(selectedValue); // Update hidden input
            $("#categoryMenu").hide(); // Close dropdown
        });
    });
</script>

@endpush
