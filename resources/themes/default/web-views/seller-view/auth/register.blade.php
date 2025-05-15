@extends('layouts.front-end.app')

@section('title', translate('vendor_Apply'))

@push('css_or_js')
    <link href="{{ theme_asset(path: 'public/assets/back-end/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ theme_asset(path: 'public/assets/back-end/css/croppie.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet"
        href="{{ theme_asset(path: 'public/assets/front-end/plugin/intl-tel-input/css/intlTelInput.css') }}">
@endpush


@section('content')
{{-- @if(session()->has('new_email'))
<p>The user's email is: {{ session('new_email') }}</p>
<p>The user's email is: {{ session('seller_id') }}</p>
@endif
 --}}





    <form id="seller-registration" action="#" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="py-5">
            <div class="first-el">
                <section>
                    <div class="container">
                        <div class="create-an-account p-3 p-sm-4">
                            <img src="{{ theme_asset('assets/img/media/form-bg.png') }}" alt=""
                                class="create-an-accout-bg-img">
                            <div class="row align-items-center">
                                @include('web-views.seller-view.auth.partial.header')
                                <div class="col-lg-8">
                                    <div class="text-center">
                                        <h4>
                                            {{ $vendorRegistrationHeader?->sub_title ?? translate('create_your_own_store') . '. ' . translate('already_have_store') . '?' }}
                                        </h4>
                                        <a class="fw-bold btn btn-primary" id="modelOpen" data-toggle="modal"
                                            data-target="#signInModal" href="{{ route('vendor.auth.login') }}">Get
                                            Started</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="create-an-account bg-white rounded border my-5 p-4">
                            <div class="row">

                            </div>
                            <div class="row">
                                <div class="col-12 d-none">
                                    <div class="bg-white p-3 p-sm-4 rounded">
                                        <h4 class="mb-4 text text-capitalize">{{ translate('create_an_account') }}</h4>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-4">
                                                    <label for="email">
                                                        {{ translate('email') }}
                                                        <span class="text-danger">*</span>
                                                        <span class="text-danger fs-12 mail-error"></span>
                                                    </label>
                                                    <input class="form-control rounded" type="email" id="email"
                                                        name="email"
                                                        placeholder="{{ translate('Ex: example@gmail.com') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-4">
                                                    <label for="tel">
                                                        {{ translate('phone') }}
                                                        <span class="text-danger">*</span>
                                                        <span class="text-danger fs-12 phone-error"></span>
                                                    </label>
                                                    <div>
                                                        <input
                                                            class="form-control form-control-user phone-input-with-country-picker"
                                                            type="tel"
                                                            placeholder="{{ translate('enter_phone_number') }}" required>
                                                        <input type="hidden" class="country-picker-phone-number w-50"
                                                            name="phone" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-4">
                                                    <label for="password">
                                                        {{ translate('password') }}
                                                        <span class="text-danger fs-12 password-error"></span>
                                                    </label>
                                                    <div class="password-toggle rtl">
                                                        <input class="form-control text-align-direction password-check"
                                                            name="password" type="password" id="password"
                                                            placeholder="{{ translate('minimum_8_characters_long') }}"
                                                            required>
                                                        <label class="password-toggle-btn">
                                                            <input class="custom-control-input" type="checkbox"><i
                                                                class="tio-hidden password-toggle-indicator"></i><span
                                                                class="sr-only">{{ translate('show_password') }} </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-4">
                                                    <label for="password" class="text-capitalize">
                                                        {{ translate('confirm_password') }}
                                                        <span class="text-danger fs-12 confirm-password-error"></span>
                                                    </label>
                                                    <div class="password-toggle rtl">
                                                        <input class="form-control text-align-direction"
                                                            name="confirm_password" type="password" id="confirm_password"
                                                            placeholder="{{ translate('confirm_password') }}" required>
                                                        <label class="password-toggle-btn">
                                                            <input class="custom-control-input " type="checkbox"><i
                                                                class="tio-hidden password-toggle-indicator"></i><span
                                                                class="sr-only">{{ translate('show_password') }} </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-flex justify-content-end">
                                                    <button type="button"
                                                        class="btn btn--primary proceed-to-next-btn text-capitalize">{{ translate('proceed_to_next') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                @include('web-views.seller-view.auth.partial.why-with-us')
                @include('web-views.seller-view.auth.partial.business-process')
                @include('web-views.seller-view.auth.partial.download-app')
                @include('web-views.seller-view.auth.partial.faq')
            </div>
            @include('web-views.seller-view.auth.partial.vendor-information-form')
        </div>
    </form>
    <form id="vendorForm">
      

        <!-- Modal 1: Email & Social -->
        <div class="modal fade" id="signInModal" tabindex="-1" aria-labelledby="signInModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="signInModalLabel">Sign In to Continue</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control rounded" id="email_add"
                                    placeholder="Enter your email" required>
                                <span class="email_validation text-danger"></span>
                            </div>
                            <button type="button" class="btn btn-dark w-100 rounded-pill"
                                id="toModal2">Continue</button>
                            <p class="text-center mt-3">Trouble Signing In?</p>
                            <div class="text-center">
                                <a href="{{ route('vendor.auth.login.google') }}"
                                    class="btn btn-social w-100 border rounded-pill my-1">
                                    <img width="25" src="https://img.icons8.com/color/48/google-logo.png"
                                        alt="google-logo" />
                                    Continue with Google
                                </a>
                                <a href="{{ route('vendor.auth.login.facebook') }}"
                                    class="btn btn-social w-100 border rounded-pill my-1">
                                    <img width="25" src="https://img.icons8.com/fluency/48/facebook-new.png"
                                        alt="facebook-new" />
                                    Continue with Facebook
                                </a>
                                <button type="button" class="btn btn-social w-100 border rounded-pill my-1">
                                    <img width="25" src="https://img.icons8.com/ios-filled/50/mac-os.png"
                                        alt="mac-os" />
                                    Continue with Apple
                                </button>
                            </div>
                            <p class="text-center mt-3">Albazar may send you communications. Change preferences in your
                                account settings. We'll never post without your permission.</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <script>
            document.getElementById('googleLogin').addEventListener('click', function(e) {
                e.preventDefault(); // Prevent the default anchor behavior

                // Close the first modal
                $('#signInModal').modal('hide');

                // Open the Saudi phone number modal
                $('#saudiPhoneModal').modal('show');
            });
        </script>

    
        <!-- Modal 2: Create Account -->
        <div class="modal fade" id="createAccountModal" tabindex="-1" aria-labelledby="createAccountModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <span id="backToSignIn"
                            class="flex items-center text-gray-600 hover:text-gray-800 transition-all cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" style="width: 20px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                            <span class="ml-2 text-sm font-medium"></span>
                        </span>
                        <h4 class="modal-title" id="createAccountModalLabel">Create your account</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="text-center">

                                <img src="{{ theme_asset('assets/front-end/img/icons/user-vector.svg') }}"
                                    alt="Vendor Avatar" class="mb-3" style="width: 50px;">
                                <p>Vendor@albazar.com</p>
                                <p>or use a different email address</p>
                                <p class="message" id="message"></p>
                            </div>



                            <div class="form-group">
                                <label for="name">Your Name</label>
                                <input type="text" class="form-control rounded" id="name_vendor"
                                    placeholder="Enter your name">
                                <span id="name_error" class="text-danger" style="display: none;">Please enter your
                                    name</span>

                            </div>

                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" class="form-control rounded" id="phone_vendor"
                                    placeholder="Enter your phone number">
                                <span id="phone_error" class="text-danger" style="display: none;">Please enter your phone
                                    number</span>

                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control rounded" id="password_vendor"
                                    placeholder="Enter your password">
                                <span id="password_error" class="text-danger" style="display: none;">Password is
                                    required</span>

                            </div>
                            <div class="form-group">
                                <label for="confirm-password">Confirm Password</label>
                                <input type="password" class="form-control rounded" id="confirm_password_vendor"
                                    placeholder="Confirm your password">
                                <span id="confirm_password_error" class="text-danger" style="display: none;">Passwords do
                                    not match</span>

                            </div>
                            <button type="button" class="btn btn-dark w-100 rounded-pill" id=""
                                onclick="validateForm()">Continue</button>
                            <p class="text-center mt-3">Albazar may send you communications. Change preferences in your
                                account settings. We'll never post without your permission.</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <!-- Modal 3: Account Verification -->
        <div class="modal fade" id="accountVerificationModal" tabindex="-1"
            aria-labelledby="accountVerificationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="accountVerificationModalLabel">Account Verification</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- OTP Verification Modal -->
                    <div class="modal-body">
                        <p>Confirm your phone number</p>
                        <p>We sent a 6-digit code to +966548005243</p>
                        <form id="otp-form">
                            <div class="form-group">
                                <input type="text" class="form-control rounded" id="otp"
                                    placeholder="Enter the 6-digit code">
                            </div>
                            <div id="otp-error-message" class="alert alert-danger mt-2 d-none" role="alert">
                                Invalid OTP. Please try again.
                            </div>
                            <button type="button" class="btn btn-dark w-100 rounded-pill proceed-to-next d-none"
                                id="proceed-to-next">Next</button>
                            <button type="button" class="btn btn-dark w-100 rounded-pill verify-otp"
                                id="verify-otp">Continue</button>
                            <p class="text-center mt-3" id="resend-section">
                                Didn't get a code? <a href="javascript:void(0);" id="resend-otp">Resend</a>
                            </p>
                        </form>
                    </div>

                    <!-- Include jQuery -->
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script>
                        $(document).ready(function() {
                            // Verify OTP
                            $('.verify-otp').on('click', function() {
                                var otp = $('#otp').val(); // Get the entered OTP

                                if (otp === "") {
                                    alert("Please enter the OTP.");
                                    return;
                                }
                                $.ajax({
                                    url: '/verify-otp', // Route to verify OTP
                                    method: 'POST',
                                    data: {
                                        _token: '{{ csrf_token() }}', // CSRF token for security
                                        otp: otp
                                    },
                                    success: function(response) {
                                        if (response.status === 'success') {
                                            // Remove the 'verify-otp' class and add the 'proceed-to-next' class
                                            $('#verify-otp').addClass('d-none');

                                            // Trigger the "Next" button
                                            $('#proceed-to-next').trigger(
                                                'click'); // Trigger the button programmatically


                                        } else {
                                            // Show the error message below the OTP input
                                            $('#otp-error-message').removeClass('d-none');
                                            // Optionally, reset the OTP input field
                                            $('#otp-input').val('');
                                        }
                                    },
                                    error: function() {
                                        alert("An error occurred while verifying the OTP.");
                                    }
                                });
                            });
                            // Verify OTP
                            $('#resend-otp').on('click', function() {
                                // Resend OTP
                                $.ajax({
                                    url: '/custom-resend-otp', // Match the route defined in web.php
                                    type: 'POST',
                                    data: {
                                        _token: '{{ csrf_token() }}', // CSRF token for security

                                    },
                                    success: function(response) {
                                        // Handle success
                                        alert(response.message);
                                    },
                                    error: function(xhr, status, error) {
                                        // Handle error
                                        console.error(xhr.responseText);
                                    }
                                });
                            });



                        });
                    </script>

                </div>
            </div>
        </div>
        </div>
    </form>
    <div class="modal fade registration-success-modal" tabindex="-1" aria-labelledby="toggle-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                    <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i
                            class="tio-clear"></i></button>
                </div>
                <div class="modal-body px-4 px-sm-5 pt-0">
                    <div class="d-flex flex-column align-items-center text-center gap-2 mb-2">
                        <img src="{{ theme_asset(path: 'public/assets/front-end/img/congratulations.png') }}"
                            width="70" class="mb-3 mb-20" alt="">
                        <h5 class="modal-title">{{ translate('congratulations') }}</h5>
                        <div class="text-center">
                            {{ translate('your_registration_is_successful') . ', ' . translate('please-wait_for_admin_approval') . '.' . translate(' you_will_get_a_mail_soon') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <span id="get-confirm-and-cancel-button-text" data-sure="{{ translate('are_you_sure') . '?' }}"
        data-message="{{ translate('want_to_apply_as_a_vendor') . '?' }}" data-confirm="{{ translate('yes') }}"
        data-cancel="{{ translate('no') }}"></span>
    <span id="proceed-to-next-validation-message" data-mail-error="{{ translate('please_enter_your_email') . '.' }}"
        data-phone-error="{{ translate('please_enter_your_phone_number') . '.' }}"
        data-valid-mail="{{ translate('please_enter_a_valid_email_address') . '.' }}"
        data-enter-password="{{ translate('please_enter_your_password') . '.' }}"
        data-enter-confirm-password="{{ translate('please_enter_your_confirm_password') . '.' }}"
        data-password-not-match="{{ translate('passwords_do_not_match') . '.' }}">
    </span>








    @if(session()->has('new_email'))
    <p>The user's email is: {{ session('new_email') }}</p>
    <p>The user's email is: {{ session('seller_id') }}</p>

    <!-- Script to open the modal if email is available in the session -->
    <script type="text/javascript">
        $(document).ready(function() {
            // Open the modal if the session has 'new_email'
            $('#saudiPhoneModal').modal('show');
        });
    </script>

    <!-- Form Submission Handling with AJAX -->
    <script>
        $(document).ready(function() {
            $("#saudiPhoneForm").on("submit", function(e) {
                e.preventDefault(); // Prevent the default form submission
    
                // Get the phone number value
                const phoneNumber = "+966" + $("#saudiPhone").val();
    
                // Validate phone number manually (if required, though `pattern` handles this)
                if (!/^5\d{8}$/.test($("#saudiPhone").val())) {
                    alert("Invalid phone number format. It must start with 5 and be 9 digits long.");
                    return;
                }
    
                // Send AJAX request
                $.ajax({
                    url: "/send-otp", // Your backend endpoint for handling OTP
                    method: "POST",
                    data: {
                        phone: phoneNumber,
                        _token: "{{ csrf_token() }}" // For Laravel CSRF protection
                    },
                    success: function(response) {
                        if (response.status === "success" || response.status === "exists") {
                            // Display appropriate success message
                            alert(response.status === "success" ? 
                                "OTP sent successfully!" : 
                                "Phone number exists. OTP regenerated!");
    
                            // Close the Saudi Phone Modal
                            $('#saudiPhoneModal').modal('hide');
    
                            // Populate the Account Verification Modal with phone number details
                            $('#accountVerificationModal').find('p').eq(1).text(
                                `We sent a 6-digit code to +966${$('#saudiPhone').val()}`
                            );
    
                            // Open the Account Verification Modal
                            $('#accountVerificationModal').modal('show');
                        } else {
                            alert("An error occurred. Please try again.");
                        }
                    },
                    error: function() {
                        alert("Failed to send OTP. Please check your network and try again.");
                    }
                });
            });
        });
    </script>

    <!-- Modal 2: Saudi Phone Number -->
    <div class="modal fade" id="saudiPhoneModal" tabindex="-1" aria-labelledby="saudiPhoneModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="saudiPhoneModalLabel">Enter Your Saudi Phone Number</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="saudiPhoneForm">
                        <div class="form-group">
                            <label for="saudiPhone">Saudi Phone Number</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">+966</span>
                                </div>
                                <input type="tel" class="form-control rounded" id="saudiPhone"
                                       placeholder="5XXXXXXXX" required pattern="5\d{8}"
                                       title="Saudi phone number must start with 5 and be 9 digits long">
                            </div>
                            <small class="form-text text-muted">
                                Enter your 9-digit Saudi phone number starting with 5.
                            </small>
                        </div>
                        <button type="submit" class="btn btn-dark w-100 rounded-pill">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@else
    <p>Email not available.</p>
@endif

@endsection

@push('script')





    @if ($web_config['recaptcha']['status'] == '1')
        <script type="text/javascript">
            "use strict";
            var onloadCallback = function() {
                let reg_id = grecaptcha.render('recaptcha-element-vendor-register', {
                    'sitekey': '{{ $web_config['recaptcha']['site_key'] }}'
                });
                $('#recaptcha-element-vendor-register').attr('data-reg-id', reg_id);
            };
        </script>
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    @endif

    <script>
        $('.proceed-to-next').on('click', function(e) {
            e.preventDefault();
            $('.second-el').removeClass('d--none').fadeIn();
            $('.first-el').hide();
            $('.modal').modal('hide');
        });

        $('#vendor-apply-submit').on('click', function() {
            @if ($web_config['recaptcha']['status'] == '1')
                var response = grecaptcha.getResponse($('#recaptcha-element-vendor-register').attr('data-reg-id'));
                if (response.length === 0) {
                    toastr.error("{{ translate('please_check_the_recaptcha') }}");
                } else {
                    submitRegistration();
                }
            @else
                if ($('#default-recaptcha-id-vendor-register').val() != '') {
                    submitRegistration();
                } else {
                    toastr.error("{{ translate('please_check_the_recaptcha') }}");
                }
            @endif
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#toModal2").click(function() {
                var email = $("#email_add").val();
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (email === "") {
                    $(".email_validation").html("Email is Required");
                } else if (!emailRegex.test(email)) {
                    $(".email_validation").html("Please enter a valid email address");
                } else {
                    $(".email_validation").html("");
                    $("#signInModal").modal("hide");
                    //   $("#createAccountModal").modal("show"); 
                    $('#createAccountModal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
            });
            $("#backToSignIn").click(function() {
                $("#createAccountModal").modal("hide"); // Hide the second modal
                $("#signInModal").modal("show"); // Show the first modal
            });
        });
        $('#modelOpen').on('click', function() {
            $('#signInModal').modal({
                backdrop: 'static',
                keyboard: false
            });
        });

        function validateForm() {
            // Reset all error messages
            document.getElementById('name_error').style.display = 'none';
            document.getElementById('phone_error').style.display = 'none';
            document.getElementById('password_error').style.display = 'none';
            document.getElementById('confirm_password_error').style.display = 'none';

            var name = document.getElementById('name_vendor').value.trim();
            var phone = document.getElementById('phone_vendor').value.trim();
            var password = document.getElementById('password_vendor').value.trim();
            var confirmPassword = document.getElementById('confirm_password_vendor').value.trim();
            var valid = true;

            if (name === "") {
                document.getElementById('name_error').style.display = 'inline';
                valid = false;
            }
            if (phone === "") {
                document.getElementById('phone_error').style.display = 'inline';
                valid = false;
            }
            if (password === "") {
                document.getElementById('password_error').style.display = 'inline';
                valid = false;
            }
            if (password != confirmPassword) {
                document.getElementById('confirm_password_error').style.display = 'inline';
                valid = false;
            }
            // If form is valid, proceed to next modal
            if (valid) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var formData = {
                    email_add: $('#email_add').val(),
                    name_vendor: $('#name_vendor').val(),
                    phone_vendor: $('#phone_vendor').val(),
                    password_vendor: $('#password_vendor').val(),
                    confirm_password_vendor: $('#confirm_password_vendor').val()
                };

                $.ajax({
                    url: "{{ route('vendor_addCustom') }}",
                    type: 'POST',
                    data: formData,
                    dataType: 'json',

                    success: function(response) {
                        console.log(response);
                        // Check if the response message indicates success
                        if (response.status === 'success') {
                            // Show success message
                            $('#message').html('<div class="alert alert-success">' + response.message +
                                '</div>');

                            // Reset the form
                            $('#vendorForm')[0].reset();

                            // Hide the first modal and show the second modal after a delay
                            setTimeout(function() {
                                    $("#createAccountModal").modal("hide"); // Hide the first modal
                                    $('#accountVerificationModal').modal({
                                        backdrop: 'static',
                                        keyboard: false
                                    });
                                },
                                3000
                            ); // Wait for 3 seconds before hiding the first modal and showing the second modal

                        } else {
                            // If the message is not 'Seller added successfully!', show the error message
                            $('#message').html('<div class="alert alert-danger">' + response.message +
                                '</div>');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Show error message if any error occurs
                        var errorMessage = xhr.responseJSON.message || 'An error occurred!';
                        $('#message').html('<div class="alert alert-danger">' + errorMessage + '</div>');
                    }
                });


            }
        }
    </script>
    <script src="{{ theme_asset(path: 'public/assets/front-end/plugin/intl-tel-input/js/intlTelInput.js') }}"></script>
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/country-picker-init.js') }}"></script>
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/vendor-registration.js') }}"></script>
@endpush
