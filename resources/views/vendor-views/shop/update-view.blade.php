@extends('layouts.back-end.app-seller')

@section('title', translate('shop_Edit'))
@push('css_or_js')
    <link rel="stylesheet"
        href="{{ dynamicAsset(path: 'public/assets/back-end/plugins/intl-tel-input/css/intlTelInput.css') }}">
@endpush
@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/shop-info.png') }}" alt="">
                {{ translate('edit_shop_info') }}
            </h2>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 text-capitalize">{{ translate('edit_shop_info') }}</h5>
                        <a href="{{ route('vendor.shop.index') }}"
                            class="btn btn--primary __inline-70 px-4 text-white">{{ translate('back') }}</a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('vendor.shop.update', [$shop->id]) }}" method="post" class="text-start"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name"
                                            class="title-color text-capitalize">{{ translate('shop_name') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="name" value="{{ $shop->name }}"
                                            class="form-control" id="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="title-color">{{ translate('contact') }}</label>
                                        <div class="mb-3">
                                            <input class="form-control form-control-user phone-input-with-country-picker"
                                                type="tel" id="exampleInputPhone"
                                                value="{{ $shop->contact ?? old('phone') }}"
                                                placeholder="{{ translate('enter_phone_number') }}" required>
                                            <div class="">
                                                <input type="text" class="country-picker-phone-number w-50"
                                                    value="{{ $shop->contact }}" name="contact" hidden readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="address" class="title-color">{{ translate('address') }} <span
                                                class="text-danger">*</span></label>
                                        <textarea type="text" rows="4" name="address" class="form-control" id="address" required>{{ $shop->address }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name"
                                            class="title-color text-capitalize">{{ translate('upload_image') }}</label>
                                        <div class="custom-file text-left">
                                            <input type="file" name="image" id="custom-file-upload"
                                                class="custom-file-input image-input" data-image-id="viewer"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label text-capitalize"
                                                for="custom-file-upload">{{ translate('choose_file') }}</label>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <img class="upload-img-view" id="viewer"
                                            src="{{ getStorageImages(path: $shop->image_full_url, type: 'backend-basic') }}"
                                            alt="{{ translate('image') }}" />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4 mt-2">
                                    <div class="form-group">
                                        <div class="flex-start">
                                            <label for="name"
                                                class="title-color text-capitalize">{{ translate('upload_banner') }}
                                            </label>
                                            <div class="mx-1">
                                                <span
                                                    class="text-info">{{ THEME_RATIO[theme_root_path()]['Store cover Image'] }}</span>
                                            </div>
                                        </div>
                                        <div class="custom-file text-left">
                                            <input type="file" name="banner" id="banner-upload"
                                                class="custom-file-input image-input" data-image-id="viewer-banner"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label text-capitalize"
                                                for="banner-upload">{{ translate('choose_file') }}</label>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <img class="upload-img-view upload-img-view__banner" id="viewer-banner"
                                                src="{{ getStorageImages(path: $shop->banner_full_url, type: 'backend-banner') }}"
                                                alt="{{ translate('banner_image') }}" />
                                        </div>
                                    </div>
                                </div>
                                @if (theme_root_path() == 'theme_aster')
                                    <div class="col-md-6 mb-4 mt-2">
                                        <div class="form-group">
                                            <div class="flex-start">
                                                <label for="name"
                                                    class="title-color text-capitalize">{{ translate('upload_secondary_banner') }}</label>
                                                <div class="mx-1">
                                                    <span class="text-info">{{ translate('ratio') . ' ' . '( 6:1 )' }}</span>
                                                </div>
                                            </div>
                                            <div class="custom-file text-left">
                                                <input type="file" name="bottom_banner" id="bottom-banner-upload"
                                                    class="custom-file-input image-input"
                                                    data-image-id="viewer-bottom-banner"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                <label class="custom-file-label"
                                                    for="bottom-banner-upload">{{ translate('choose_file') }}</label>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <img class="upload-img-view upload-img-view__banner"
                                                    id="viewer-bottom-banner"
                                                    src="{{ getStorageImages(path: $shop->bottom_banner_full_url, type: 'backend-banner') }}"
                                                    alt="{{ translate('banner_image') }}" />
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if (theme_root_path() == 'theme_fashion')
                                    <div class="col-md-6 mb-4 mt-2">
                                        <div class="form-group">
                                            <div class="flex-start">
                                                <label for="name"
                                                    class="title-color text-capitalize">{{ translate('upload_offer_banner') }}</label>
                                                <div class="mx-1">
                                                    <span class="text-info">{{ translate('ratio') . ' ' . '( 7:1 )' }}</span>
                                                </div>
                                            </div>
                                            <div class="custom-file text-left">
                                                <input type="file" name="offer_banner" id="offer-banner-upload"
                                                    class="custom-file-input image-input"
                                                    data-image-id="viewer-offer-banner"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                <label class="custom-file-label text-capitalize"
                                                    for="offer-banner-upload">{{ translate('choose_file') }}</label>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <div class="d-flex">
                                                <img class="upload-img-view upload-img-view__banner"
                                                    id="viewer-offer-banner"
                                                    src="{{ getStorageImages(path: $shop->offer_banner_full_url, type: 'backend-banner') }}"
                                                    alt="{{ translate('banner_image') }}" />
                                            </div>
                                        </div>
                                    </div>
                                @endif


                            </div>
                            <hr>
                            {{-- {{  $shop->shop }} --}}
                            <div class="row">


                             

                                   <!-- Search Location Field -->
                                   <div class="col-lg-6 form-group">
                                    <label for="autocomplete" class="title-color d-flex gap-1 align-items-center">
                                        Search Location
                                    </label>
                                    <div class="input-group input-group-merge">
                                        <input id="autocomplete" class="form-control" placeholder="Search for a place"
                                            type="text">
                                    </div>
                                </div>

                                <div class="col-lg-3 form-group">
                                    <label for="latitude" class="title-color d-flex gap-1 align-items-center">
                                        Latitude
                                    </label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" id="latitude" name="latitude"
                                            class="form-control E9ECEFcolor" placeholder="Ex: -94.22213" readonly
                                            value="{{ $shop->seller->latitude }}">
                                    </div>
                                </div>

                                <div class="col-lg-3 form-group">
                                    <label for="longitude" class="title-color d-flex gap-1 align-items-center">
                                        Longitude
                                    </label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" id="longitude" name="longitude"
                                            class="form-control E9ECEFcolor" placeholder="Ex: 103.344322" readonly
                                            value="{{ $shop->seller->longitude }}">
                                    </div>
                                </div>

                             

                                <!-- Google Map Embed -->
                                <div class="col-lg-12 form-group map-container" id="map" style="height: 200px;">
                                    <iframe
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3629.650719447814!2d46.675295114785446!3d24.713552084119667!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e2f02b3e037d0d3%3A0x37e34a9e59ad82c8!2sRiyadh!5e0!3m2!1sen!2ssa!4v1234567890123"
                                        allowfullscreen="" loading="lazy"></iframe>
                                </div>
                                <div class="col-lg-12 form-group">
                                    <label for="brief_here" class="title-color d-flex gap-1 align-items-center">
                                        Write a brief about your shop
                                    </label>
                                    <div class="input-group input-group-merge">
                                        <textarea id="brief_here" name="brief_here" class="form-control" rows="4" placeholder="Brief here">{{ $shop->seller->brief_here }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label for="city" class="title-color d-flex gap-1 align-items-center">
                                        City
                                    </label>
                                    <div class="input-group input-group-merge">
                                        <select id="city" class="custom-select" name="city">
                                            @php
                                                $cities = [
                                                    'Riyadh',
                                                    'Jeddah',
                                                    'Mecca',
                                                    'Medina',
                                                    'Dammam',
                                                    'Khobar',
                                                    'Tabuk',
                                                    'Taif',
                                                    'Al-Kharj',
                                                    'Abha',
                                                ];
                                            @endphp

                                            @foreach ($cities as $city)
                                                <option value="{{ $city }}"
                                                    {{ $shop->seller->city == $city ? 'selected' : '' }}>
                                                    {{ $city }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6 form-group">
                                    <label for="category" class="title-color d-flex gap-1 align-items-center">
                                        choose category
                                    </label>
                                    <div class="input-group input-group-merge">
                                        <select id="category" class="custom-select" name="category">
                                            <option value="toys">Toys</option>
                                            <option value="book">book</option>
                                        </select>
                                    </div>
                                </div>

                                @if (theme_root_path() == 'theme_aster')
                                    <div class="col-lg-6 form-group">
                                        <div class="d-flex justify-content-center">
                                            <img class="upload-img-view upload-img-view__banner" id="viewerBottomBanner"
                                                src="{{ dynamicAsset(path: 'public/assets/back-end/img/400x400/img2.jpg') }}"
                                                alt="{{ translate('banner_image') }}" />
                                        </div>

                                        <div class="mt-4">
                                            <div class="d-flex gap-1 align-items-center title-color mb-2">
                                                {{ translate('shop_secondary_banner') }}
                                                <span
                                                    class="text-info">{{ THEME_RATIO[theme_root_path()]['Store Banner Image'] }}</span>
                                            </div>

                                            <div class="custom-file">
                                                <input type="file" name="bottom_banner" id="bottom-banner-upload"
                                                    class="custom-file-input image-input"
                                                    data-image-id="viewerBottomBanner"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                <label class="custom-file-label text-capitalize"
                                                    for="bottom-banner-upload">{{ translate('upload_bottom_banner') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>









                    </div>
                    <div class="d-flex justify-content-end gap-2 m-5">
                        <a class="btn btn-danger" href="{{ route('vendor.shop.index') }}">{{ translate('cancel') }}</a>
                        <button type="submit" class="btn btn--primary">{{ translate('update') }}</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/plugins/intl-tel-input/js/intlTelInput.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/country-picker-init.js') }}"></script>


    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB3VTAihhs6gEYNld1LMwNkEiszH3TRcMQ&libraries=places&callback=initMap"
        async></script>
    <script>
        $(document).ready(function() {
            $('#shop_name').on('keyup', function() {
                const shopName = $(this).val();

                // Hide all validation messages
                $('.validation-message').hide();

                // Length and character validation
                const specialCharRegex = /[^a-zA-Z0-9 ]/; // No special characters
                if (shopName.length < 4 || shopName.length > 20) {
                    $('#error-length-message').text("Shop name must be between 4 and 20 characters.")
                        .show();
                    return;
                }

                if (specialCharRegex.test(shopName)) {
                    $('#error-special-message').text("Shop name cannot contain special characters.").show();
                    return;
                }

                // Send AJAX request if basic validation passes
                $.ajax({
                    url: "{{ route('check.shop.name') }}", // Adjust the route name as per your Laravel route
                    type: 'GET', // Use POST if required by your backend
                    data: {
                        shop_name: shopName
                    },
                    success: function(response) {
                        if (response.exists) {
                            // Shop name is already taken
                            $('#success-message').hide();
                            $('#error-message').text("Shop name is already taken.").show();
                        } else {
                            // Shop name is available
                            $('#error-message').hide();
                            $('#success-message').text("Shop name is available.").show();
                        }
                    },
                    error: function() {
                        $('#error-message').text(
                            "An error occurred while checking the shop name.").show();
                    }
                });
            });
        });
    </script>
    <script>
        let autocomplete;
        let map;
        let marker;

        function initMap() {
            // Initialize the map centered at a default location (Riyadh, for example)
            map = new google.maps.Map(document.getElementById("map"), {
                center: {
                    lat: 24.7136,
                    lng: 46.6753
                }, // Default coordinates
                zoom: 13,
            });

            // Create a draggable marker
            marker = new google.maps.Marker({
                map: map,
                draggable: true,
                position: map.getCenter(), // Initially place it at the center of the map
            });

            // Add an event listener to update latitude and longitude when the marker is dragged
            google.maps.event.addListener(marker, 'dragend', function() {
                const latLng = marker.getPosition();
                document.getElementById("latitude").value = latLng.lat();
                document.getElementById("longitude").value = latLng.lng();
            });

            // Initialize the Places Autocomplete feature
            autocomplete = new google.maps.places.Autocomplete(
                document.getElementById("autocomplete"), {
                    types: ["geocode"], // Only return geocoded results
                }
            );

            // Listen for place change and update the map and marker position
            autocomplete.addListener("place_changed", onPlaceChanged);
        }

        function onPlaceChanged() {
            const place = autocomplete.getPlace();
            if (!place.geometry) {
                return;
            }

            // Update the map's center and zoom to the selected place
            map.setCenter(place.geometry.location);
            map.setZoom(15);

            // Update the marker's position to the selected place
            marker.setPosition(place.geometry.location);

            // Set the latitude and longitude values
            const lat = place.geometry.location.lat();
            const lng = place.geometry.location.lng();
            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;

            // Optionally display the place name or address
            const placeName = place.formatted_address || "Location not found";
            document.getElementById("shop_address").value = placeName;
        }
    </script>
@endpush
