@if (count($bannerTypeMainBanner) > 0)
    <section class="pb-4 rtl">
        <div class="">
            <div>
                <style>
                    /* Set image width and height */
                    .carousel-item img {
                        width: 100%;
                        height: auto;
                        min-height: 450px;
                        /* Fixed height for large screens */
                        object-fit: cover;
                    }

                    .desktop-view {
                        display: block;
                    }

                    .mobile-view {
                        display: none;
                    }

                    /* Responsively manage height for smaller screens */
                    @media (max-width: 767px) {
                        .carousel-item img {
                            height: auto;
                            /* Adjust height for smaller screens */
                            min-height: unset !important;
                        }

                        .mobile-view {
                            display: block;
                        }

                        .desktop-view {
                            display: none;
                        }
                    }

                    /* Carousel inner style */
                    .carousel-inner {
                        display: flex;
                    }

                    #imageCarousel {
                        background-color: #000000;
                    }
                </style>
                <style>
                    .carousel-btn {
                        position: absolute;
                        top: 50%;
                        transform: translateY(-50%);
                        background-color: transparent;
                        border: none;
                        color: white;
                        font-size: 2rem;
                        z-index: 10;
                        padding: 0.5rem 1rem;
                        cursor: pointer;

                        border-radius: 50%;
                        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
                        transition: color 0.3s ease, background-color 0.3s ease;
                        background-color: #000000
                    }
                
                    .carousel-btn:hover {
                        color: #ddd; /* slight hover effect */
                    }
                
                    .prev-btn {
                        left: 10px;
                    }
                
                    .next-btn {
                        right: 10px;
                    }
                </style>
                

                <!-- Bootstrap Carousel Wrapper -->
                <div id="imageCarousel" class="carousel slide desktop-view" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @php
                            $totalSlides = ceil(count($bannerTypeMainBanner) / 4); // Calculate total slides needed
                        @endphp

                        @foreach ($bannerTypeMainBanner->chunk(4) as $chunkIndex => $bannerChunk)
                            <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                                <div class="row">
                                    @foreach ($bannerChunk as $banner)
                                        <div class="col-3">
                                            <a href="{{ $banner['url'] }}" class="d-block" target="_blank">
                                                <img src="{{ getStorageImages(path: $banner->photo_full_url, type: 'banner') }}"
                                                    alt="Banner Image" class="img-fluid">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- Carousel Controls -->
               <!-- Left Arrow Button -->
<button type="button" class="carousel-btn prev-btn" data-bs-target="#imageCarousel" data-bs-slide="prev">
    &#8592;
</button>

<!-- Right Arrow Button -->
<button type="button" class="carousel-btn next-btn" data-bs-target="#imageCarousel" data-bs-slide="next">
    &#8594;
</button>


                </div>
                <div id="imageCarousel" class="carousel slide mobile-view" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @php
                            $totalSlides = ceil(count($bannerTypeMainBanner) / 4); // Calculate total slides needed
                        @endphp

                        @foreach ($bannerTypeMainBanner->chunk(2) as $chunkIndex => $bannerChunk)
                            <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                                <div class="row">
                                    @foreach ($bannerChunk as $banner)
                                        <div class="col-6">
                                            <a href="{{ $banner['url'] }}" class="d-block" target="_blank">
                                                <img src="{{ getStorageImages(path: $banner->photo_full_url, type: 'banner') }}"
                                                    alt="Banner Image" class="img-fluid">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
               <!-- Left Arrow Button -->
               <button type="button" class="carousel-btn prev-btn" data-bs-target="#imageCarousel" data-bs-slide="prev">
                &#8592;
            </button>
            
            <!-- Right Arrow Button -->
            <button type="button" class="carousel-btn next-btn" data-bs-target="#imageCarousel" data-bs-slide="next">
                &#8594;
            </button>
            

                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS (Ensure it's included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Manual Carousel Initialization (Optional but helpful) -->
    <script>
        $(document).ready(function () {
            // Auto-start carousel every 5 seconds
            $('#imageCarousel').carousel({
                interval: 5000,
                ride: 'carousel'
            });
    
            // Optional manual control (already handled by buttons via data attributes)
            $('.prev-btn').click(function () {
                $('#imageCarousel').carousel('prev');
            });
    
            $('.next-btn').click(function () {
                $('#imageCarousel').carousel('next');
            });
        });
    </script>
    
@endif
