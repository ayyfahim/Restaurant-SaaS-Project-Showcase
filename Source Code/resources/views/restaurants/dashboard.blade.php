@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')
<style>
    .quick-stats .card {
        margin-bottom: 0;
        height: 100%;
    }

    .quick-stats .col-md-3 {
        margin-bottom: 30px;
    }
</style>
{{-- @include('restaurants.notification.expired_notification') --}}
{{-- @include('restaurants.notification.new_order_notification') --}}
{{-- @include('restaurants.notification.call_waiter_notification') --}}
<div class="container-fluid">

    <!-- Card stats -->
    <div class="container quick-stats">
        @if (!$bank_detail)
        <div class="alert alert-danger alert-dismissible fade show" style="margin-bottom: 15px;" role="alert">
            <span class="alert-icon"><i class="ni ni-like-2"></i></span>
            <span class="alert-text">Please add your bank details in the settings. <strong><a
                        href="{{ route('store_admin.add_bank_details') }}" class="text-white">Click
                        Here</a></strong></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        @endif
        <h3>Quick Stats</h3>
        <div class="row">
            <div class="col-md-3">
                <div class="card card-stats">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row p-0">
                            <div class="col-md-6 dashboard">
                                <h5 class="card-title text-uppercase mb-0">Total Orders</h5>
                                <span class="h1 font-weight-bold mb-0"><b>{{ $order_count }}</b></span>
                            </div>
                            <div class="col-md-6 dashboard-img">
                                <img class="img-fluid" src="{{ asset('images/icons/orders.png') }}" alt="Total Orders">
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="card card-stats">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row p-0">
                            <div class="col-md-6 dashboard">
                                <h5 class="card-title text-uppercase mb-0">Item Sold</h5>
                                <span class="h1 font-weight-bold mb-0"><b>{{ $item_sold }}</b></span>
                            </div>
                             <div class="col-md-6 dashboard-img">
                                <img class="img-fluid" src="{{ asset('images/icons/sold.png') }}" alt="Total Orders">
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-stats">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row p-0">
                            <div class="col-md-9 dashboard">
                                <h5 class="card-title text-uppercase mb-0">Total Earnings</h5>
                                <span class="h1 font-weight-bold mb-0"><b> @include('layouts.render.currency',["amount"=>$earnings])</b></span>
                            </div>
                             <div class="col-md-3 dashboard-img">
                                <img class="img-fluid" style="max-width:184%; margin-left:-24px" src="{{ asset('images/icons/earnings.png') }}" alt="icon">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-stats">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row p-0">
                            <div class="col-md-9 dashboard">
                                <h5 class="card-title text-uppercase mb-0">Plan end date</h5>
                                <span class="h1 font-weight-bold"><b>{{ date('d-m-Y', strtotime(auth()->user()->subscription_end_date)) }}</b></span>
                            </div>
                             <div class="col-md-3 dashboard-img">
                                <img class="img-fluid" style="max-width:184%; margin-left:-24px" src="{{ asset('images/icons/subscription.png') }}" alt="icon">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if (Auth::user()->is_takeway_order)
<div class="container">
    <div class="card">
        <div class="card-body" style="border-radius: 60px !important;">
            <div class="row">
                <div class="col">
                    <p id="item-to-copy" hidden> {{ route('view_store', [Auth::user()->view_id]) }}</p>
                    <p class="mb-0">For takeaway orders please share this link</p>
                </div>
                <div class="col">
                    <button class="btn btn-sm btn-info btn-primary-appetizr" onclick="copyToClipboard()">Copy</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif




<div class="container quick-stats">
    <div class="tab-pane fade show active" id="home2" role="tabpanel" aria-labelledby="home-tab">
        <h3>Pending Orders</h3>


        <div class="row">


            @php $i=1 @endphp
            @foreach ($orders as $pending)

            <div class="col-md-3">

                <div class="card">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <b>{{ $pending->customer_name }}</b>
                            </div>

                        </div>
                        <div>
                            <span class="h6 surtitle text-muted">
                                Order ID
                            </span>
                            <div class="h4">{{ $pending->order_unique_id }}</div>
                        </div>

                        <div class="row">
                            <div class="col">

                                <span class="h6 surtitle text-muted">
                                    Table No.
                                </span>
                                <div class="h4" style="color: rgb(211, 0, 0);">{{ $pending->table_no }}</div>

                            </div>
                            <div class="col">

                                <span class="h6 surtitle text-muted">
                                    Total
                                </span>
                                <div class="h4" style="color: rgb(211, 0, 0);">{{ $pending->total }}</div>

                            </div>
                        </div>
                        <hr>
                        <div class="row">

                            @if ($pending->status == 1)
                            <div class="col">
                                <a class="btn btn-outline-primary btn-outline-primary-appetizr btn-sm"
                                    onclick="document.getElementById('accept_order-{{ $pending->id }}').submit();">Accept</a>
                            </div>
                            <div class="col">
                                <a class="btn btn-outline-danger btn-sm"
                                    onclick="document.getElementById('reject_order{{ $pending->id }}').submit();">Reject</a>
                                @endif
                            </div>
                            @if ($pending->status == 2)
                            <a class="btn btn-outline-success btn-sm"
                                onclick="document.getElementById('complete_order{{ $pending->id }}').submit();">Complete
                                Order</a>
                            @endif

                            @if ($pending->status == 3)
                            <a class="btn btn-danger btn-sm text-white">Rejected</a>
                            @endif

                            @if ($pending->status == 4)
                            <a class="btn btn-success btn-sm text-white">Completed</a>
                            @endif


                            <form style="visibility: hidden" method="post"
                                action="{{ route('store_admin.update_order_status', ['id' => $pending->order_unique_id]) }}"
                                id="accept_order-{{ $pending->id }}">
                                @csrf
                                @method('patch')
                                <input style="visibility:hidden" name="status" type="hidden" value="2">
                            </form>
                            <form style="visibility: hidden" method="post"
                                action="{{ route('store_admin.update_order_status', ['id' => $pending->order_unique_id]) }}"
                                id="reject_order{{ $pending->id }}">
                                @csrf
                                @method('patch')
                                <input style="visibility:hidden" name="status" type="hidden" value="3">
                            </form>
                            <form style="visibility: hidden" method="post"
                                action="{{ route('store_admin.update_order_status', ['id' => $pending->order_unique_id]) }}"
                                id="complete_order{{ $pending->id }}">
                                @csrf
                                @method('patch')
                                <input style="visibility:hidden" name="status" type="hidden" value="4">
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>


</div>




{{-- <script language="javascript"> --}}
{{-- setTimeout(function(){ --}}
{{-- window.location.reload(1) --}}
{{-- }, 10000) --}}
{{-- </script> --}}

<script>
    function copyToClipboard() {
            const str = document.getElementById('item-to-copy').innerText;
            const el = document.createElement('textarea');
            el.value = str;
            el.setAttribute('readonly', '');
            el.style.position = 'absolute';
            el.style.left = '-9999px';
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
        }

</script>


@endsection
