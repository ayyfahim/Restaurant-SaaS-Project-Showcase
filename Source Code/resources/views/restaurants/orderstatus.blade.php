@extends("restaurants.layouts.restaurantslayout")

@section("restaurantcontant")



    <div class="container-fluid">




            <div class="row">

                <div class="col-md-4">
                    <!-- Members list group card -->
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header" style="color: #fff; background-color: #FFA101">
                            <!-- Title -->
                            <h5 class="h3 mb-0 text-white">New Order</h5>
                        </div>
                        <!-- Card body -->
                        <div class="card-body">
                            <!-- List group -->
                            <ul class="list-group list-group-flush list my--3">
                                @foreach($neworder as $new)
                                <li class="list-group-item px-0">
                                    <div class="row">
                                        <div class="col-auto">
                                            <!-- Avatar -->
                                            Order No: <b>{{ $new->order_unique_id }}</b>
                                        </div>

                                        <div class="col ml--2">

                                            --
                                        </div>

                                        <div class="col-auto">
                                            Table No:  <b>{{ $new->table_no }}</b>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <!-- Members list group card -->
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header" style="color: #fff; background-color: #2EC114">
                            <!-- Title -->
                            <h5 class="h3 mb-0 text-white">Processing</h5>
                        </div>
                        <!-- Card body -->
                        <div class="card-body">
                            <!-- List group -->
                            <ul class="list-group list-group-flush list my--3">
                                @foreach($orders as $data)
                                    <li class="list-group-item px-0">
                                        <div class="row">
                                            <div class="col-auto">
                                                <!-- Avatar -->
                                                Order No: <b>{{ $data->order_unique_id }}</b>
                                            </div>

                                            <div class="col ml--2">

                                                --
                                            </div>

                                            <div class="col-auto">
                                                Table No:  <b>{{ $data->table_no }}</b>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <!-- Members list group card -->
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header" style="background-color: #69519E">
                            <!-- Title -->
                            <h5 class="h3 mb-0 text-white">Ready</h5>
                        </div>
                        <!-- Card body -->
                        <div class="card-body">
                            <!-- List group -->
                            <ul class="list-group list-group-flush list my--3">
                                @foreach($ready as $read)
                                    <li class="list-group-item px-0">
                                        <div class="row">
                                            <div class="col-auto">
                                                <!-- Avatar -->
                                                Order No: <b>{{ $read->order_unique_id }}</b>
                                            </div>

                                            <div class="col ml--2">

                                                --
                                            </div>

                                            <div class="col-auto">
                                                Table No:  <b>{{ $read->table_no }}</b>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>



            </div>


    </div>



    <script language="javascript">
        setTimeout(function(){
            window.location.reload(1);
        }, 10000);
    </script>


@endsection
