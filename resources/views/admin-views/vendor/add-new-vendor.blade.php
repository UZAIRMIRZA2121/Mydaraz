@extends('layouts.back-end.app')

@section('title', translate('add_new_Vendor'))
@push('css_or_js')
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/plugins/intl-tel-input/css/intlTelInput.css') }}">
@endpush
@section('content')

<style>
      .map-container {
      border: 1px solid #A5DBC3;
      border-radius: 2px;
      overflow: hidden;
      padding: 10px;
    }
    .map-container iframe {
      width: 100%;
      height: 200px;
      border: none;
    }
    #multiStepForm input[type='radio'] {
        width: 22px;
        height: 22px; 
        border: 2px solid #E37070;
        border-radius: 50%; 
        appearance: none; 
        outline: none; 
        background-color: white; 
        transition: background-color 0.3s;
    }

    #multiStepForm input[type='radio']:checked {
        background-color: #E37070; 
        border: 2px solid #E37070; 
    }
    #multiStepForm input[type='radio']:focus {
        border: 2px solid #E37070;
    }
</style>
<div class="content container-fluid main-card {{Session::get('direction')}}">
    <div class="mb-4">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{dynamicAsset(path: 'public/assets/back-end/img/add-new-seller.png')}}" class="mb-1" alt="">
            {{ translate('add_new_Vendor') }}
        </h2>
    </div>
    <form class="user" action="{{route('admin.vendors.add')}}" method="post" enctype="multipart/form-data" id="add-vendor-form">
        @csrf
        <div class="card">
            <div class="card-body">
                <input type="hidden" name="status" value="approved">
                <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2 border-bottom pb-3 mb-4 pl-4">
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/vendor-information.png')}}" class="mb-1" alt="">
                    {{ translate('vendor_information') }}
                </h5>
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="form-group">
                            <label for="exampleFirstName" class="title-color d-flex gap-1 align-items-center">{{translate('first_name')}}</label>
                            <input type="text" class="form-control form-control-user" id="exampleFirstName" name="f_name" value="{{old('f_name')}}" placeholder="{{translate('ex')}}: Jhone" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleLastName" class="title-color d-flex gap-1 align-items-center">{{translate('last_name')}}</label>
                            <input type="text" class="form-control form-control-user" id="exampleLastName" name="l_name" value="{{old('l_name')}}" placeholder="{{translate('ex')}}: Doe" required>
                        </div>
                        <div class="form-group">
                            <label class="title-color d-flex" for="exampleFormControlInput1">{{translate('phone')}}</label>
                            <div class="mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">+966</span>
                                    </div>
                                    <input 
                                        class="form-control form-control-user" 
                                        type="tel" 
                                        id="exampleInputPhone" 
                                        value="{{ old('phone') }}" 
                                        placeholder="{{ translate('enter_phone_number') }}" 
                                        required 
                                        name="phone"
                                        pattern="[0-9]{9}" 
                                        maxlength="9"
                                    >
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <div class="d-flex justify-content-center">
                                <img class="upload-img-view" id="viewer"
                                    src="{{dynamicAsset(path: 'public/assets/back-end/img/400x400/img2.jpg')}}" alt="{{translate('banner_image')}}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="title-color mb-2 d-flex gap-1 align-items-center">{{translate('vendor_Image')}} <span class="text-info">({{translate('ratio')}} {{translate('1')}}:{{translate('1')}})</span></div>
                            <div class="custom-file text-left">
                                <input type="file" name="image" id="custom-file-upload" class="custom-file-input image-input"
                                       data-image-id="viewer"
                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="custom-file-upload">{{translate('upload_image')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <input type="hidden" name="status" value="approved">
                <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2 border-bottom pb-3 mb-4 pl-4">
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/vendor-information.png')}}" class="mb-1" alt="">
                    {{translate('account_information')}}
                </h5>
                <div class="row">
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail" class="title-color d-flex gap-1 align-items-center">{{translate('email')}}</label>
                        <input type="email" class="form-control form-control-user" id="exampleInputEmail" name="email" value="{{old('email')}}" placeholder="{{translate('ex').':'.'Jhone@company.com'}}" required>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="user_password" class="title-color d-flex gap-1 align-items-center">
                            {{translate('password')}}
                            <span class="input-label-secondary cursor-pointer d-flex" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{translate('The_password_must_be_at_least_8_characters_long_and_contain_at_least_one_uppercase_letter').','.translate('_one_lowercase_letter').','.translate('_one_digit_').','.translate('_one_special_character').','.translate('_and_no_spaces').'.'}}">
                                <img alt="" width="16" src={{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }}>
                            </span>
                        </label>
                        <div class="input-group input-group-merge">
                            <input type="password" class="js-toggle-password form-control password-check"
                                   name="password" required id="user_password" minlength="8"
                                   placeholder="{{ translate('password_minimum_8_characters') }}"
                                   data-hs-toggle-password-options='{
                                                         "target": "#changePassTarget",
                                                        "defaultClass": "tio-hidden-outlined",
                                                        "showClass": "tio-visible-outlined",
                                                        "classChangeTarget": "#changePassIcon"
                                                }'>
                            <div id="changePassTarget" class="input-group-append">
                                <a class="input-group-text" href="javascript:">
                                    <i id="changePassIcon" class="tio-visible-outlined"></i>
                                </a>
                            </div>
                        </div>
                        <span class="text-danger mx-1 password-error"></span>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="confirm_password" class="title-color d-flex gap-1 align-items-center">{{translate('confirm_password')}}</label>
                        <div class="input-group input-group-merge">
                            <input type="password" class="js-toggle-password form-control"
                                   name="confirm_password" required id="confirm_password"
                                   placeholder="{{ translate('confirm_password') }}"
                                   data-hs-toggle-password-options='{
                                                         "target": "#changeConfirmPassTarget",
                                                        "defaultClass": "tio-hidden-outlined",
                                                        "showClass": "tio-visible-outlined",
                                                        "classChangeTarget": "#changeConfirmPassIcon"
                                                }'>
                            <div id="changeConfirmPassTarget" class="input-group-append">
                                <a class="input-group-text" href="javascript:">
                                    <i id="changeConfirmPassIcon" class="tio-visible-outlined"></i>
                                </a>
                            </div>
                        </div>
                        <div class="pass invalid-feedback">{{translate('repeat_password_not_match').'.'}}</div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Basic Information --}}
        <div class="card mt-3">
            <div class="card-body">
                <input type="hidden" name="status" value="approved">
                <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2 border-bottom pb-3 mb-4 pl-4">
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/vendor-information.png')}}" class="mb-1" alt="">
                    Basic Information
                </h5>
                <div class="row">
                    <div class="col-12 col-lg-6  col-md-6">
                        <div class="col-lg-12 form-group " id="multiStepForm">
                            <label  class="title-color d-flex gap-1 align-items-center">
                                Registration Type
                            </label>
                            <div class="d-flex  multiStepFormlabel">
                                <div class="col">
                                    <label for="commercial" class="d-flex justify-content-between border p-2  align-content-center" > 
                                    <div class="d-flex align-content-center">Commercial</div>
                                    <div class="d-flex align-content-center">
                                         <input class="" type="radio" name="radio_check" value="commercial" id="commercial">
                                        </div></label>
                                </div>
                                <div class="col">
                                    <label for="freelancing" class="d-flex justify-content-between border p-2  align-content-center" >
                                    <div class="d-flex align-content-center">Freelancing</div>
                                    <div class="d-flex align-content-center">
                                         <input class="" type="radio" name="radio_check" value="freelancing" id="freelancing"></div>
                                        </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 form-group">
                            <label for="business_day" class="title-color d-flex gap-1 align-items-center">
                                Business name as seen on Certification
                            </label>
                            <div class="input-group input-group-merge">
                                <input type="text" class=" form-control" name="business_day" required id="business_day"  placeholder="Business name" >
                            </div>
                        </div>
                        <div class="col-lg-12 form-group">
                            <label for="establishment" class="title-color d-flex gap-1 align-items-center">Year of establishment</label>
                            <div class="input-group input-group-merge">
                                    <select id="establishment" class="custom-select" name="establishment">
                                        <option value="2020">2020</option>
                                        <option value="2019">2019</option>
                                        <option value="2018">2018</option>
                                    </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6  col-md-6">
                        <div class="col-lg-12 form-group">
                            <div class="d-flex justify-content-center">
                                <img class="upload-img-view" id="upload_certifice"
                                    src="{{dynamicAsset(path: 'public/assets/back-end/img/400x400/img2.jpg')}}" alt="{{translate('banner_image')}}"/>
                            </div>

                            <div class="mt-4">
                                <div class="d-flex gap-1 align-items-center title-color mb-2">
                                    Upload Certification
                                </div>
                                <div class="custom-file">
                                    <input type="file" name="upload_certifice" id="upload_certifice" class="custom-file-input image-input"
                                        data-image-id="upload_certifice"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    <label class="custom-file-label" for="upload_certifice">Upload Certification</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- shop information --}}
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2 border-bottom pb-3 mb-4 pl-4">
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/vendor-information.png')}}" class="mb-1" alt="">
                    {{translate('shop_information')}}
                </h5>

                <div class="row">
                    <div class="col-lg-6 form-group">
                        <label for="shop_address" class="title-color d-flex gap-1 align-items-center">
                            Shop Address
                        </label>
                        <div class="input-group input-group-merge">
                            <textarea id="shop_address" name="shop_address" class="form-control" rows="4" placeholder="Address"></textarea>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 form-group">
                        <label for="brief_here" class="title-color d-flex gap-1 align-items-center">
                            Write a brief about your shop
                        </label>
                        <div class="input-group input-group-merge">
                            <textarea id="brief_here" name="brief_here" class="form-control" rows="4" placeholder="Brief here"></textarea>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 form-group">
                        <label for="latitude" class="title-color d-flex gap-1 align-items-center">
                            Latitude
                        </label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="latitude" name="latitude" class="form-control E9ECEFcolor" placeholder="Ex: -94.22213" readonly>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 form-group">
                        <label for="longitude" class="title-color d-flex gap-1 align-items-center">
                            Longitude
                        </label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="longitude" name="longitude" class="form-control E9ECEFcolor" placeholder="Ex: 103.344322" readonly>
                        </div>
                    </div>
                    
                    <!-- Search Location Field -->
                    <div class="col-lg-12 form-group">
                        <label for="autocomplete" class="title-color d-flex gap-1 align-items-center">
                            Search Location
                        </label>
                        <div class="input-group input-group-merge">
                            <input id="autocomplete" class="form-control" placeholder="Search for a place" type="text">
                        </div>
                    </div>
                    
                    <!-- Google Map Embed -->
                    <div class="col-lg-12 form-group map-container" id="map" style="height: 200px;">
                        <iframe  
                          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3629.650719447814!2d46.675295114785446!3d24.713552084119667!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e2f02b3e037d0d3%3A0x37e34a9e59ad82c8!2sRiyadh!5e0!3m2!1sen!2ssa!4v1234567890123"
                          allowfullscreen=""
                          loading="lazy"></iframe>
                    </div>
                    
                    <div class="col-lg-6 form-group">
                        <label for="city" class="title-color d-flex gap-1 align-items-center">
                            City
                        </label>
                        <div class="input-group input-group-merge">
                            <select id="city" class="custom-select" name="city">
                                <option value="Riyadh">Riyadh</option>
                                <option value="Jeddah">Jeddah</option>
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
                
                    {{-- <div class="col-lg-6 form-group">
                        <label for="business_day" class="title-color d-flex gap-1 align-items-center">
                            Business name as seen on Certification
                        </label>
                        <div class="input-group input-group-merge">
                            <input type="text" class=" form-control"name="business_day" required id="business_day"  placeholder="Business name" >
                        </div>
                    </div> --}}
                    <div class="col-lg-6 form-group">
                        <label for="shop_name" class="title-color d-flex gap-1 align-items-center">
                            {{translate('shop_name')}}
                        </label>
                        <input 
                            type="text" 
                            class="form-control form-control-user" 
                            id="shop_name" 
                            name="shop_name" 
                            placeholder="{{translate('ex').':'.translate('Jhon')}}" 
                            value="{{old('shop_name')}}" 
                            required>
                            <br>
                        <!-- Validation Messages -->
                        <small id="error-length-message" class="text-danger validation-message" style="display: none;"></small>
                        <small id="error-special-message" class="text-danger validation-message" style="display: none;"></small>
                        <small id="error-message" class="text-danger validation-message" ></small>
                        <small id="success-message" class="text-success validation-message" style="display: none;"></small>
                    </div>
                 
                    
                    <div class="col-lg-12 form-group">
                        <label for="shop_address" class="title-color d-flex gap-1 align-items-center">{{translate('shop_address')}}</label>
                        <textarea name="shop_address" class="form-control text-area-max" id="shop_address" rows="1" placeholder="{{translate('ex').':'.translate('doe')}}">{{old('shop_address')}}</textarea>
                    </div>
                    <div class="col-lg-6 form-group">
                        <div class="d-flex justify-content-center">
                            <img class="upload-img-view" id="viewerLogo"
                                src="{{dynamicAsset(path: 'public/assets/back-end/img/400x400/img2.jpg')}}" alt="{{translate('banner_image')}}"/>
                        </div>

                        <div class="mt-4">
                            <div class="d-flex gap-1 align-items-center title-color mb-2">
                                {{translate('shop_logo')}}
                                <span class="text-info">({{translate('ratio').' '.'1:1'}})</span>
                            </div>

                            <div class="custom-file">
                                <input type="file" name="logo" id="logo-upload" class="custom-file-input image-input"
                                       data-image-id="viewerLogo"
                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="logo-upload">{{translate('upload_logo')}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 form-group">
                        <div class="d-flex justify-content-center">
                            <img class="upload-img-view upload-img-view__banner" id="viewerBanner"
                                    src="{{dynamicAsset(path: 'public/assets/back-end/img/400x400/img2.jpg')}}" alt="{{translate('banner_image')}}"/>
                        </div>
                        <div class="mt-4">
                            <div class="d-flex gap-1 align-items-center title-color mb-2">
                                {{translate('shop_banner')}}
                                <span class="text-info">{{ THEME_RATIO[theme_root_path()]['Store cover Image'] }}</span>
                            </div>

                            <div class="custom-file">
                                <input type="file" name="banner" id="banner-upload" class="custom-file-input image-input"
                                       data-image-id="viewerBanner"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label text-capitalize" for="banner-upload">{{translate('upload_Banner')}}</label>
                            </div>
                        </div>
                    </div>

                    @if(theme_root_path() == "theme_aster")
                    <div class="col-lg-6 form-group">
                        <div class="d-flex justify-content-center">
                            <img class="upload-img-view upload-img-view__banner" id="viewerBottomBanner"
                                    src="{{dynamicAsset(path: 'public/assets/back-end/img/400x400/img2.jpg')}}" alt="{{translate('banner_image')}}"/>
                        </div>

                        <div class="mt-4">
                            <div class="d-flex gap-1 align-items-center title-color mb-2">
                                {{translate('shop_secondary_banner')}}
                                <span class="text-info">{{ THEME_RATIO[theme_root_path()]['Store Banner Image'] }}</span>
                            </div>

                            <div class="custom-file">
                                <input type="file" name="bottom_banner" id="bottom-banner-upload" class="custom-file-input image-input"
                                       data-image-id="viewerBottomBanner"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label text-capitalize" for="bottom-banner-upload">{{translate('upload_bottom_banner')}}</label>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

                <div class="d-flex align-items-center justify-content-end gap-10">
                    <input type="hidden" name="from_submit" value="admin">
                    <button type="reset" class="btn btn-secondary reset-button">{{translate('reset')}} </button>
                    <button type="button" class="btn btn--primary btn-user form-submit" data-form-id="add-vendor-form" data-redirect-route="{{route('admin.vendors.vendor-list')}}"
                            data-message="{{translate('want_to_add_this_vendor').'?'}}">{{translate('submit')}}</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection


@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/plugins/intl-tel-input/js/intlTelInput.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/country-picker-init.js') }}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/vendor.js')}}"></script>
    {{-- <script>
        function initMap() {
          // Interactive map initialize karna
          const map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: 24.7136, lng: 46.6753 }, // Riyadh ke coordinates
            zoom: 10,
          });
    
          // Click event listener
          map.addListener("click", (event) => {
            const lat = event.latLng.lat(); // Latitude nikalna
            const lng = event.latLng.lng(); // Longitude nikalna
    
            // Input fields mein values insert karna
            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;
    
            // Alert show karna
            alert(`Latitude: ${lat}, Longitude: ${lng}`);
          });
        }
    
        // Map initialize karna jab page load ho
        window.onload = initMap;
      </script> --}}
      <!-- Google Maps API Script -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB3VTAihhs6gEYNld1LMwNkEiszH3TRcMQ&libraries=places&callback=initMap" async></script>
<script>
    $(document).ready(function() {
        $('#shop_name').on('keyup', function() {
            const shopName = $(this).val();

            // Hide all validation messages
            $('.validation-message').hide();

            // Length and character validation
            const specialCharRegex = /[^a-zA-Z0-9 ]/; // No special characters
            if (shopName.length < 4 || shopName.length > 20) {
                $('#error-length-message').text("Shop name must be between 4 and 20 characters.").show();
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
                    $('#error-message').text("An error occurred while checking the shop name.").show();
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
            center: { lat: 24.7136, lng: 46.6753 },  // Default coordinates
            zoom: 13,
        });

        // Create a draggable marker
        marker = new google.maps.Marker({
            map: map,
            draggable: true,
            position: map.getCenter(),  // Initially place it at the center of the map
        });

        // Add an event listener to update latitude and longitude when the marker is dragged
        google.maps.event.addListener(marker, 'dragend', function() {
            const latLng = marker.getPosition();
            document.getElementById("latitude").value = latLng.lat();
            document.getElementById("longitude").value = latLng.lng();
        });

        // Initialize the Places Autocomplete feature
        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById("autocomplete"),
            {
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
