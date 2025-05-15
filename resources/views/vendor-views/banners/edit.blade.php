@extends('layouts.back-end.app-seller')
@section('title', translate('order_List'))

@push('css_or_js')
    <link href="{{ dynamicAsset(path: 'public/assets/back-end/vendor/datatables/dataTables.bootstrap4.min.css') }}"
        rel="stylesheet">
@endpush


@section('content')
    <div class="content container-fluid">

        <div class="d-flex justify-content-between mb-3">
            <div>
                <h2 class="h1 mb-1 text-capitalize d-flex align-items-center gap-2">
                    <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/banner.png') }}" alt="">
                    {{ translate('banner_update_form') }}
                </h2>
            </div>
            <div>
                <a class="btn btn--primary text-white" href="{{ route('admin.banner.list') }}">
                    <i class="tio-chevron-left"></i> {{ translate('back') }}</a>
            </div>
        </div>

        <div class="row text-start">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('vendor.banner.update', ['id' => $banner->id]) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="row">
                                        <input type="hidden" id="id" name="id">
                                      
                                        <div class="form-group col-md-12">
                                            <label for="name" class="title-color text-capitalize">
                                                {{ translate('banner_type') }}
                                            </label>
                                            <input type="banner_type" name="banner_type" class="form-control"
                                                id="banner_type" disabled value="{{ $banner->banner_type }}">

                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="name" class="title-color text-capitalize">
                                                {{ translate('Current') }}
                                            </label>
                                            <br>
                                            <img class="ratio-4-2 rounded" width="300" alt=""
                                            src="{{ getStorageImages(path: $banner->photo_full_url , type: 'backend-banner') }}">
                                        </div>
                                 
                                 
                                    


                                        <!-- For Theme Fashion - New input Field - Start -->
                                        @if (theme_root_path() == 'theme_fashion')
                                            <div class="form-group mt-4 input-field-for-main-banner">
                                                <label for="button_text"
                                                    class="title-color text-capitalize">{{ translate('Button_Text') }}</label>
                                                <input type="text" name="button_text" class="form-control"
                                                    id="button_text" placeholder="{{ translate('Enter_button_text') }}">
                                            </div>
                                            <div class="form-group mt-4 mb-0 input-field-for-main-banner">
                                                <label for="background_color"
                                                    class="title-color text-capitalize">{{ translate('background_color') }}</label>
                                                <input type="color" name="background_color"
                                                    class="form-control form-control_color w-100" id="background_color"
                                                    value="#fee440">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex flex-column justify-content-center">
                                    <div>
                                        <div class="mx-auto text-center">
                                            <div class="uploadDnD">
                                                <div class="form-group inputDnD input_image"
                                                    data-title="{{ 'Drag and drop file or Browse file' }}">
                                                    <input type="file" name="photo"
                                                        class="form-control-file text--primary font-weight-bold"
                                                        id="banner"
                                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .webp |image/*">
                                                </div>
                                            </div>
                                        </div>
                                        <label for="name" class="title-color text-capitalize">
                                            {{ translate('banner_image') }}
                                        </label>
                                        <span class="title-color" id="theme_ratio">( {{ translate('ratio') }} 4:1
                                            )</span>
                                        <p>{{ translate('banner_Image_ratio_is_not_same_for_all_sections_in_website') }}.
                                            {{ translate('please_review_the_ratio_before_upload') }}</p>
                                        <!-- For Theme Fashion - New input Field - Start -->
                                        @if (theme_root_path() == 'theme_fashion')
                                            <div class="form-group mt-4 input-field-for-main-banner">
                                                <label for="title"
                                                    class="title-color text-capitalize">{{ translate('Title') }}</label>
                                                <input type="text" name="title" class="form-control" id="title"
                                                    placeholder="{{ translate('Enter_banner_title') }}">
                                            </div>
                                            <div class="form-group mb-0 input-field-for-main-banner">
                                                <label for="sub_title"
                                                    class="title-color text-capitalize">{{ translate('Sub_Title') }}</label>
                                                <input type="text" name="sub_title" class="form-control"
                                                    id="sub_title"
                                                    placeholder="{{ translate('Enter_banner_sub_title') }}">
                                            </div>
                                        @endif
                                        <!-- For Theme Fashion - New input Field - End -->

                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end flex-wrap gap-10">
                                    <button class="btn btn-secondary cancel px-4"
                                        type="reset">{{ translate('reset') }}</button>
                                    <button id="add" type="submit"
                                        class="btn btn--primary px-4">{{ translate('save') }}</button>
                                    <button id="update"
                                        class="btn btn--primary d--none text-white">{{ translate('update') }}</button>
                                </div>
                            </div>
                        </form>
                     
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/banner.js') }}"></script>
    <script>
        "use strict";
        $(document).on('ready', function () {
            getThemeWiseRatio();
        });
        let elementBannerTypeSelect = $('#banner_type_select');
        elementBannerTypeSelect.on('change',function(){
            getThemeWiseRatio();
        });
        function getThemeWiseRatio(){
            let bannerType = elementBannerTypeSelect.val();
            let theme = '{{ theme_root_path() }}';
            let themeRatio = {!! json_encode(THEME_RATIO) !!};
            let getRatio = themeRatio[theme][bannerType];
            $('#theme_ratio').text(getRatio);
        }
    </script>
@endpush
