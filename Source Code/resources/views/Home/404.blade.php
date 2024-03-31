@extends('Home.home_layout.app')

@section('home_content')

    <div class="osahan-restaurant">
        <div class="osahan-restaurant-detail">




            <div class="bg-light">

                <!-- slider -->
                <div class="trending-slider rounded overflow-hidden">
                    <div class="osahan-slider-item px-1 py-3">
                        <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                            <div class="list-card-image">
                                <a href="#">
                                    <img src="{{asset('images/404.png')}}" class="img-fluid item-img w-100" alt="image">
                                </a>
                            </div>

                        </div>
                    </div>


                </div>


                <div class="p-3">

                    <div class="pt-3" style="text-align: center;">
                        <h2 class="font-weight-bold">Restaurant Under Maintance</h2>
                        <h6> plz check your subscription details</h6>


                    </div>

                </div>



            </div>
        </div>







    </div>

@endsection
