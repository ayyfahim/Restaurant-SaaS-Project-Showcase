@extends("restaurants.layouts.restaurantslayout")

@section("restaurantcontant")


    <div class="container-fluid">

        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">

                <div class="row">
                    <div class="col-6">
                        <h3 class="mb-0"> Table Reports</h3>
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
                        <th>Table Name</th>
                        <th>Table Number</th>
                        <th>Total Orders</th>
                        <th>Total Price</th>

                    </tr>
                    </thead>
                    <tbody>

                    @php $i=1 @endphp
                    @foreach($tables as $data)

                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$data->table_name}}</td>
                            <td>@if ($data->table_number) {{$data->table_number}} @else - @endif</td>
                            <td>{{$data->total_order_count($data->table_name)}} </td>
                            <td>
                                {{$data->total_order_sum($data->table_name)}}
                            </td>
{{--                            <td>--}}
{{--                                {{$data->created_at->diffForHumans()}}--}}
{{--                            </td>--}}
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>

    </div>


@endsection
