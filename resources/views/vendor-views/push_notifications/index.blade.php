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
        <div class="container card p-5 mb-3">
            <h2>Create Push Notification</h2>
        
            <form action="{{ route('vendor.push-notification.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
        
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" class="form-control" id="imageInput" onchange="previewImage(event)">
                    </div>
        
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <img id="imagePreview" src="#" alt="Selected Image" class="img-fluid d-none" style="max-width: 100px; max-height: 140px; border: 1px solid #ccc; padding: 5px;">

                    </div>
        
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="px-3 py-4 light-bg">
                    <div class="row g-2 align-items-center flex-grow-1">
                        <div class="col-md-4">
                            <h5 class="text-capitalize d-flex gap-1">
                                {{translate('push_notifications_setup')}}
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
                                <th>Title</th>
                                <th>Description</th>
                                <th>Notification count</th>
                                <th>Image</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notifications as $key => $notification)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $notification->title }}</td>
                                <td>{{ $notification->description }}</td>
                                <td>{{ $notification->notification_count }}</td>

                                <td>
                                    @if($notification->image)
                                        <img src="{{ asset('storage/notification/' . $notification->image) }}" width="80" height="50" alt="Notification Image">
                                    @else
                                        No Image
                                    @endif
                                </td>
                               
                                <td>
                                    @if($notification->status == 1)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Deactive</span>
                                @endif
                                
                                    {{-- <form action="http://127.0.0.1:8081/admin/notification/update-status" method="post" id="notification-status4-form" class="notification_status_form">
                                        <input type="hidden" name="_token" value="2s6NU8sSpXcO86fR7aDJBC5RZ9lXh5FSJWZeimn9" autocomplete="off">                                            <input type="hidden" name="id" value="4">
                                        <label class="switcher mx-auto">
                                            <input type="checkbox" class="switcher_input toggle-switch-message" id="notification-status4" name="status" value="1" checked="" data-modal-id="toggle-status-modal" data-toggle-id="notification-status4" data-on-image="notification-on.png" data-off-image="notification-off.png" data-on-title="Want to Turn ON Notification Status?" data-off-title="Want to Turn OFF Notification Status?" data-on-message="<p>If enabled customers will receive notifications on their devices</p>" data-off-message="<p>If disabled customers will not receive notifications on their devices</p>">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </form> --}}
                                </td>
                                <td>
                                    <a href="{{ route('vendor.push-notification.edit', $notification->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('vendor.push-notification.destroy', $notification->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>

                <div class="table-responsive mt-4">
                    <div class="d-flex justify-content-lg-end">
                        {{-- {{$orders->links()}} --}}
                    </div>
                </div>
{{-- 
                @if(count($orders)==0)
                    @include('layouts.back-end._empty-state',['text'=>'no_order_found'],['image'=>'default'])
                @endif --}}
            </div>
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
