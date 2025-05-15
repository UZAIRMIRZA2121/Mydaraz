<div class="product-wrapper">
    <input class="search_input_store" type="text" name="search_category_value" value="{{request('search_category_value')}}" hidden>
    <input class="search_input_store search_input_name" type="text" name="search" value="{{request('search')}}" hidden>
    <input class="search_input_store search_input_data_from" type="text" name="data_from" value="{{ isset($request_data) ? $request_data['data_from'] : request('data_from')}}" hidden>

    @if (count($products) > 0)
        @foreach($products as $product)
            @include('theme-views.partials._products-list-single-card', ['product'=>$product])
        @endforeach
    @else
        <div class="text-center pt-5 w-100">
            <div class="text-center mb-5">
                <img loading="lazy" src="{{ theme_asset('assets/img/icons/product.svg') }}" alt="{{ translate('product') }}">
                <h5 class="my-3 text-muted">{{translate('products_Not_Found')}}!</h5>
                <p class="text-center text-muted">{{ translate('sorry_no_data_found_related_to_your_search') }}</p>
            </div>
        </div>
    @endif

</div>

@if(count($products) > 0 && ceil(($products->total() / $singlePageProductCount)) != 1)
    <ul class="search-pagination justify-content-end">
        <li>
            <label for="paginate_{{($page-1)}}" class="paginate_{{($page-1)}}">
                <i class="bi bi-chevron-left"></i>
            </label>
        </li>

        @php
            $totalPages = ceil($products->total() / $singlePageProductCount);
            $windowSize = 1;
        @endphp

        @for ($i = 1; $i <= min(2, $totalPages); $i++)
            <li>
                <label class="paginate_{{ $i }} {{ $page == $i ? 'active' : '' }}" for="paginate_{{ $i }}">{{ $i }}</label>
                <input class="paginate_btn paginate_btn_id{{ $i }} d-none" type="radio" name="page" id="paginate_{{ $i }}" value="{{ $i }}" {{ $page == $i ? 'checked' : '' }}>
            </li>
        @endfor

        @if ($totalPages > 3 && $page > 2)
            <li class="d-flex align-items-center disabled user-select-none">
                <span class="px-2">...</span>
            </li>
        @endif

        @for ($i = max(3, $page - $windowSize); $i <= min($totalPages - 2, $page + $windowSize); $i++)
            <li>
                <label class="paginate_{{ $i }} {{ $page == $i ? 'active' : '' }}" for="paginate_{{ $i }}">{{ $i }}</label>
                <input class="paginate_btn paginate_btn_id{{ $i }} d-none" type="radio" name="page" id="paginate_{{ $i }}" value="{{ $i }}" {{ $page == $i ? 'checked' : '' }}>
            </li>
        @endfor

        @if ($page < $totalPages - 2)
            <li class="d-flex align-items-center disabled user-select-none">
                <span class="px-2">...</span>
            </li>
        @endif

        @for ($i = max($totalPages - 1, 1); $i <= $totalPages; $i++)
            @if ($i > 2)
            <li>
                <label class="paginate_{{ $i }} {{ $page == $i ? 'active' : '' }}" for="paginate_{{ $i }}">{{ $i }}</label>
                <input class="paginate_btn paginate_btn_id{{ $i }} d-none" type="radio" name="page" id="paginate_{{ $i }}" value="{{ $i }}" {{ $page == $i ? 'checked' : '' }}>
            </li>
            @endif
        @endfor

        <li>
            <label for="paginate_{{($page+1)}}" class="paginate_{{($page+1)}}">
                <i class="bi bi-chevron-right"></i>
            </label>
        </li>
    </ul>

    <script>
        "use strict";

        @for ($i = 1; $i <= ceil(($products->total() / $singlePageProductCount)); $i++)
        $('.paginate_'+{{$i}}).on('click', function (){
            inputTypeNumberClick('{{$i}}');
        })
        @endfor

        @if ($page != ceil(($products->total() / $singlePageProductCount)))
        $('.paginate_'+{{$page}}).on('click', function (){
            inputTypeNumberClick('{{$page}}');
        })
        @endif

        @if ($page != 1)
        $('.paginate_'+{{$page-1}}).on('click', function (){
            inputTypeNumberClick('{{$page-1}}');
        })
        @endif

    </script>
@endif

