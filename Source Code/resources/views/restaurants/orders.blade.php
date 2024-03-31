@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')

    {{-- @include('restaurants.notification.expired_notification') --}}
    {{-- @include('restaurants.notification.new_order_notification') --}}
    {{-- @include('restaurants.notification.call_waiter_notification') --}}

    <div class="container-fluid">




        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <div class="row">
                    <div class="col-6">
                        <h3 class="mb-0">All Orders

                            <span class="badge badge-md badge-circle badge-floating badge-gray border-white">
                                {{ $orders_count }}</span>
                        </h3>
                    </div>

                </div>
            </div>
            <!-- Light table -->
            <div class="table-responsive">
                <table class="table table-flush" id="datatable-basic">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Order ID</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Order Placed At</th>
                            <th>Table Number</th>
                            <th>Accept / Reject</th>
                            <th>View Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1 @endphp

                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $i++ }}</td>

                                <td>{{ $order->order_unique_id }}</td>

                                <td>
                                    @include('layouts.render.currency',["amount"=>$order->total])
                                </td>

                                <td>
                                    {{-- @php print_r($order->status)
                                    @endphp--}}
                                    @if ($order->status == 1)
                                        <span class="badge badge-order-placed">Order Placed</span>
                                    @endif

                                    @if ($order->status == 2)
                                        <span class="badge badge-warning">Processing</span>
                                    @endif
                                    @if ($order->status == 5)
                                        <span class="badge badge-default">Ready</span>
                                    @endif

                                    @if ($order->status == 3)
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif

                                    @if ($order->status == 4)
                                        <span class="badge badge-success">Order Completed</span>
                                    @endif


                                </td>
                                <td>
                                    {{ $order->created_at->diffForHumans() }}
                                </td>
                                <td> <span class="badge badge-gray"> {{ $order->table_no }}</span></td>
                                <td>
                                    @can('accept_reject_orders')
                                        @if ($order->status == 1)
                                            <a class="btn btn-success btn-sm text-white"
                                                onclick="document.getElementById('accept_order{{ $order->id }}').submit();">Accept
                                                Order</a>
                                            <a class="btn btn-danger btn-sm text-white"
                                                onclick="document.getElementById('reject_order{{ $order->id }}').submit();">Reject
                                                Order</a>
                                        @endif


                                        {{-- @if ($order->status == 2)--}}
                                            {{-- <a class="btn btn-outline-success btn-sm"
                                                --}} {{--
                                                onclick="document.getElementById('complete_order{{ $order->id }}').submit();">Complete--}}
                                                {{-- Order</a>--}}
                                            {{-- @endif
                                        --}}


                                        @if ($order->status == 2)
                                            <a class="btn btn-outline-success btn-sm"
                                                onclick="document.getElementById('ready_to_serve{{ $order->id }}').submit();">Ready
                                                to Serve</a>
                                        @endif
                                        @if ($order->status == 5)
                                            <a class="btn btn-outline-success btn-sm"
                                                onclick="document.getElementById('complete_order{{ $order->id }}').submit();">Complete</a>
                                        @endif


                                        @if ($order->status == 3)
                                            <a class="btn btn-danger btn-sm text-white">Rejected</a>
                                        @endif

                                        @if ($order->status == 4)
                                            <a class="btn btn-success btn-sm text-white">Completed</a>
                                        @endif


                                        <form style="visibility: hidden" method="post"
                                            action="{{ route('store_admin.update_order_status', ['id' => $order->order_unique_id]) }}"
                                            id="accept_order{{ $order->id }}">
                                            @csrf
                                            @method('patch')
                                            <input style="visibility:hidden" name="status" type="hidden" value="2">
                                        </form>
                                        <form style="visibility: hidden" method="post"
                                            action="{{ route('store_admin.update_order_status', ['id' => $order->order_unique_id]) }}"
                                            id="reject_order{{ $order->id }}">
                                            @csrf
                                            @method('patch')
                                            <input style="visibility:hidden" name="status" type="hidden" value="3">
                                        </form>
                                        <form style="visibility: hidden" method="post"
                                            action="{{ route('store_admin.update_order_status', ['id' => $order->order_unique_id]) }}"
                                            id="ready_to_serve{{ $order->id }}">
                                            @csrf
                                            @method('patch')
                                            <input style="visibility:hidden" name="status" type="hidden" value="5">
                                        </form>
                                        <form style="visibility: hidden" method="post"
                                            action="{{ route('store_admin.update_order_status', ['id' => $order->order_unique_id]) }}"
                                            id="complete_order{{ $order->id }}">
                                            @csrf
                                            @method('patch')
                                            <input style="visibility:hidden" name="status" type="hidden" value="4">
                                        </form>

                                    @endcan

                                </td>

                                <td style="text-align: center">
                                    @can('view_orders')
                                        <span>
                                            <a class="btn btn-default btn-sm"
                                                href="{{ route('store_admin.view_order', $order->id) }}">
                                                View Order
                                            </a>
                                        </span>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        setTimeout(function(){
            window.location.reload(1);
        },15000);
    </script>
@endsection
