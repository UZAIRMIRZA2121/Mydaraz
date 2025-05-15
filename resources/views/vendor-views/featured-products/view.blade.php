@extends('layouts.back-end.app-seller')
@section('title', translate('order_List'))

@push('css_or_js')
    <link href="{{ dynamicAsset(path: 'public/assets/back-end/vendor/datatables/dataTables.bootstrap4.min.css') }}"
        rel="stylesheet">
@endpush


@section('content')
    <div class="content container-fluid">
        <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
            <h2 class="h1 mb-1 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/banner.png') }}" alt="">
                {{ translate('Promotion Setup') }}
                <small>
                    <strong class="text--primary text-capitalize">
                        ({{ str_replace('_', ' ', theme_root_path() == 'theme_fashion' ? 'theme_lifestyle' : theme_root_path()) }})
                    </strong>
                </small>
            </h2>
            <div class="btn-group">
                <div class="ripple-animation" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none"
                        class="svg replaced-svg">
                        <path
                            d="M9.00033 9.83268C9.23644 9.83268 9.43449 9.75268 9.59449 9.59268C9.75449 9.43268 9.83421 9.2349 9.83366 8.99935V5.64518C9.83366 5.40907 9.75366 5.21463 9.59366 5.06185C9.43366 4.90907 9.23588 4.83268 9.00033 4.83268C8.76421 4.83268 8.56616 4.91268 8.40616 5.07268C8.24616 5.23268 8.16644 5.43046 8.16699 5.66602V9.02018C8.16699 9.25629 8.24699 9.45074 8.40699 9.60352C8.56699 9.75629 8.76477 9.83268 9.00033 9.83268ZM9.00033 13.166C9.23644 13.166 9.43449 13.086 9.59449 12.926C9.75449 12.766 9.83421 12.5682 9.83366 12.3327C9.83366 12.0966 9.75366 11.8985 9.59366 11.7385C9.43366 11.5785 9.23588 11.4988 9.00033 11.4993C8.76421 11.4993 8.56616 11.5793 8.40616 11.7393C8.24616 11.8993 8.16644 12.0971 8.16699 12.3327C8.16699 12.5688 8.24699 12.7668 8.40699 12.9268C8.56699 13.0868 8.76477 13.1666 9.00033 13.166ZM9.00033 17.3327C7.84755 17.3327 6.76421 17.1138 5.75033 16.676C4.73644 16.2382 3.85449 15.6446 3.10449 14.8952C2.35449 14.1452 1.76088 13.2632 1.32366 12.2493C0.886437 11.2355 0.667548 10.1521 0.666992 8.99935C0.666992 7.84657 0.885881 6.76324 1.32366 5.74935C1.76144 4.73546 2.35505 3.85352 3.10449 3.10352C3.85449 2.35352 4.73644 1.7599 5.75033 1.32268C6.76421 0.88546 7.84755 0.666571 9.00033 0.666016C10.1531 0.666016 11.2364 0.884905 12.2503 1.32268C13.2642 1.76046 14.1462 2.35407 14.8962 3.10352C15.6462 3.85352 16.24 4.73546 16.6778 5.74935C17.1156 6.76324 17.3342 7.84657 17.3337 8.99935C17.3337 10.1521 17.1148 11.2355 16.677 12.2493C16.2392 13.2632 15.6456 14.1452 14.8962 14.8952C14.1462 15.6452 13.2642 16.2391 12.2503 16.6768C11.2364 17.1146 10.1531 17.3332 9.00033 17.3327ZM9.00033 15.666C10.8475 15.666 12.4206 15.0168 13.7195 13.7185C15.0184 12.4202 15.6675 10.8471 15.667 8.99935C15.667 7.15213 15.0178 5.57907 13.7195 4.28018C12.4212 2.98129 10.8481 2.33213 9.00033 2.33268C7.1531 2.33268 5.58005 2.98185 4.28116 4.28018C2.98227 5.57852 2.3331 7.15157 2.33366 8.99935C2.33366 10.8466 2.98283 12.4196 4.28116 13.7185C5.57949 15.0174 7.15255 15.6666 9.00033 15.666Z"
                            fill="currentColor"></path>
                    </svg>
                </div>
                <div
                    class="dropdown-menu dropdown-menu-right bg-aliceblue border border-color-primary-light p-4 dropdown-w-lg-30">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/note.png') }}"
                            alt="">
                        <h5 class="text-primary mb-0">{{ translate('note') }}</h5>
                    </div>
                    <p class="title-color font-weight-medium mb-0">{{ translate('currently_you_are_managing_banners_for') }}
                        {{ ucwords(str_replace('_', ' ', theme_root_path())) }}.{{ translate('these_saved_data_is_only_applicable_only_for_') }}{{ ucwords(str_replace('_', ' ', theme_root_path())) }}.{{ translate('if_you_change_theme_from_theme_setup_these_banners_will_not_be_shown_in_changed_theme._You_have_upload_all_the_banners_over_again _according_to_the_new_theme_ratio_and_sizes._If_you_switch_back_to_') }}{{ ucwords(str_replace('_', ' ', theme_root_path())) }}{{ translate('_again_,_you_will_see_the_saved_data.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="row pb-4  text-start" id="main-banner">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 text-capitalize">{{ translate('Promotion Type') }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('vendor.featured-product.store') }}" method="post">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="row">
                                        <input type="hidden" id="id" name="id">
                                        <input type="hidden" id="promotion_type" name="promotion_id"
                                            value="{{ $promotion->id }}">
                                        <div class="form-group col-md-6">
                                            <label for="name" class="title-color text-capitalize">
                                                {{ translate('Promotion Type') }}
                                            </label>
                                            <h3>{{ $promotion->promotion_type }}</h3>

                                        </div>
                                        <div class="form-group mb-0 col-md-6" id="resource-product">
                                            <label for="product_id"
                                                class="title-color text-capitalize">{{ translate('product') }}</label>
                                            <select class="js-example-responsive form-control w-100" name="product_id[]"
                                                multiple id="product-select">
                                                @foreach ($products as $product)
                                                    <option value="{{ $product['id'] }}"
                                                        data-thumbnail="{{ getStorageImages(path: $product->thumbnail_full_url, type: 'backend-product') }}">
                                                        {{ $product['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>




                                        <div class="form-group col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Start Date</label>
                                                <input type="date" id="start_date" name="start_date" class="form-control"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">End Date</label>
                                                <input type="date" id="end_date" name="end_date" class="form-control"
                                                    required>
                                            </div>
                                        </div>




                                    </div>
                                </div>
                                <!-- Selected Product Images Display -->
                                <div id="selected-product-images" class="mt-3 d-flex flex-wrap gap-2"></div>

                                <div class="col-12 d-flex justify-content-end flex-wrap gap-10">
                                    <button class="btn btn-secondary cancel px-4"
                                        type="reset">{{ translate('reset') }}</button>
                                    <button id="add" type="submit"
                                        class="btn btn--primary px-4">{{ translate('Pay Now') }}</button>
                                    <button id="update"
                                        class="btn btn--primary d--none text-white">{{ translate('update') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <style>
                #invoice-print {
                    font-size: 18px;
                    /* Increase overall font size */
                }

                #invoice-print h6 {
                    font-size: 20px;
                    /* Increase label font size */
                    font-weight: bold;
                }

                #invoice-print .card-header h5 {
                    font-size: 24px;
                    /* Make invoice title bigger */
                    font-weight: bold;
                }

                #invoice-print span {
                    font-size: 20px;
                    /* Make values larger */
                    color: #333;
                }

                #invoice-print button {
                    font-size: 18px;
                    /* Bigger button text */
                    font-weight: bold;
                }
            </style>

            <div class="col-md-4">
                <div id="invoice-print" class="mt-3 ">
                    <div class="card">
                        <div class="card-header bg-dark text-white text-center">
                            <h5 class="mb-0 text-white">Invoice</h5>
                        </div>
                        <div class="card-body">
                            <h6><b>Invoice No:</b> <span id="print-invoice-no"></span></h6>
                            <h6><b>Type:</b> <span id="print-customer-name">{{ $promotion->promotion_type }} </span></h6>
                            <h6><b>Price per Day:</b> SAR <span id="print-price">{{ $promotion->price }}</span></h6>
                            <h6><b>Number of Product:</b> <span id="print-product"></span></h6>
                            <h6><b>Number of Days:</b> <span id="print-days"></span></h6>
                            <h6><b>Total Price:</b> SAR <span id="print-total"></span></h6>
                            <hr>

                            {{-- <p class="text-center">Thank you for your business!</p> --}}
                        </div>
                    </div>
                    {{-- <button onclick="window.print()" class="btn btn-success w-100 mt-2">Print Invoice</button> --}}
                </div>
            </div>
        </div>

        <span id="route-admin-banner-store" data-url="{{ route('admin.banner.store') }}"></span>
        <span id="route-admin-banner-delete" data-url="{{ route('admin.banner.delete') }}"></span>
    @endsection
    <!-- Include jQuery (if not already included) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        $("#start_date").on("change", function () {
          
            let startDate = $(this).val();
            if (startDate) {
                let minEndDate = new Date(startDate);
                minEndDate.setDate(minEndDate.getDate() + 1); // Minimum next day
         
                let maxEndDate = new Date(startDate);
                maxEndDate.setDate(maxEndDate.getDate() + 14); // Maximum 14 days ahead
                
                let minDateStr = minEndDate.toISOString().split("T")[0];
                let maxDateStr = maxEndDate.toISOString().split("T")[0];

                $("#end_date").attr("min", minDateStr);
                $("#end_date").attr("max", maxDateStr);
                $("#end_date").val(""); // Reset end date if already selected
            }
        });
    });
</script>
    <script>
        $(document).ready(function() {
            $('#product-select').on('change', function() {
                let selectedImagesContainer = $('#selected-product-images');
                selectedImagesContainer.empty(); // Clear previous images


                let selectedProducts = $('#product-select option:selected');
                let productCount = selectedProducts.length;

                // Update product count in invoice
                $('#print-product').text(productCount);
                $('#product-select option:selected').each(function() {
                    let imageUrl = $(this).data('thumbnail');
                    if (imageUrl) {
                        let imgElement =
                            `<img src="${imageUrl}" class=" border object-fit-cover m-1" width="150" height="150">`;
                        selectedImagesContainer.append(imgElement);
                    }
                });
            });
        });
    </script>

    <script>
        document.getElementById('start_date').addEventListener('change', function() {
            let startDate = new Date(this.value);
            let endDateField = document.getElementById('end_date');

            if (startDate) {
                let maxDate = new Date(startDate);
                maxDate.setDate(startDate.getDate() + 14); // Add 14 days to start date

                let minDateStr = startDate.toISOString().split('T')[0]; // Format YYYY-MM-DD
                let maxDateStr = maxDate.toISOString().split('T')[0];

                endDateField.setAttribute('min', minDateStr); // Set min date to selected start date
                endDateField.setAttribute('max', maxDateStr); // Set max date to 14 days after start date
                endDateField.value = minDateStr; // Reset end date if it was set before
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            const maxSelection = 4; // Set maximum selection limit

            $('.js-example-responsive').select2({
                width: '100%',
                placeholder: "Select Products",
                allowClear: true
            });

            $('#product-select').on('select2:select', function(e) {
                let selectedOptions = $(this).val();
                if (selectedOptions.length > maxSelection) {
                    let removedOption = selectedOptions.pop(); // Remove last selected option
                    $(this).val(selectedOptions).trigger('change');
                    alert(`You can only select up to ${maxSelection} products.`);
                }
            });
        });
    </script>
    <script>
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
            });
        @endif

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
            });
        @endif
    </script>


    <script>
    $(document).ready(function() {
    // Ensure price is correctly assigned
    let price = parseFloat("{{ $promotion->price }}") || 0;

    // Get today's date and tomorrow's date
    let today = new Date();
    let tomorrow = new Date();
    tomorrow.setDate(today.getDate() + 1);
    let tomorrowStr = tomorrow.toISOString().split('T')[0];

    // Set the default start date to tomorrow
    $('#start_date').attr('min', tomorrowStr).val(tomorrowStr);

    // Function to update invoice total dynamically
    function updateInvoiceTotal() {
        let days = calculateDays();
        let numProducts = $('#product-select option:selected').length;

        // Ensure at least 1 day and 1 product
        if (days < 1) days = 1;
        if (numProducts < 1) numProducts = 1;

        $('#days').val(days);
        $('#print-days').text(days);
        $('#print-product').text(numProducts);

        let total = price * days * numProducts;

        // Update invoice display
        $('#print-total').text(total.toFixed(2));
    }

    // Function to calculate the number of days between start and end date
    function calculateDays() {
        let startDate = $('#start_date').val();
        let endDate = $('#end_date').val();
        if (!startDate || !endDate) return 1;

        let start = new Date(startDate);
        let end = new Date(endDate);

        let timeDiff = end - start;
        let daysDiff = Math.ceil(timeDiff / (1000 * 60 * 60 * 24)); // Convert milliseconds to days

        return daysDiff;
    }

    // Function to update end date based on selected days
    function updateEndDate() {
        let days = parseInt($('#days').val()) || 1;
        let startDate = $('#start_date').val();
        if (!startDate) return;

        let start = new Date(startDate);
        start.setDate(start.getDate() + days);

        let endDate = start.toISOString().split('T')[0];
        $('#end_date').val(endDate);
        $('#print-end-date').text(endDate);

        // Set min attribute to ensure end date is not before start date
        $('#end_date').attr('min', startDate);

        updateInvoiceTotal(); // Update total when end date is set
    }

    // Prevent selecting a start date before tomorrow
    $('#start_date').on('change', function() {
        let startDate = $(this).val();
        if (new Date(startDate) < tomorrow) {
            $(this).val(tomorrowStr);
            startDate = tomorrowStr;
        }

        // Ensure end date is not before start date
        let endDate = $('#end_date').val();
        if (endDate && new Date(endDate) < new Date(startDate)) {
            $('#end_date').val(startDate);
        }

        updateEndDate();
    });

    // Prevent selecting an end date before the start date
    $('#end_date').on('change', function() {
        let startDate = $('#start_date').val();
        let endDate = $(this).val();

        if (startDate && new Date(endDate) < new Date(startDate)) {
            $(this).val(startDate);
        }

        updateInvoiceTotal();
    });

    // Set default values on page load
    $('#days').val(1);
    updateEndDate(); // Ensures end date updates based on start date

    // Event listeners
    $('#days').on('keyup change', updateEndDate);
    $('#start_date, #end_date').on('change', updateInvoiceTotal);
    $('#product-select').on('change', updateInvoiceTotal);

    // Trigger function when .add_end is interacted with
    $(document).on('click focus', '.add_end', function() {
        updateInvoiceTotal();
    });
});

    </script>




    @push('script')
        <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/banner.js') }}"></script>
        <script>
            "use strict";

            $(document).on('ready', function() {
                getThemeWiseRatio();
            });

            let elementBannerTypeSelect = $('#banner_type_select');

            function getThemeWiseRatio() {
                let banner_type = elementBannerTypeSelect.val();
                let theme = '{{ theme_root_path() }}';
                let theme_ratio = {!! json_encode(THEME_RATIO) !!};
                let get_ratio = theme_ratio[theme][banner_type];
                $('#theme_ratio').text(get_ratio);
            }

            elementBannerTypeSelect.on('change', function() {
                getThemeWiseRatio();
            });
        </script>
    @endpush
