@extends("restaurants.layouts.restaurantslayout")

@section("restaurantcontant")


    <div class="container-fluid">

        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">

                <div class="row">
                    <div class="col-6">
                        <h3 class="mb-0"> Recent Customers</h3>
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
                        <th>Customer Name</th>
                        <th>Customer Phone</th>
                        <th>No of Orders</th>
                        <th>Recent Order </th>
                    </tr>
                    </thead>
                    <tbody>

                    @php $i=1 @endphp
                    @foreach($customers as $data)

                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$data->customer_name}}</td>
                            <td>{{$data->customer_phone}} </td>
                            <td>
                                {{$data->total($data->customer_phone)}}
                            </td>
                            <td> {{$data->created_at->diffForHumans()}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>


@endsection
