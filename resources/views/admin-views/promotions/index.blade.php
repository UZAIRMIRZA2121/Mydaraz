@php
    use Illuminate\Support\Str;
@endphp
@extends('layouts.back-end.app')
@section('title', translate('add_new_notification'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endpush
@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/push_notification.png') }}"
                    alt="">
                {{ translate('Promotion Management') }}
            </h2>
        </div>
        <div class="row gx-2 gx-lg-3">

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">

                    <div class="table-responsive datatable-custom">
                        <table
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
                            <thead class="thead-light thead-50 text-capitalize">
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
                                            <a href="#" class="btn btn-warning edit-btn"
                                                data-id="{{ $promotion->id }}" data-price="{{ $promotion->price }}"
                                                data-bs-toggle="modal" data-bs-target="#editPriceModal">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <table class="mt-4">
                            <tfoot>

                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Edit Price Modal -->
    <div class="modal fade" id="editPriceModal" tabindex="-1" aria-labelledby="editPriceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPriceModalLabel">Edit Price</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editPriceForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="promotion_id" name="promotion_id">

                        <div class="mb-3">
                            <label for="price" class="form-label">Price (SR per day)</label>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Price</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.edit-btn').on('click', function() {
                var promotionId = $(this).data('id');
                var price = $(this).data('price');

                console.log("Promotion ID: " + promotionId); // Debugging
                console.log("Price: " + price); // Debugging

                var updateUrl = "{{ route('admin.promotions.update', ['promotion' => ':id']) }}";
                updateUrl = updateUrl.replace(':id', promotionId);

                console.log("Form Action URL: " + updateUrl); // Debugging

                $('#editPriceForm').attr('action', updateUrl);
                $('#promotion_id').val(promotionId);
                $('#price').val(price);
            });
        });
    </script>
@endsection


@push('script')
@endpush
