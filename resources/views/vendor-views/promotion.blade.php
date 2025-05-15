@extends('layouts.back-end.app-seller')
@section('title', translate('order_List'))

@push('css_or_js')
    <link href="{{ dynamicAsset(path: 'public/assets/back-end/vendor/datatables/dataTables.bootstrap4.min.css') }}"
        rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
            <h2 class="h1 mb-0">
                <img src="{{ dynamicAsset(path: 'assets/back-end/img/all-orders.png') }}" class="mb-1 mr-1" alt="">
                <span class="page-header-title">

                </span>
                {{ translate('Promotion Types') }}

            </h2>

        </div>

        <div class="card">
            <div class="card-body">
                <div class="px-3 py-4 light-bg">
                    <div class="row g-2 align-items-center flex-grow-1">
                        <div class="col-md-4">
                            <h5 class="text-capitalize d-flex gap-1">
                                <h2>Please Select your Promotion Type</h2>

                                {{-- <span class="badge badge-soft-dark radius-50 fz-12">{{$orders->total()}}</span> --}}
                            </h5>
                        </div>

                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Promotion Type</th>
                                <th>Price (SR per day)</th>
                                <th>Maximum Allowed</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($promotions as $key => $promotion)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $promotion->promotion_type }}</td>
                                    <td>{{ $promotion->price }}</td>
                                    <td>{{ $promotion->maximum_allowed }}</td>
                                    <td>
                                        @if ($promotion->promotion_type == 'Push Notification')
                                            <a href="{{ route('vendor.push-notification.index', $promotion->id) }}"
                                                class="btn btn-warning">Select</a>
                                        @elseif($promotion->promotion_type == 'Main Section Banner' || $promotion->promotion_type == 'Footer Banner')
                                            <a href="{{ route('vendor.banner.index', $promotion->id) }}"
                                                class="btn btn-warning">Select</a>
                                        @elseif(
                                            $promotion->promotion_type == 'Promote product in category page' ||
                                                $promotion->promotion_type == 'Promote product in sub-category page' ||
                                                $promotion->promotion_type == 'Promote product in sub-sub-category page')
                                            <a href="{{ route('vendor.featured-product.index', $promotion->id) }}"
                                                class="btn btn-warning">Select</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p><strong>Note:</strong> Each vendor is allowed to launch a campaign for a maximum of 14 days.</p>



                </div>

                <div class="table-responsive mt-4">
                    <div class="d-flex justify-content-lg-end">
                        {{-- {{$orders->links()}} --}}
                    </div>
                </div>
                {{-- 
                @if (count($orders) == 0)
                    @include('layouts.back-end._empty-state',['text'=>'no_order_found'],['image'=>'default'])
                @endif --}}
            </div>
        </div>
    </div>


@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/vendor/datatables/dataTables.bootstrap4.min.js') }}">
    </script>
    <script>
        function previewImage(event) {
            const imageInput = event.target;
            const imagePreview = document.getElementById('imagePreview');

            if (imageInput.files && imageInput.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('d-none');
                };

                reader.readAsDataURL(imageInput.files[0]);
            }
        }
    </script>
@endpush
