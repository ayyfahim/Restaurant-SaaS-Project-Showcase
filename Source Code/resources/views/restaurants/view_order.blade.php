@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')


    <div class="container-fluid">

        <div class="card">
            <div class="card-header border-0">
                <div class="row">
                    <div class="col-6">
                        <h3 class="mb-0">
                            <smal>Order Id:</smal>{{ $order->order_unique_id }}
                        </h3>
                    </div>
                    <div class="col-6 text-right">
                        <a href="javascript:void(0)" id="printButton" class="btn btn-sm btn-primary btn-round btn-icon"
                            data-toggle="tooltip" data-original-title="Print">
                            <span class="btn-inner--icon"><i class="fas fa-print"></i></span>
                            <span class="btn-inner--text">Print</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body" id="printThis">


                <div class="card-body">
                    <!-- Stack the columns on mobile by making one full-width and the other half-width -->
                    <div class="row row-example">
                        <div class="col-md-8">
                            <span>
                                <i class="text-blue">Customer Details:</i><br>
                                Customer Name: <b>{{ $order->customer_name }}</b><br>
                                Phone No: <b>{{ $order->customer_phone }}</b>

                            </span>
                            @if ($order->waiter_orders->count() > 0)
                                <span>
                                    <i class="text-blue">Ordered by waiter:</i><br>
                                    Waiter Name: <b>{{ $order->waiter_orders->first()->waiter->name }}</b><br>
                                    Phone No: <b>{{ $order->waiter_orders->first()->waiter->phone }}</b>

                                </span>
                            @endif
                        </div>
                        <div class="col-md-4"><span>
                                <i class="text-blue">Order Details:</i><br>
                                Order Id: <b>{{ $order->order_unique_id }}</b><br>
                                Placed at: <b>{{ $order->created_at }}</b><br>
                                Accepted at: <b>{{ $order->accepted_at ?? '-' }}</b><br>
                                Canceled at: <b>{{ $order->canceled_at ?? '-' }}</b><br>
                                Completed at: <b>{{ $order->completed_at ?? '-' }}</b><br>
                                Served at: <b>{{ $order->served_at ?? '-' }}</b><br>
                                Table No: <b>{{ $order->table_no ?? '-' }}</b>

                            </span></div>

                    </div>
                    <div class="col"><span>
                            <i class="text-blue">Customer Note:</i><br>
                            <b>{{ $order->comments }}</b><br>


                        </span></div>

                </div>



                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Item Price</th>
                                <th>Qty</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderDetails as $order_data)
                                @foreach ($order_data['order_details'] as $key => $data)
                                    <tr>
                                        <th scope="row">{{ $key + 1 }}</th>

                                        <td><b>{{ $data['name'] }}</b><br>


                                            @foreach ($data['order_details_extra_addon'] as $key => $exra)

                                                <span class="badge badge-primary">{{ $key + 1 }}</span>
                                                Name: <strong>{{ $exra['addon_name'] }} (
                                                    {{ $exra['addon_price'] }})</strong>
                                                x
                                                <strong> {{ $exra['addon_count'] }}</strong> =
                                                <strong>
                                                    {{ $account_info != null ? $account_info->currency_symbol : '₹' }} {{ $exra['addon_count'] * $exra['addon_price'] }}</strong>
                                                <br>
                                                <ol>
                                                    @foreach ($exra['order_details_extra_parent_addon'] as $extraNestedAddon)
                                                        <li>
                                                            Name: <strong>{{ $extraNestedAddon['addon_name'] }} (
                                                                {{ $extraNestedAddon['addon_price'] }})</strong>
                                                            x
                                                            <strong> {{ $extraNestedAddon['addon_count'] }}</strong> =
                                                            <strong>
                                                                {{ $account_info != null ? $account_info->currency_symbol : '₹' }} {{ $extraNestedAddon['addon_count'] * $extraNestedAddon['addon_price'] }}</strong>
                                                        </li>
                                                    @endforeach
                                                </ol>
                                            @endforeach


                                        </td>
                                        {{-- <td>{{ $data['price'] }}</td> --}}
                                        <td>
                                            {{ $account_info != null ? $account_info->currency_symbol : '₹' }}
                                            {{ $data['price'] }}
                                            <br>
                                            @foreach ($data['order_details_extra_addon'] as $key => $exra)
                                                +
                                                {{ $account_info != null ? $account_info->currency_symbol : '₹' }}
                                                {{ $exra['addon_count'] * $exra['addon_price'] }}
                                                <br>
                                                @foreach ($exra['order_details_extra_parent_addon'] as $extraNestedAddon)
                                                    +
                                                    {{ $account_info != null ? $account_info->currency_symbol : '₹' }} {{ $extraNestedAddon['addon_count'] * $extraNestedAddon['addon_price'] }}
                                                <br>
                                                @endforeach
                                                <br>
                                            @endforeach
                                        </td>
                                        <td>{{ $data['quantity'] }}</td>
                                        {{-- <td class="color-primary"> {{ $data['quantity'] * $data['price'] }}</td> --}}
                                        <td class="color-primary"> {{ $account_info != null ? $account_info->currency_symbol : '₹' }} {{ $order->sub_total }}</td>
                                    </tr>

                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <br>

                <div class="float-right">
                    <table class="table table-bordered table-striped bill-calc-table">

                        <tbody>
                            <tr>
                                <td class="text-left td-title">SubTotal</td>
                                <td class="td-data">@include('layouts.render.currency',["amount"=>$order->sub_total])</td>
                            </tr>

                            <tr>
                                <td class="text-left td-title">Service Charge</td>
                                <td class="td-data"> @include('layouts.render.currency',["amount"=>$order->store_charge])
                                </td>
                            </tr>


                            {{-- <tr>
                                <td class="text-left td-title">Tax</td>
                                <td class="td-data">@include('layouts.render.currency',["amount"=>$order->tax])</td>
                            </tr> --}}

                            <tr>
                                <td class="text-left td-title">Discount</td>
                                <td class="td-data">- (@include('layouts.render.currency',["amount"=>$order->discount]))
                                </td>
                            </tr>

                            <tr>
                                <td class="text-left td-title">Coupon</td>
                                <td class="td-data">- (@include('layouts.render.currency',["amount"=>$order->coupon]))
                                </td>
                            </tr>


                            <tr>
                                <td class="text-left td-title"><b>TOTAL</b></td>
                                <td class="td-data"> @include('layouts.render.currency',["amount"=>$order->total]) </td>
                            </tr>

                            <tr>
                                <td class="text-left td-title">Order Status</td>
                                <td class="td-data"> <span class="badge badge-pill badge-primary">
                                        {{ $order->status == 1 ? 'Order Placed' : null }}
                                        {{ $order->status == 2 ? 'Order Accepted' : null }}
                                        {{ $order->status == 5 ? 'Ready to Serve' : null }}
                                        {{ $order->status == 3 ? 'Order Rejected' : null }}
                                        {{ $order->status == 4 ? 'Order Completed' : null }}


                                    </span></td>
                            </tr>

                        </tbody>
                    </table>
                </div>






            </div>
        </div>


    </div>








@endsection
