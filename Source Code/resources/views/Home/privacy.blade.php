@extends('Home.index_layout.layout')



@section('content')

    <link href=”http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.css” rel=”stylesheet”>
    <script src=”http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.js”></script>
    <header class="rt-site-header home-four rt-fixed-top ">
        <div class="main-header rt-sticky">
            <nav class="navbar">
                <div class="rt-container">
                    <a href="{{route('home')}}" class="brand-logo"><img
                            src={{asset($account_info !=NULL ? $account_info->application_logo:'themes/default_home/images/logo/logo.png')}} alt=""
                            width="175px"></a>
                    <a href="{{route('home')}}" class="sticky-logo"><img
                            src={{asset($account_info !=NULL ? $account_info->application_logo:'themes/default_home/images/logo/logo.png')}} alt=""
                            width="175px"></a>
                    <div class="ml-auto d-flex align-items-center">
                        <div class="main-menu">
                            <ul>
                                <li><a href="{{route('home')}}">Home</a>

                                </li>
                                <li>
                                    <a href="{{route('home')}}#how">
                                        How It Works?
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('home')}}#service">
                                        Service
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('store_pricing')}}">
                                        Pricing
                                    </a>
                                </li>

                                <li>
                                    <a href="{{route('privacy')}}">
                                        Privacy Policy
                                    </a>
                                </li>


                                <li>
                                    <a href="{{route('store.login')}}">
                                        Login
                                    </a>
                                </li>

                                <li class="current-menu-item">
                                    <a href="{{route('store_register')}}">

                                        Register
                                    </a>
                                </li>
                            </ul>
                        </div><!-- end main menu -->


                        <div class="rt-nav-tolls d-flex align-items-center">


                            <div class="mobile-menu">
                                <div class="menu-click">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </nav>
        </div><!-- /.bootom-header -->
    </header>

    <br>
    <br>
    <br>
    <br>
    <div class="inner-content container">
        <br>
        <br>
        <br>
        <h3> Privacy Policy</h3>
        <br>
        <br>
        <br>
        <p class="container" id="summernote">

            {!!$privacy!!}

        </p>

    </div><!-- /.inner-content -->


    <script>
        $('.summernote').summernote({
            airMode: true
        });
    </script>


@endsection
