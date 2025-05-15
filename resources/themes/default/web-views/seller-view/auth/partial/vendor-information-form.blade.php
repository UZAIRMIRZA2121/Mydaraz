{!! NoCaptcha::renderJs() !!}
<div class="second-el d--none">
    <style>
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .step {
            display: inline-block;
            padding: 7px 10px;
            background-color: #F3F4F7;
            color: #9EA8BD;
            border-radius: 5px;
            position: relative;
            flex-grow: 1;
            text-align: center;
            cursor: pointer;
            font-size: 12px;
            clip-path: polygon(88% 1%, 100% 50%, 87% 100%, 0% 100%, 7% 49%, 0% 0%);
        }

        .step.active {
            color: #0F0F0F;
            font-weight: bold;
        }

        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
        }

        .steps_borders {
            border: 2px solid black;
            border-radius: 10px;
        }

        .btn-C0C0C0 {
            background-color: #C0C0C0;
        }

        .btn-C0C0C0:hover {
            background-color: #C0C0C0;
        }

        .btn-FC4D05 {
            background-color: #FC4D05;
        }

        .btn-FC4D05:hover {
            background-color: #FC4D05;
        }

        .steps_title {
            font-size: 13px;
            font-weight: 600 !important;
            margin-top: 30px;
        }

        .step_label {
            font-size: 12px;
            font-weight: 700 !important;
        }

        .coloFAFBFC {
            background-color: #FAFBFC;
        }

        #multiStepForm input {
            box-shadow: 0 0 5px rgba(169, 169, 169, 0.3);
            font-size: 14px;
            border: none;
            transition: box-shadow 0.3s ease;
        }

        #multiStepForm select {
            box-shadow: none;
            font-size: 14px;
            box-shadow: 0 0 5px rgba(169, 169, 169, 0.3);
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

        .multiStepFormlabel label {
            font-size: 14px;
        }

        .colorFCFCFC {
            background-color: #FCFCFC;
        }

        .step_upload_file {
            font-size: 14px;
        }

        .upload-button {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 8px 12px;
            width: 200px;
            cursor: pointer;
        }

        .upload-button input[type="file"] {
            display: none;
        }

        .placeholder_target input::placeholder {
            font-size: 12px;
        }

        .placeholder_target textarea::placeholder {
            font-size: 12px;
        }

        .upload-icon {
            color: #000000;
            font-size: 10px;
            border: 2px solid #007bff;
            padding: 3px 5px;
            border-radius: 5px;
        }

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

        .validation-message {
            font-size: 14px;
        }

        .text-success i,
        .text-danger i {
            margin-right: 5px;
        }

        .hidden {
            display: none;
        }

        .E9ECEFcolor {
            background-color: #E9ECEF !important;
        }

        .step5_p p {
            font-size: 13px;
            font-weight: 600;
        }

        .step5_p h6 {
            font-size: 12px;
        }

        .step6_p p {
            font-size: 13px;
            font-weight: 600;
        }

        .wizard_circle {
            width: 25px;
            height: 25px;
            border: 2px solid #ddd;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #fff;
            color: #ddd;
            font-size: 16px;
            font-weight: bold;
            z-index: 1;
        }

        .step.active .circle {
            border-color: #0000 !important;
            background-color: #0000;
            color: #fff;
        }

        .circle-black {
            color: black !important;
        }
    </style>
    @if ($errors->has('captcha'))
        <div class="alert alert-danger">
            {{ $errors->first('captcha') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="container my-5 coloFAFBFC p-lg-5 p-md-5">
        <div class="row flex justify-content-center">
            <div class="col-11 col-md-11 col-lg-11 pt-3">
                <div class="row  flex-wrap justify-content-around text-center ">
                    <div class="col-12 col-md-10 col-lg-10">
                        <div class="step-indicator flex-wrap  grid row-cols-6 row-cols-lg-6">
                            <span class="col mb-2 px-2 d-flex align-items-center step active" data-step="1">
                                <div class="wizard_circle d-lg-none d-md-none circle-black">✓</div>
                                <div class="d-none d-md-block d-lg-block px-1 d-flex align-content-center">Business Info
                                </div>
                            </span>
                            <span class="col mb-2 px-2 d-flex align-content-center   step" data-step="2">
                                <div class="wizard_circle d-lg-none d-md-none circle-black ">✓</div>
                                <div class="d-none d-md-block d-lg-block px-1 d-flex align-content-center"> Shop Address
                                </div>
                            </span>
                            <span class="col mb-2 px-2 d-flex align-content-center   step" data-step="3">
                                <div class="wizard_circle d-lg-none d-md-none circle-black ">✓</div>
                                <div class="d-none d-md-block d-lg-block px-1 d-flex align-content-center"> Shop Name
                                </div>
                            </span>
                            <span class="col mb-2 px-2 d-flex align-content-center   step" data-step="4">
                                <div class="wizard_circle d-lg-none d-md-none circle-black ">✓</div>
                                <div class="d-none d-md-block d-lg-block px-1 d-flex align-content-center"> Shop
                                    Activity </div>
                            </span>
                            <span class="col mb-2 px-2 d-flex align-content-center   step" data-step="5">
                                <div class="wizard_circle d-lg-none d-md-none circle-black ">✓</div>
                                <div class="d-none d-md-block d-lg-block px-1 d-flex align-content-center"> Business
                                    Plan </div>
                            </span>
                            <span class="col mb-2 px-2 d-flex align-content-center   step" data-step="6">
                                <div class="wizard_circle d-lg-none d-md-none circle-black ">✓</div>
                                <div class="d-none d-md-block d-lg-block px-1 d-flex align-content-center"> Terms and
                                    Conditions </div>
                            </span>
                        </div>
                    </div>
                </div>
                <form id="multiStepForm" class="user placeholder_target" action="{{ route('custome.vendors.store') }}"
                    method="post" enctype="multipart/form-data">
                    @csrf

                </form>
                <form id="multiStepForm" class="user placeholder_target" action="{{ route('custome.vendors.store') }}"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-step active" data-step="1">
                        <div class="row steps_borders p-1  p-lg-5">
                            <div class="col-md-12   mx-lg-5">
                                <h5 class="mb-4 steps_title">Registration Type</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex  multiStepFormlabel mb-4 gap-3">
                                            <div
                                                class="col d-flex justify-content-between border p-2  align-items-center colorFCFCFC">
                                                <div><label for="commercial">Commercial</label></div>
                                                <div>
                                                    <input class="show" type="radio" name="radio_check"
                                                        value="commercial" id="commercial">
                                                </div>
                                            </div>
                                            <div
                                                class="col d-flex justify-content-between border p-2  align-items-center colorFCFCFC">
                                                <div><label for="freelancing">Freelancing</label></div>
                                                <div> <input class="show" type="radio" name="radio_check"
                                                        value="freelancing" id="freelancing"></div>
                                            </div>
                                        </div>
                                        <p id="radioCheckError" class="text-danger error-message"></p>
                                        <div class="mb-2">
                                            <label for="businessName" class="form-label step_label">Business name as
                                                seen on Certification</label>
                                            <input type="text" id="businessName" name="businessName"
                                                class="form-control" placeholder="Business Name">
                                            <p id="businessNameError" class="text-danger error-message"></p>
                                        </div>
                                        <div class="mb-2">
                                            <label for="establishment" class="form-label step_label">Year of
                                                establishment</label>
                                                <select id="establishment" name="establishment" class="custom-select"></select>

                                            <p id="establishmentError" class="text-danger error-message"></p>
                                        </div>
                                        <script>
                                            const select = document.getElementById("establishment");
                                            const currentYear = new Date().getFullYear();
                                            const startYear = currentYear - 49; // Last 50 years including current year
                                        
                                            for (let year = currentYear; year >= startYear; year--) {
                                                const option = document.createElement("option");
                                                option.value = year;
                                                option.textContent = year;
                                                select.appendChild(option);
                                            }
                                        </script>
                                        
                                        <div class="mb-2 form-group">
                                            <div class="title-color mb-2 d-flex gap-1 align-items-center">
                                                Certificate
                                            </div>
                                            <div class="custom-file text-left">
                                                <input type="file" name="image" id="custom-file-upload"
                                                    class="custom-file-input image-input" data-image-id="viewer"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                <label class="custom-file-label"
                                                    for="custom-file-upload">{{ translate('upload_image') }}</label>
                                            </div>
                                            <p id="certificateImageError" class="text-danger error-message"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <div class="d-flex justify-content-center">
                                                <img class="upload-img-view" id="viewer"
                                                    src="{{ dynamicAsset(path: 'public/assets/back-end/img/400x400/img2.jpg') }}"
                                                    alt="{{ translate('banner_image') }}"
                                                    style="max-width: 190px; height: auto;" />
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-step" data-step="2">
                        <div class="container">
                            <div class="row steps_borders p-2 p-lg-5">
                                <div class="col-md-6 mb-3">
                                    <div class="mb-3">
                                        <label for="city" class="form-label step_label">City</label>
                                        <select id="city" class="custom-select" name="city"
                                            style="width: 100%;">
                                            <option value="">Select a city</option>
                                            <option value="Riyadh">Riyadh</option>
                                            <option value="Jeddah">Jeddah</option>
                                            <option value="Mecca">Mecca</option>
                                            <option value="Medina">Medina</option>
                                            <option value="Dammam">Dammam</option>
                                            <option value="Khobar">Khobar</option>
                                            <option value="Tabuk">Tabuk</option>
                                            <option value="Buraydah">Buraydah</option>
                                            <option value="Hail">Hail</option>
                                            <option value="Abha">Abha</option>
                                            <option value="Khamis Mushait">Khamis Mushait</option>
                                            <option value="Najran">Najran</option>
                                            <option value="Al Qassim">Al Qassim</option>
                                            <option value="Jazan">Jazan</option>
                                            <option value="Yanbu">Yanbu</option>
                                            <option value="Al Jubail">Al Jubail</option>
                                            <option value="Al Ahsa">Al Ahsa</option>
                                            <option value="Al Baha">Al Baha</option>
                                            <option value="Sakakah">Sakakah</option>
                                            <option value="Arar">Arar</option>
                                        </select>

                                    </div> <br>
                                    <p id="cityError" class="text-danger error-message"></p>
                                    <div class="mb-3">
                                        <label for="shop_address" class="form-label">Shop Address</label>
                                        <textarea id="shop_address" name="shop_address" class="form-control" rows="11" placeholder="Address"></textarea>
                                    </div>
                                    <br>
                                    <p id="shopAddressError" class="text-danger error-message"></p>
                                </div>

                                <div class="col-md-6">
                                    <!-- Add a container to prevent issues with dropdown visibility -->
                                    <div class="map-container mb-3">
                                        <input id="autocomplete" class="form-control"
                                            placeholder="Search for a place" type="text">
                                    </div>

                                    <!-- Google Map Container -->
                                    <div id="map" style="height: 400px;"></div>

                                    <div class="mb-3">
                                        <label for="latitude" class="form-label step_label">Latitude <i
                                                class="bi bi-info-circle " title="Enter Latitude"></i></label>
                                        <input type="text" id="latitude" name="latitude"
                                            class="form-control E9ECEFcolor" placeholder="Ex: -94.22213" readonly>
                                        <br>
                                        <p id="latitudeError" class="text-danger error-message"></p>
                                    </div>

                                    <div class="mb-3">
                                        <label for="longitude" class="form-label step_label">Longitude <i
                                                class="bi bi-info-circle " title="Enter Longitude"></i></label>
                                        <input type="text" id="longitude" name="longitude"
                                            class="form-control E9ECEFcolor" placeholder="Ex: 103.344322" readonly>
                                        <br>
                                        <p id="longitudeError" class="text-danger error-message"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-step" data-step="3">
                        <div class="container  steps_borders p-2 p-lg-5">
                            <div class="mb-4">
                                <label for="shop_name" class="form-label fw-bold">Name Your Shop</label>
                                <input type="text" id="shop_name" name="shop_name" class="form-control"
                                    placeholder="Shop name">
                                <div class="mt-2 mx-4 mt-4">
                                    <p id="error-length-message" class="text-danger validation-message"
                                        style="display: none;"></p>
                                    <p id="error-special-message" class="text-danger validation-message"
                                        style="display: none;"></p>
                                    <p id="error-message" class="text-danger validation-message"
                                        style="display: none;"></p>
                                    <p id="success-message" class="text-success validation-message"
                                        style="display: none;"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-step" data-step="4">
                        <div class="container ">
                            <div class="row steps_borders p-2 p-lg-5 p-md-4">
                                <div class="col-12 col-md-12 mb-3">
                                    <div class="mb-3">
                                        <label for="category" class="form-label step_label">choose category</label>
                                        @php
                                            use App\Models\Category;
                                            $categories = Category::where('parent_id', 0)->get();
                                        @endphp

                                        <select id="category" name="category" class="custom-select">
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>

                                        <br>
                                        <p id="category-error-message" class="text-danger validation-message"
                                            style="display: none;"></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="brief_here" class="form-label">write a brief about your
                                            shop</label>
                                        <textarea id="brief_here" name="brief_here" class="form-control" rows="11" placeholder="Brief here"></textarea> <br>
                                        <p id="brief-error-message" class="text-danger validation-message"
                                            style="display: none;"></p>
                                    </div>
                                </div>
                                {{-- <div class="col-12 col-md-6 mb-3">
                                    <!-- Shop Logo -->
                                    <div class="mb-2 form-group">
                                        <div class="title-color mb-2 d-flex gap-1 align-items-center">
                                            Shop Logo
                                        </div>
                                        <div class="custom-file text-left">
                                            <input type="file" name="shop_logo" id="shop-logo-upload"
                                                class="custom-file-input image-input" data-image-id="shopLogoViewer"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label" for="shop-logo-upload">
                                                {{ translate('upload_logo') }}
                                            </label>
                                        </div>
                                        <p id="shopLogoError" class="text-danger error-message"></p>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <div class="d-flex justify-content-center">
                                                <img class="upload-img-view" id="shopLogoViewer"
                                                    src="{{ dynamicAsset(path: 'public/assets/back-end/img/400x400/img2.jpg') }}"
                                                    alt="{{ translate('shop_logo') }}"
                                                    style="max-width: 190px; height: auto;" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Shop Banner -->
                                    <div class="mb-2 form-group">
                                        <div class="title-color mb-2 d-flex gap-1 align-items-center">
                                            Shop Banner
                                        </div>
                                        <div class="custom-file text-left">
                                            <input type="file" name="shop_banner" id="shop-banner-upload"
                                                class="custom-file-input image-input" data-image-id="shopBannerViewer"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label" for="shop-banner-upload">
                                                {{ translate('upload_banner') }}
                                            </label>
                                        </div>
                                        <p id="shopBannerError" class="text-danger error-message"></p>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <div class="d-flex justify-content-center">
                                                <img class="upload-img-view" id="shopBannerViewer"
                                                    src="{{ dynamicAsset(path: 'public/assets/back-end/img/400x400/img2.jpg') }}"
                                                    alt="{{ translate('shop_banner') }}"
                                                    style="max-width: 190px; height: auto;" />
                                            </div>
                                        </div>
                                    </div>

                                </div> --}}
                            </div>
                        </div>


                    </div>
                    <div class="form-step" data-step="5">
                        <div class="container py-4">
                            <div class="row steps_borders p-lg-5 p-md-5">
                                <div class="col-12 mb-3 my-2">
                                    <div class="card border-0 shadow-sm step5_p">
                                        <h6 class="text-muted mb-4 p-2">Business Plan</h6>
                                        <div class="card-body text-center p-2 p-md-5 p-lg-5">
                                            <p class="mb-2">
                                                Albazaar Commission Is <span class="fs-4">15%</span> If Product Price
                                                Is Below 150 Riyals
                                            </p>
                                            <p class="mb-3">
                                                Albazaar Commission Is <span class="fs-4">20%</span> If Product Price
                                                Is Above 150 Riyals
                                            </p>
                                            <p>
                                                Albazaar Incurs All Transaction Fees, Installment Payment Fees, And Any
                                                Other Fees Required
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-step" data-step="6">
                        <div class="container py-4">
                            <div class="row steps_borders p-1 p-md-4 p-lg-4">
                                <div class="col-12 ">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card border-0 shadow-sm p-2 p-md-4 p-lg-5">
                                            <p class="mb-4">
                                                List of terms and conditions a seller must agree on, below he must enter
                                                his name and
                                                email. The terms and conditions must be sent to the seller ema
                                            </p>
                                            <div class="mb-3">
                                                <div class="d-inline-block border p-3 rounded">
                                                    <div>
                                                        {!! NoCaptcha::display() !!}
                                                    </div>
                                                    <p id="captcha-error" style="color: red; display: none;">Please
                                                        complete the captcha.</p>
                                                </div>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="terms">
                                                <label class="form-check-label" for="terms">
                                                    I agree with the <a href="#" class="text-primary">terms &
                                                        conditions</a>
                                                </label>
                                            </div>
                                            <p id="terms-error" style="color: red; display: none;">You must agree to
                                                the terms & conditions.</p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-lg-end  justify-content-center mt-4">
                        <button type="button" class="btn btn-C0C0C0  mx-2 px-4 px-lg-4" id="prevBtn"
                            disabled>Back</button>
                        <button class="btn btn-FC4D05 text-white px-3" id="nextBtn">Proceed To Next</button>
                    </div>
                    {{-- <input type="submit" value="submit"> --}}
                </form>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/plugins/intl-tel-input/js/intlTelInput.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/country-picker-init.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/admin/vendor.js') }}"></script>
@endpush
<!-- SweetAlert2 CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.17/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.17/dist/sweetalert2.all.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    // Select all step indicators and form steps
    const steps = document.querySelectorAll('.step'); // Step indicators (e.g., dots or numbers)
    const formSteps = document.querySelectorAll('.form-step'); // Form sections for each step
    const prevBtn = document.getElementById('prevBtn'); // Previous button
    const nextBtn = document.getElementById('nextBtn'); // Next button
    const form = document.getElementById('multiStepForm'); // Form element

    let currentStep = 1; // Track the current step

    // Function to update the visibility of form steps and step indicators
    function updateFormSteps() {
        // Update active form steps
        formSteps.forEach((formStep) => {
            formStep.classList.remove('active');
            if (parseInt(formStep.dataset.step) === currentStep) {
                formStep.classList.add('active');
            }
        });

        // Update active step indicators
        steps.forEach((step) => {
            step.classList.remove('active');
            if (parseInt(step.dataset.step) === currentStep) {
                step.classList.add('active');
            }
        });

        // Update button states
        prevBtn.disabled = currentStep === 1; // Disable Previous button on the first step
        nextBtn.textContent = currentStep === steps.length ? 'Submit' : 'Proceed To Next'; // Change button text
    }

    // Handle Next button click
    nextBtn.addEventListener('click', (e) => {
        e.preventDefault(); // Prevent default button behavior (like form submission)

        if (currentStep < steps.length) {
            console.log(currentStep + '----------' + steps.length);

            // Validate the current step dynamically based on currentStep
            const validateCurrentStep = window[`validateStep${currentStep}`];
            if (validateCurrentStep && validateCurrentStep()) {
                currentStep++; // Move to the next step
                updateFormSteps(); // Update the form to the next step
            }
        } else {
            // Reset error messages
            $("#captcha-error").hide();
            $("#terms-error").hide();

            let isValid = true;

            // Check if the reCAPTCHA is not checked
            if (!grecaptcha.getResponse()) {
                $("#captcha-error").show();
                isValid = false;
            }

            // Check if the terms checkbox is not checked
            if (!$('#terms').is(':checked')) {
                $("#terms-error").show();
                isValid = false;
            }

            // If validation fails, prevent form submission
            if (!isValid) {
                alert('Please correct the errors before submitting the form.');
                event.preventDefault();
            }
            if (isValid) {
                form.submit(); // Submit the form on the last step
            }
        }
    });


    // Handle Previous button click
    prevBtn.addEventListener('click', (e) => {
        e.preventDefault(); // Prevent default button behavior

        if (currentStep > 1) {
            currentStep--; // Move to the previous step
            updateFormSteps();
        }
    });

    // Initialize form steps on page load
    updateFormSteps();

    // Generic validation function
    function validateStep1() {
        let isValid = true;

        // Clear previous error messages
        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

        // Check if a radio button is selected for Registration Type
        const radioCheck = document.querySelector('input[name="radio_check"]:checked');
        if (!radioCheck) {
            document.getElementById('radioCheckError').textContent = "Please select a registration type.";
            isValid = false;
        }

        // Business Name validation
        const businessName = document.getElementById('businessName').value;
        if (!businessName) {
            document.getElementById('businessNameError').textContent = "Business name is required.";
            isValid = false;
        }

        // Year of establishment validation
        const establishment = document.getElementById('establishment').value;
        if (!establishment) {
            document.getElementById('establishmentError').textContent = "Year of establishment is required.";
            isValid = false;
        }

        // Certificate Image validation
        const certificateImage = document.getElementById('custom-file-upload').files[0];
        if (!certificateImage) {
            document.getElementById('certificateImageError').textContent =
                "Please upload your business certificate image.";
            isValid = false;
        } else {
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/bmp', 'image/tiff'];
            if (!validTypes.includes(certificateImage.type)) {
                document.getElementById('certificateImageError').textContent =
                    "Invalid image type. Please upload a jpg, png, or jpeg file.";
                isValid = false;
            }
        }

        return isValid;
    }


    function validateStep2() {
        let isValid = true;

        // Clear previous error messages
        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

        // City selection validation
        const city = document.getElementById('city').value;
        if (!city) {
            document.getElementById('cityError').textContent = "Please select a city.";
            isValid = false;
        }

        // Shop address validation
        const shopAddress = document.getElementById('shop_address').value;
        if (!shopAddress) {
            document.getElementById('shopAddressError').textContent = "Shop address is required.";
            isValid = false;
        }

        // Latitude and Longitude validation
        const latitude = document.getElementById('latitude').value;
        const longitude = document.getElementById('longitude').value;

        const latRegex = /^-?\d+(\.\d+)?$/; // Regular expression for numeric format
        const lonRegex = /^-?\d+(\.\d+)?$/;

        if (!latRegex.test(latitude)) {
            document.getElementById('latitudeError').textContent = "Invalid latitude format.";
            isValid = false;
        }

        if (!lonRegex.test(longitude)) {
            document.getElementById('longitudeError').textContent = "Invalid longitude format.";
            isValid = false;
        }

        return isValid;
    }


    function validateStep3() {
        let isValid = true;

        // Clear previous error messages
        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

        // Shop Name validation
        const shopName = document.getElementById('shop_name').value;

        if (shopName.length < 4 || shopName.length > 20) {
            document.getElementById('shopNameError').textContent = "Shop name must be between 4 and 20 characters.";
            isValid = false;
        }

        const specialCharRegex = /[^a-zA-Z0-9 ]/; // No special characters
        if (specialCharRegex.test(shopName)) {
            document.getElementById('shopNameError').textContent = "Shop name cannot contain special characters.";
            isValid = false;
        }

        // Backend validation for shop name uniqueness can be added here (e.g., AJAX check)

        return isValid;
    }

    function validateStep4() {
        let isValid = true;

        // Hide all validation messages
        $('.validation-message').hide();

        // Category validation
        const category = document.getElementById('category').value;
        if (!category) {
            $('#category-error-message').text("Please choose a category.").show();
            isValid = false;
        }

        // Brief about the shop validation
        const briefHere = document.getElementById('brief_here').value.trim();
        if (!briefHere) {
            $('#brief-error-message').text("Please write a brief about your shop.").show();
            isValid = false;
        }

        return isValid;
    }

    function validateStep5() {
        let isValid = true;


        return isValid;
    }


    function validateStep6() {
        let isValid = true;

        // Hide all validation messages
        $('.validation-message').hide();

        // "I'm not a robot" reCAPTCHA checkbox validation
        const recaptchaChecked = document.getElementById('recaptcha').checked;
        if (!recaptchaChecked) {
            $('#recaptcha-error-message').text("Please confirm that you're not a robot.").show();
            isValid = false;
        }

        // Terms and Conditions checkbox validation
        const termsChecked = document.getElementById('terms').checked;
        if (!termsChecked) {
            $('#terms-error-message').text("You must agree to the terms and conditions.").show();
            isValid = false;
        }

        return isValid;
    }
</script>

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
                url: "{{ route('check.shop.name') }}", // Use route name here
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

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            toast: false, // Set to false to make it a normal modal rather than a toast
            position: 'center', // Position it in the center of the screen
            showConfirmButton: false,
            timer: 5000, // Keep it visible for 5 seconds
            customClass: {
                popup: 'big-toast' // Custom class for styling
            }
        });
    </script>
@endif


<script>
    // JavaScript to show the uploaded image
    document.getElementById('custom-file-upload').addEventListener('change', function(event) {
        const file = event.target.files[0]; // Get the selected file
        const viewer = document.getElementById('viewer'); // Get the image element
        if (file) {
            const reader = new FileReader(); // Create a FileReader to read the file
            reader.onload = function(e) {
                viewer.src = e.target.result; // Set the image source to the file's data URL
            };
            reader.readAsDataURL(file); // Read the file as a data URL
        }
    });
    $(document).ready(function() {
        // Image preview handler
        function previewImage(input, viewerId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $(`#${viewerId}`).attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Shop Logo Preview
        $('#shop-logo-upload').change(function() {
            previewImage(this, 'shopLogoViewer');
        });

        // Shop Banner Preview
        $('#shop-banner-upload').change(function() {
            previewImage(this, 'shopBannerViewer');
        });

        // Additional validation logic can be added here for logo and banner
    });
</script>

@if (session('success'))
    <script>
        Swal.fire({{ session('success') }});
    </script>
@endif
<!-- Google Maps API Script -->
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB3VTAihhs6gEYNld1LMwNkEiszH3TRcMQ&libraries=places&callback=initMap"
    async></script>

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

        // Update the map's center and zoom
        map.setCenter(place.geometry.location);
        map.setZoom(15);

        // Update the marker's position to the selected place
        marker.setPosition(place.geometry.location);

        // Set the latitude and longitude values
        const lat = place.geometry.location.lat();
        const lng = place.geometry.location.lng();
        document.getElementById("latitude").value = lat;
        document.getElementById("longitude").value = lng;
    }
</script>
