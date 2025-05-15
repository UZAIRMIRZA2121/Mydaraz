@extends('layouts.back-end.app')
@section('title', translate('order_List'))

@section('content')
    <div class="content container-fluid">

        <div>
            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                <h2 class="h1 mb-0">
                    <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/all-orders.png') }}" class="mb-1 mr-1"
                        alt="">

                    {{ translate('Tryotto') }}
                </h2>
                {{-- <span class="badge badge-soft-dark radius-50 fz-14">{{$orders->total()}}</span> --}}
            </div>
            <div class="card mb-2 remove-card-shadow">
                <div class="card-body">
                    <div class="row flex-between align-items-center g-2 mb-3">
                        <div class="col-sm-6">
                            <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                                <img src="http://127.0.0.1:8000/assets/back-end/img/business_analytics.png"
                                    alt="">Business analytics
                            </h4>
                        </div>
                        <div class="col-sm-6 d-flex justify-content-sm-end">
                            <select class="custom-select w-auto" name="statistics_type" id="statistics_type">
                                <option value="overall">
                                    Overall statistics
                                </option>
                                <option value="today">
                                    Todays Statistics
                                </option>
                                <option value="this_month">
                                    This Months Statistics
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-2" id="order_stats">
                        <div class="col-sm-6 col-lg-3">
                            <a class="business-analytics card" href="http://127.0.0.1:8000/admin/orders/list/all">
                                <h5 class="business-analytics__subtitle">Total order</h5>
                                <h2 class="business-analytics__title"> {{ $orders->count() }}</h2>
                                <img src="http://127.0.0.1:8000/assets/back-end/img/all-orders.png" width="30"
                                    height="30" class="business-analytics__img" alt="">
                            </a>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <a class="business-analytics get-view-by-onclick card"
                                href="http://127.0.0.1:8000/admin/vendors/list">
                                <h5 class="business-analytics__subtitle">Total Amount</h5>
                                <h2 class="business-analytics__title">
                                    {{ $orders->sum(fn($order) => $order->shipping_cost - $order->shipping_commission) }}
                                </h2>
                                <img src="http://127.0.0.1:8000/assets/back-end/img/total-stores.png"
                                    class="business-analytics__img" alt="">
                            </a>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <a class="business-analytics card">
                                <h5 class="business-analytics__subtitle">Total Recieved</h5>
                                <h2 class="business-analytics__title">
                                    {{ $paid_orders->sum(fn($paid_orders) => $paid_orders->shipping_cost - $paid_orders->shipping_commission) }}
                                </h2>
                                <img src="http://127.0.0.1:8000/assets/back-end/img/total-product.png"
                                    class="business-analytics__img" alt="">
                            </a>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <a class="business-analytics card" href="http://127.0.0.1:8000/admin/customer/list">
                                <h5 class="business-analytics__subtitle">Total Pending</h5>
                                <h2 class="business-analytics__title">
                                    {{ $unpaid_orders->sum(fn($unpaid_orders) => $unpaid_orders->shipping_cost - $unpaid_orders->shipping_commission) }}
                                </h2>
                                <img src="http://127.0.0.1:8000/assets/back-end/img/total-customer.png"
                                    class="business-analytics__img" alt="">
                            </a>
                        </div>
                    </div>
                    <div class="row g-2" id="order_stats">
                        <div class="col-sm-6 col-lg-3">
                            <a class="business-analytics card" href="http://127.0.0.1:8000/admin/orders/list/all">
                                <h5 class="business-analytics__subtitle">Total Paid to tryotto</h5>
                                <h2 class="business-analytics__title"> {{ $tryotto_total_paid_amount }}</h2>
                                <img src="http://127.0.0.1:8000/assets/back-end/img/all-orders.png" width="30"
                                    height="30" class="business-analytics__img" alt="">
                            </a>
                        </div>
                        {{-- <div class="col-sm-6 col-lg-3">
                            <a class="business-analytics get-view-by-onclick card"
                                href="http://127.0.0.1:8000/admin/vendors/list">
                                <h5 class="business-analytics__subtitle">Total Amount</h5> 
                                 <h2 class="business-analytics__title">
                                    {{ $orders->sum(fn($order) => $order->shipping_cost - $order->shipping_commission) }}
                                </h2>
                                <img src="http://127.0.0.1:8000/assets/back-end/img/total-stores.png"
                                    class="business-analytics__img" alt="">
                            </a>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <a class="business-analytics card">
                                <h5 class="business-analytics__subtitle">Total Recieved</h5>
                                <h2 class="business-analytics__title">
                                    {{ $paid_orders->sum(fn($paid_orders) => $paid_orders->shipping_cost - $paid_orders->shipping_commission) }}
                                </h2>
                                <img src="http://127.0.0.1:8000/assets/back-end/img/total-product.png"
                                    class="business-analytics__img" alt="">
                            </a>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <a class="business-analytics card" href="http://127.0.0.1:8000/admin/customer/list">
                                <h5 class="business-analytics__subtitle">Total Pending</h5>
                                <h2 class="business-analytics__title">
                                    {{ $unpaid_orders->sum(fn($unpaid_orders) => $unpaid_orders->shipping_cost - $unpaid_orders->shipping_commission) }}
                                </h2>
                                <img src="http://127.0.0.1:8000/assets/back-end/img/total-customer.png"
                                    class="business-analytics__img" alt="">
                            </a>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <div class="px-3 py-4 light-bg">
                        <div class="row g-2 align-items-center flex-grow-1">
                            <div class="col-md-4">
                                <h5 class="text-capitalize d-flex gap-1">
                                    {{ translate('Tryotto ') }}
                                    {{-- <span class="badge badge-soft-dark radius-50 fz-12">{{$orders->total()}}</span> --}}
                                </h5>
                            </div>
                            {{-- <div class="col-md-8 d-flex gap-3 flex-wrap flex-sm-nowrap justify-content-md-end">
                                <form action="" method="GET">
                                    <div class="input-group input-group-custom input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                            placeholder="{{translate('search_by_Order_ID')}}" aria-label="Search by Order ID" value="{{ $searchValue }}">
                                        <button type="submit" class="btn btn--primary input-group-text">{{translate('search')}}</button>
                                    </div>
                                </form>
                                <div class="dropdown">
                                    <a type="button" class="btn btn-outline--primary text-nowrap" href="{{ route('admin.orders.export-excel', ['delivery_man_id' => request('delivery_man_id'), 'status' => $status, 'from' => $from, 'to' => $to, 'filter' => $filter, 'searchValue' => $searchValue,'seller_id'=>$vendorId,'customer_id'=>$customerId, 'date_type'=>$dateType]) }}">
                                        <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/excel.png')}}" class="excel" alt="">
                                        <span class="ps-2">{{ translate('export') }}</span>
                                    </a>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <div class="table-responsive datatable-custom">
                        <table
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
                            <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{ translate('SL') }}</th>
                                    <th>{{ translate('order_ID') }}</th>
                                    <th class="text-capitalize">{{ translate('order_date') }}</th>
                                    <th class="text-capitalize">{{ translate('customer_info') }}</th>
                                    <th>{{ translate('store') }}</th>
                                    <th class="text-capitalize">{{ translate('total_amount') }}</th>
                                    <th class="text-capitalize">{{ translate('Commission') }}</th>
                                    <th class="text-capitalize">{{ translate('Shipping cost') }}</th>
                                    <th class="text-capitalize">{{ translate('Shipping Commission') }}</th>
                                    <th class="text-capitalize">{{ translate('Tryotto Amount') }}</th>
                                    <th class="text-capitalize">{{ translate('Shipping Services') }}</th>
                                    <th class="text-capitalize">{{ translate('Shipping Services Id') }}</th>
                                    <th class="text-capitalize">{{ translate('tax') }}</th>


                                    <th class="text-capitalize">{{ translate('payment_method') }} </th>

                                    <th class="text-center">{{ translate('action') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($orders as $key => $order)

                                    <tr class="status-{{ $order['order_status'] }} class-all">
                                        <td class="">
                                            {{-- {{$orders->firstItem()+$key}} --}}
                                        </td>

                                        <td>
                                            <a class="title-color"
                                                href="{{ route('admin.orders.details', ['id' => $order['id']]) }}">{{ $order['id'] }}
                                                {!! $order->order_type == 'POS' ? '<span class="text--primary">(POS)</span>' : '' !!}</a>
                                        </td>
                                        <td>
                                            <div>{{ date('d M Y', strtotime($order['created_at'])) }},</div>
                                            <div>{{ date('h:i A', strtotime($order['created_at'])) }}</div>
                                        </td>
                                        <td>
                                            @if ($order->is_guest)
                                                <strong class="title-name">{{ translate('guest_customer') }}</strong>
                                            @elseif($order->customer_id == 0)
                                                <strong class="title-name">{{ translate('walking_customer') }}</strong>
                                            @else
                                                @if ($order->customer)
                                                    <a class="text-body text-capitalize"
                                                        href="{{ route('admin.orders.details', ['id' => $order['id']]) }}">
                                                        <strong
                                                            class="title-name">{{ $order->customer['f_name'] . ' ' . $order->customer['l_name'] }}</strong>
                                                    </a>
                                                    @if ($order->customer['phone'])
                                                        <a class="d-block title-color"
                                                            href="tel:{{ $order->customer['phone'] }}">{{ $order->customer['phone'] }}</a>
                                                    @else
                                                        <a class="d-block title-color"
                                                            href="mailto:{{ $order->customer['email'] }}">{{ $order->customer['email'] }}</a>
                                                    @endif
                                                @else
                                                    <label class="badge badge-danger fz-12">
                                                        {{ translate('customer_not_found') }}
                                                    </label>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($order->seller_id) && isset($order->seller_is))
                                                <a href="{{ $order->seller_is == 'seller' && $order->seller?->shop ? route('admin.vendors.view', ['id' => $order->seller->shop->id]) : 'javascript:' }}"
                                                    class="store-name font-weight-medium">
                                                    @if ($order->seller_is == 'seller')
                                                        {{ isset($order->seller?->shop) ? $order->seller?->shop?->name : translate('Store_not_found') }}
                                                    @elseif($order->seller_is == 'admin')
                                                        {{ translate('in_House') }}
                                                    @endif
                                                </a>
                                            @else
                                                {{ translate('Store_not_found') }}
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                @php($orderTotalPriceSummary = \App\Utils\OrderManager::getOrderTotalPriceSummary(order: $order))
                                                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $orderTotalPriceSummary['totalAmount']), currencyCode: getCurrencyCode()) }}
                                            </div>

                                            @if ($order->payment_status == 'paid')
                                                <span class="badge badge-soft-success">{{ translate('paid') }}</span>
                                            @else
                                                <span class="badge badge-soft-danger">{{ translate('unpaid') }}</span>
                                            @endif
                                        </td>

                                        <td class="">
                                            {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order->admin_commission), currencyCode: getCurrencyCode()) }}
                                        </td>
                                        <td class="">
                                            {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order->shipping_cost), currencyCode: getCurrencyCode()) }}
                                        </td>
                                        <td class="">
                                            $ {{ $order->shipping_commission }}
                                        </td>
                                        <td class="">
                                            {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order->shipping_cost - $order->shipping_commission), currencyCode: getCurrencyCode()) }}
                                        </td>


                                        <td class="">
                                            {{ $order->service_name }}
                                        </td>
                                        <td class="">
                                            {{ $order->option_id }}
                                        </td>

                                        <td class="">
                                            {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order->details->sum('tax')), currencyCode: getCurrencyCode()) }}
                                        </td>

                                        <td class="text-capitalize">
                                            {{ str_replace('_', ' ', $order['payment_method']) }}
                                        </td>

                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                @if ($order->payment_status == 'paid')
                                                <form action="{{ route('admin.tryotto.payout') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="order_id" value="{{ $order['id'] }}">
                                                    <input type="hidden" name="amount" value="{{ $order['shipping_cost'] - $order['shipping_commission'] }}">
                                                
                                                    <button type="submit" class="btn btn-outline-info square-btn btn-sm mr-1">
                                                        <i class="tio-credit-card"></i> Payout
                                                    </button>
                                                </form>
                                                
                                            @else
                                                <span class="badge badge-soft-danger">{{ translate('unpaid') }}</span>
                                            @endif
                                           

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
            <div class="js-nav-scroller hs-nav-scroller-horizontal d-none">
                <span class="hs-nav-scroller-arrow-prev d-none">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:">
                        <i class="tio-chevron-left"></i>
                    </a>
                </span>

                <span class="hs-nav-scroller-arrow-next d-none">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:">
                        <i class="tio-chevron-right"></i>
                    </a>
                </span>
                <ul class="nav nav-tabs page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">{{ translate('order_list') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>


    <span id="message-date-range-text" data-text="{{ translate('invalid_date_range') }}"></span>
    <span id="js-data-example-ajax-url" data-url="{{ route('admin.orders.customers') }}"></span>
@endsection

@push('script_2')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/admin/order.js') }}"></script>
@endpush
