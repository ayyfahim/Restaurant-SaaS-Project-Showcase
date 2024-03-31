@extends("restaurants.layouts.restaurantslayout")

@section("restaurantcontant")


    <div class="container-fluid">

        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">

                <div class="row">
                    <div class="col-6">
                        <h3 class="mb-0"> Subscription History</h3>

                    </div>


                </div>
            </div>
            <!-- Light table -->
            <div class="table-responsive">
                @if(session()->has("MSG"))
                    <div class="alert alert-{{session()->get("TYPE")}}">
                        <strong> <a>{{session()->get("MSG")}}</a></strong>
                    </div>
                @endif
                <table class="table align-items-center table-flush text-center">
                    <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Plan Name</th>
                        <th>Price</th>
                        <th>No of Days</th>
                        <th>Payment Status</th>
                        <th>Gateway</th>
                        <th>Payment Transactional Id</th>
                        <th>date</th>
                    </tr>
                    </thead>
                    <tbody>

                    @php $i=1 @endphp
                    @foreach($store_plan_history as $data)

                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$data->subscription_name}}</td>
                            <td>{{$data->subscription_price}} </td>
                            <td>
                                <span class="badge badge-danger">{{round($data->subscription_days)}} Days</span>
                            </td>
                            <td>
                                <span class="badge badge-{{$data->payment_status != "paid"?"danger":"success"}}">{{$data->payment_status}}</span>
                            </td>
                            <td>{{$data->gateway_name}}</td>
                            <td>
                                    <input value="{{$data->payment_transactional_id}}" type="text" name="name" class="form-control" required>
                            </td>
                            <td> {{date('d-m-Y H:i:s', strtotime($data->created_at))}}</td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>


@endsection
