@extends("waiters.layouts.waiterslayout")

@section('waiterscontent')



    <div class="container-fluid">
        <div class="tab-pane fade show active" id="home2" role="tabpanel" aria-labelledby="home-tab">
            <div class="row">


                @php $i=1 @endphp
                @foreach ($calls as $data)

                    <div class="col-md-3">

                        <div class="card">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <b>{{ $data->customer_name }}</b>
                                    </div>
                                    <div class="col-auto">
                                        @if ($data->is_completed == 0)
                                            @if ($data->type == 1)
                                                <a class="btn btn-outline-success btn-sm" type="button" data-toggle="modal"
                                                    data-target="#paymentModal-{{ $data->id }}">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            @else
                                                <a class="btn btn-outline-success btn-sm"
                                                    onclick="document.getElementById('complete-waiter-{{ $data->id }}').submit();"><i
                                                        class="fas fa-check"></i>
                                                </a>
                                            @endif
                                        @endif
                                        @if ($data->is_completed == 1)
                                            <a class="btn bg-gradient-success btn-sm text-white"><i
                                                    class="fas fa-check-double"></i>
                                            </a>
                                        @endif
                                        <form style="visibility: hidden" method="post"
                                            action="{{ route('waiter_admin.update_waiter_call_status', ['id' => $data->id]) }}"
                                            id="complete-waiter-{{ $data->id }}">
                                            @csrf
                                            @method('patch')
                                            <input style="visibility:hidden" name="is_completed" type="hidden" value="1">
                                        </form>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col">

                                        <span class="h6 surtitle text-muted">
                                            CUSTOMER PHONE
                                        </span>
                                        <div class="h4">{{ $data->customer_phone }}</div>

                                    </div>

                                    <div class="col">

                                        <span class="h6 surtitle text-muted">
                                            Table No .
                                        </span>
                                        <div class="h4">{{ $data->table_name }}</div>

                                    </div>

                                </div>


                                <div class="row">
                                    <div class="col">

                                        <span class="h6 surtitle text-muted">
                                            Comment .
                                        </span>
                                        <div class="h4">{{ $data->comment }}</div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>


                    @if ($data->type == 1)
                        <!-- Modal -->
                        <div class="modal fade paymentModal" id="paymentModal-{{ $data->id }}" data-backdrop="static"
                            data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Pay for order</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <h4>How would the customer like to pay?</h4>
                                        <p>Order ({{ $data->order->order_unique_id }}) Sum:
                                            <b
                                                style="font-weight: bold">{{ $data->order->total - $data->order->paid_amount }}</b>
                                        </p>
                                        <a class="btn btn-outline-success btn-sm"
                                            onclick="document.getElementById('complete-waiter-order-{{ $data->id }}').submit();">
                                            Pay for Order
                                        </a>
                                        <form style="visibility: hidden" method="post"
                                            action="{{ route('waiter_admin.update_waiter_call_status_order', ['id' => $data->id]) }}"
                                            id="complete-waiter-order-{{ $data->id }}">
                                            @csrf
                                            @method('patch')
                                            <input style="visibility:hidden" name="is_completed" type="hidden" value="1">
                                        </form>
                                        <h4 class="mt-1">Or</h4>
                                        <p>Total Table #{{ $data->order->table->id }} Order Sum:
                                            <b style="font-weight: bold">
                                                @php
                                                    $amount = 0;
                                                    
                                                    foreach ($data->order->table->unpaid_orders as $order) {
                                                        $unpaid_amount = $order->total - $order->paid_amount;
                                                        $amount = $amount + $unpaid_amount;
                                                    }
                                                @endphp
                                                {{ $amount }}
                                            </b>
                                        </p>
                                        <a class="btn btn-outline-success btn-sm"
                                            onclick="document.getElementById('complete-waiter-table-{{ $data->id }}').submit();">
                                            Pay for whole Table
                                        </a>
                                        <form style="visibility: hidden" method="post"
                                            action="{{ route('waiter_admin.update_waiter_call_status_table', ['id' => $data->id]) }}"
                                            id="complete-waiter-table-{{ $data->id }}">
                                            @csrf
                                            @method('patch')
                                            <input style="visibility:hidden" name="is_completed" type="hidden" value="1">
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

        </div>
    </div>










    <script language="javascript">
        var reloadTimer = setTimeout(function() {
            window.location.reload(1);
        }, 10000);

    </script>

    </div>

@endsection

@section('custom_scripts')
    <script>
        $('.paymentModal').on('show.bs.modal', function(event) {
            clearTimeout(reloadTimer);
        })
        $('.paymentModal').on('hidden.bs.modal', function(event) {
            var reloadTimer = setTimeout(function() {
                window.location.reload(1);
            }, 10000);
        })

    </script>
@endsection
