
@extends('Home.home_layout.app')
@section('home_content')
    <div class="container-fluid pageloader">
        <div class="row h-100">
            <div class="col-12 align-self-start text-center">
            </div>
            <div class="col-12 align-self-center text-center">
                <div class="loader-logo">

                    <div class="loader-roller">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>

                </div>
            </div>
            <div class="col-12 align-self-end text-center">
                <p class="my-5">Please wait<br><small class="text-mute">{{$account_info != NULL ?$account_info->application_name:"Digital"}} menu is loading...</small></p>
            </div>
        </div>
    </div>
    <!-- Page laoder ends -->

    <!-- Fixed navbar -->
    <header class="header fixed-top">
        <nav class="navbar">

            <div>
                <a class="navbar-brand" href="#">

                    <h4 class="logo-text">
                        <span>{{$store_name}}</span>
                        <small>{{$description}}</small>
                    </h4>
                </a>
            </div>
            <div>


            </div>
        </nav>
    </header>
    <!-- Fixed navbar ends -->

    <!-- Begin page content -->
    <main class="flex-shrink-0 main-container pb-0">
        <!-- page content goes here -->
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                <div class="container mb-4">
                    <input type="search" id="Search" onkeyup="myFunction()" class="form-control border-0 shadow-light" placeholder="Search here...">
                </div>
                <div class="container mb-4">
                    <div class="swiper-container swiper-offers">
                        <div class="swiper-wrapper">

                            @foreach($sliders as $slider)
                                <div class="swiper-slide w-auto search">
                                    <div class="card w-250 position-relative overflow-hidden bg-dark text-white border-0">
                                        <div class="background opacity-60">
                                            <img src="{{asset($slider->photo_url)}}" alt="" s>
                                        </div>
                                        <div class="card-body text-center z-1 h-50"></div>
                                        <div class="card-footer border-0 z-1">
                                            <div class="media">
                                                <div class="media-body">

                                                    <h6 class="mb-1" style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">{{$slider->name}}</h6>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
                <div class="container">
                    <h6 class="page-subtitle search">RECOMMENDED ITEMS</h6>
                    <div class="row">

                        @foreach($recommended as $data)

                            <div class="col-6 col-md-3 search">
                                <div class="card border-0 shadow-light text-center mb-4">
                                    <div class="card-body position-relative">

                                        <div class="h-100px position-relative">
                                            <div class="background background-h-100">
                                                <img src="{{asset($data->image_url)}}" alt="">
                                            </div>
                                        </div>


                                        <h6 class="text-default" style="margin-top:10px;">{{ucfirst($data->name)}}</h6>
                                        <p class="small" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">{{ucfirst($data->description)}}<br></p>


                                        <div class="row">
                                            <div class="col text-left">
                                                <p class="text-success my-0">{{$account_info != NULL ?$account_info->currency_symbol:"₹"}} {{$data->price}}</p>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @foreach($categories as $category)
                    <div class="container">
                        <?php $count = 0 ?>
                        <h6 class="page-subtitle search">{{ucfirst($category->name)}} <small class="text-black-50">
                                @foreach($products as $product)
                                    @if($category->id == $product->category_id)
                                        <?php $count++ ?>
                                    @endif
                                @endforeach
                                {{$count}} ITEMS</small></h6>
                        @foreach($products as $product)
                            @if($category->id == $product->category_id)

                                <div class="card  border-0 shadow-light mb-4 search">
                                    <div class="card-body position-relative">
                                        <div class="row">
                                            <div class="col-auto w-100px pr-0 align-self-center">
                                                <div class="h-80 position-relative">
                                                    <div class="background background-h-100">
                                                        <img src="{{asset($product->image_url)}}" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <p class="mb-1"><a href="#" class="text-default">{{$product->name}}</a></p>
                                                <p class="mb-2 small"> <span class="text-mute">{{$product->description}} </span>
                                                    <span class="badge badge-success badge-pill">{{$category->name}} </span>

                                                </p>
                                                <div class="row">
                                                    <div class="col text-left">
                                                        <p class="text-success my-0">{{$account_info != NULL ?$account_info->currency_symbol:"₹"}}  {{$product->price}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endforeach

            </div>

        </div>

    </main>
    <!-- End of page content -->

    <!-- scroll to top button -->
    <button type="button" class="btn btn-default default-shadow scrollup bottom-right position-fixed btn-44"><span class="arrow_carrot-up"></span></button>
    <!-- scroll to top button ends-->

@endsection
