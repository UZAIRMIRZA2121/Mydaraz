@extends('layouts.front-end.app')

@section('title', translate('Update_Info'))

@push('css_or_js')
    <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/front-end/plugin/intl-tel-input/css/intlTelInput.css') }}">
@endpush

@section('content')
    <div class="container py-4 py-lg-5 my-4 __inline-7">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 box-shadow max-w-500 mx-auto p-3">
                    <div class="card-body">
                        <div>
                            <div class="text-center">
                                <div class="py-3">
                                    <img src="{{theme_asset(path: 'public/assets/front-end/img/icons/otp-login-icon.svg') }}" alt="" width="50">
                                </div>
                                <div class="my-3">
                                    <p class="text-muted">
                                        {{ translate('just_one_step_away') }}!
                                        {{ translate('_this_will_help_make_your_profile_more_personalized') }}
                                    </p>
                                </div>
                            </div>
                            <form class="needs-validation_" id="sign-up-form"
                                  @if($updateType == 'otp')
                                    action="{{ route('customer.auth.login.update-info') }}"
                                  @elseif($updateType == 'social')
                                    action="{{ route('customer.auth.social-login-confirmation.update') }}"
                                  @endif

                                  method="post">
                                @csrf
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="user-name">
                                            {{ translate('Name') }}
                                        </label>
                                        <input class="form-control" type="text" name="name" id="user-name" required
                                               placeholder="{{ translate('Enter_your_name') }}"
                                               value="{{ request('fullName') ? base64_decode(request('fullName')) : '' }}">
                                    </div>

                                    @if($updateType == 'otp')
                                        <div class="form-group">
                                            <label for="user-email">
                                                {{ translate('Email') }}
                                            </label>
                                            <input class="form-control" type="text" name="email" id="user-email"
                                                   placeholder="{{ translate('Enter_your_email') }}">
                                        </div>
                                    @elseif($updateType == 'social')
                                        <div class="form-group">
                                            <label class="form-label font-semibold">{{ translate('phone_number') }}</label>
<!-- Phone Input Field -->
<input class="form-control text-align-direction"
       id="phone"
       type="tel"
       value="+966"
       placeholder="{{ translate('enter_phone_number') }}"
       required>
                                            <input type="hidden" class=" w-50" name="phone" readonly>
                                        </div>
                                    @endif
                                    <input type="hidden" name="identity" value="{{ $identity }}">

                                    @if($web_config['firebase_otp_verification'] && $web_config['firebase_otp_verification']['status'])
                                        <div id="recaptcha-container-verify-token" class="my-2"></div>
                                    @endif
                                </div>


                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn--primary">{{ translate('Update')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($updateType == 'social')
        @include(VIEW_FILE_NAMES['modal_for_social_media_user_view'])
    @endif
@endsection
<!-- Include intl-tel-input CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>

@push('script')
    <script src="{{ theme_asset(path: 'public/assets/front-end/plugin/intl-tel-input/js/intlTelInput.js') }}"></script>
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/country-picker-init.js') }}"></script>
    @if($user)
        <script>
            $(document).ready(function() {
                $('#social-media-user-modal').modal('show');
            })
        </script>
    @endif
<script>
    // Select phone input
    var input = document.querySelector("#phone");

  

    // Set the default value to "+966 "
    input.value = "+966 ";

    // Prevent user from removing "+966"
    input.addEventListener("input", function () {
        if (!input.value.startsWith("+966 ")) {
            input.value = "+966 ";
        }
        // Allow only numbers after +966
        input.value = "+966 " + input.value.slice(5).replace(/\D/g, "");
    });

    // Ensure only numbers are entered after "+966"
    input.addEventListener("keydown", function (e) {
        if (input.selectionStart < 5) {
            e.preventDefault(); // Prevent editing the "+966" part
        }
        // Allow only numbers (0-9), Backspace, Delete, Arrow keys
        if (!/[0-9]/.test(e.key) && !["Backspace", "Delete", "ArrowLeft", "ArrowRight"].includes(e.key)) {
            e.preventDefault();
        }
    });
</script>
@endpush
