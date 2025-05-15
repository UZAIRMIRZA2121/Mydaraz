@extends('layouts.back-end.app-seller')
@section('title', translate('order_List'))

@push('css_or_js')
    <link href="{{dynamicAsset(path: 'public/assets/back-end/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
            <h2 class="h1 mb-0">
                <img src="{{dynamicAsset(path: 'assets/back-end/img/all-orders.png')}}" class="mb-1 mr-1" alt="">
                <span class="page-header-title">
                
                </span>
                {{translate('push_notifications_setup')}}
            </h2>
         
        </div>
        <div class="container">
            <h2>Create Push Notification</h2>
        
            <form action="{{ route('vendor.push-notification.update', $notification->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required value="{{$notification->title}}">
                    </div>
        
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" class="form-control" id="imageInput" onchange="previewImage(event)" >
                    </div>
        
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" required>{{$notification->description}}</textarea>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="status" name="status" value="1" checked>
                            <label class="form-check-label" for="status">Active</label>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        @if($notification->image)
                        <img id="imagePreview" src="{{ asset('storage/notification/' . $notification->image) }}" alt="Selected Image" class="img-fluid {{$notification->image ? '':'d-none'}} " style="max-width: 100px; max-height: 140px; border: 1px solid #ccc; padding: 5px;">

                    @else
                        No Image
                    @endif

                    </div>
                  
        
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
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
