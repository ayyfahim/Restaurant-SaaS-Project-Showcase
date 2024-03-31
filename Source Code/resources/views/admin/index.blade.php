@extends("admin.adminlayout")

@section("admin_content")


    <div class="container-fluid">



        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-gradient-primary border-0">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0 text-white">{{ __('chef.stores') }}</h5>
                                <span class="h2 font-weight-bold mb-0 text-white">{{$store_count}}</span>
                                <div class="progress progress-xs mt-3 mb-0">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="30"
                                         aria-valuemin="0" aria-valuemax="100" style="width: 30%;"></div>
                                </div>
                            </div>

                        </div>
                        <p class="mt-3 mb-0 text-sm">
                            <a href="#!" class="text-nowrap text-white font-weight-600">See details</a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-gradient-info border-0">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0 text-white">{{ __('chef.products') }}</h5>
                                <span class="h2 font-weight-bold mb-0 text-white">{{$product_count}}</span>
                                <div class="progress progress-xs mt-3 mb-0">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="50"
                                         aria-valuemin="0" aria-valuemax="100" style="width: 50%;"></div>
                                </div>
                            </div>

                        </div>
                        <p class="mt-3 mb-0 text-sm">
                            <a href="#!" class="text-nowrap text-white font-weight-600">See details</a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-gradient-danger border-0">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0 text-white">{{ __('chef.earnings') }}</h5>
                                <span class="h2 font-weight-bold mb-0 text-white">
                                    @include('layouts.render.currency',["amount"=>$earnings])
                                </span>
                                <div class="progress progress-xs mt-3 mb-0">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="80"
                                         aria-valuemin="0" aria-valuemax="100" style="width: 80%;"></div>
                                </div>
                            </div>

                        </div>
                        <p class="mt-3 mb-0 text-sm">
                            <a href="#!" class="text-nowrap text-white font-weight-600">See details</a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-gradient-default border-0">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0 text-white">{{ __('chef.pendingstores') }}</h5>
                                <span class="h2 font-weight-bold mb-0 text-white">{{$pending_stores }}</span>
                                <div class="progress progress-xs mt-3 mb-0">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="90"
                                         aria-valuemin="0" aria-valuemax="100" style="width: 90%;"></div>
                                </div>
                            </div>

                        </div>
                        <p class="mt-3 mb-0 text-sm">
                            <a href="#!" class="text-nowrap text-white font-weight-600">See details</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6">
                <!-- Members list group card -->
                <div class="">
                    <!-- Card header -->
                    <div class="card-header">
                        <!-- Title -->
                        <h5 class="h3 mb-0">New Registrations</h5>
                    </div>
                   <br>
                        <ul class="list-group list-group-flush list my--3" >

                            @foreach($new_stores as $data)


                                <div class="intro-y">
                                    <div class="box px-4 py-4 mb-3 flex items-center zoom-in">
                                        <div class="">
                                            {{-- <img alt="Store" src="{{asset($data->logo_url)}}" width="100px" height="100px" style="border-radius: 10px"> --}}
                                            <img alt="Store" src="{{ $data->logo_url !=NULL ? $data->logo_url : asset("assets/images/avatar/1.jpg") }}" width="100px" height="100px" style="border-radius: 10px">
                                        </div>
                                        <div class="ml-4 mr-auto">
                                            <div class="font-medium">{{$data->store_name}}</div>
                                            <div class="text-gray-600 text-xs mt-0.5">{{$data->email}} / {{$data->phone}}</div>
                                        </div>
                                        <div class="py-1 px-2 rounded-full text-xs bg-theme-9 text-white cursor-pointer font-medium">{{date('d-m-Y',strtotime($data->subscription_end_date))}}</div>
                                    </div>
                                </div>
                            @endforeach
                        </ul>

                </div>
            </div>

            <div class="col-xl-6">
                <!-- Members list group card -->
                <div class="">
                    <!-- Card header -->
                    <div class="card-header">
                        <!-- Title -->
                        <h5 class="h3 mb-0">Expired Stores</h5>
                    </div>
                    <br>
                    <ul class="list-group list-group-flush list my--3" >

                        @foreach($expired_stores as $data)


                            <div class="intro-y">
                                <div class="box px-4 py-4 mb-3 flex items-center zoom-in">
                                    <div class="">
                                        {{-- <img alt="Store" src="{{asset($data->logo_url)}}" width="100px" height="100px" style="border-radius: 10px"> --}}
                                        <img alt="Store" src="{{ $data->logo_url !=NULL ? Storage::disk('s3')->url($data->logo_url) : asset("assets/images/avatar/1.jpg") }}" width="100px" height="100px" style="border-radius: 10px">
                                    </div>
                                    <div class="ml-4 mr-auto">
                                        <div class="font-medium">{{$data->store_name}}</div>
                                        <div class="text-gray-600 text-xs mt-0.5">{{$data->email}} / {{$data->phone}}</div>
                                    </div>
                                    <div class="py-1 px-2 rounded-full text-xs bg-danger text-white cursor-pointer font-medium">{{date('d-m-Y',strtotime($data->subscription_end_date))}}</div>
                                </div>
                            </div>
                        @endforeach
                    </ul>

                </div>
            </div>









        </div>



    </div>

@endsection
