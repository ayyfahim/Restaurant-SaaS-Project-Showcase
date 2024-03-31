@extends('Home.index_layout.layout')



@section('content')


    <header class="rt-site-header default-header rt-fixed-top white-menu ">
        <div class="main-header rt-sticky">
            <nav class="navbar">
                <div class="rt-container">
                    <a href="{{route('home')}}" class="brand-logo"><img
                            src={{asset($account_info !=NULL ? $account_info->application_logo:'themes/default_home/images/logo/logo.png')}} alt="" width="175px"></a>
                    <a href="{{route('home')}}" class="sticky-logo"><img
                            src={{asset($account_info !=NULL ? $account_info->application_logo:'themes/default_home/images/logo/logo.png')}} alt="" width="175px"></a>
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




    <div class="rt-overlay"></div><!-- ./ rt overlay -->





    <div class="rt-breadcump rt-breadcump-height default-breadcump">
        <div class="rt-page-bg rtbgprefix-cover" style="background-image: url({{asset('themes/default/images/all-img/bredcump-1.png')}});"></div>
        <!-- /.rt-page-bg -->
        <div class="rt-container">
            <div class="row rt-breadcump-height align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="inner-content">
                        <h3> Choose the best plan for your business</h3>

                    </div><!-- /.inner-content -->
                </div><!-- /.col-12 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div><!-- /.rt-bredcump -->


    <!--
        !=========== price  Area start =========!
     -->


    <div class="price-area">
        <div class="rt-container">

            <div class="row">

                @foreach($subscription as $data)

                <div class="col-lg-4 col-md-6 mx-auto rt-mb-30 wow fade-in-bottom">
                    <div class="rt-price-1 color_3">
                        <div class="price-hrader ">
                            <img src="{{asset('themes/default/images/all-icon/plane3.png')}}" alt="price image" draggable="false">
                            <span>{{$data->name}}</span>
                        </div><!-- /.price-hrader -->
                        <div class="price-body">
                            <div class="price-amount">
                                <span class="text-47a f-size-36 rt-strong d-inline-block rt-mb-5">{{$account_info != NULL ?$account_info->currency_symbol:"₹"}}{{preg_replace('~\.0+$~','',$data->price)}} </span> <span
                                    class="text-47a d-inline-block rt-mb-5">/</span>
                                <span class="d-block text-47a">{{$data->days}} Days</span>
                            </div><!-- /.price-amount -->
                            <ul class="rt-list">
                                <li>
                                    {{$data->description}}
                                </li>

                            </ul>
                        </div><!-- /.price-body -->
                        <div class="price-footer text-center">
                            <a href="{{route('store_register')}}" class="rt-btn rt-gradient4 pill text-uppercase rt-Bshadow-2">Start
                                <span class="check"><i class="flaticon-right-arrow"></i></span>
                            </a>
                        </div><!-- /.price-footer  -->
                    </div><!-- /.rt-price-1 -->
                </div>

                @endforeach







            </div><!-- /.row -->
        </div><!-- /.rt-container -->
    </div>
    <div class="rt-spacer-93 rt-spacer-xs-50"></div><!-- /.rt-spacer-93 -->












    <section class="rt-site-footer bg_footer2 home-five title_bar_footer rtbgprefix-cover" style="background-image: url(assets/images/all-img/footer-bg4.png);">
        <div class="footer-inner-content">
            <div class="footer-callto">
                <div class="rt-container">
                    <div class="row">
                        <div class="col-lg-4 rt-mb-md-20">
                            <div class="media">
                                <img src="{{asset('themes/default/images/all-icon/footer-icon-1.png')}}" class="rt-mr-20" alt="post_image">
                                <div class="media-body text-white">
                                    <span class="d-block rt-mb-8 rt-light3 ">Give us a Call</span>
                                    <p class="rt-mb-0 f-size-18 rt-strong">
                                        {{$account_info !=NULL ? $account_info->contact_no:'987654321'}}
                                    </p>
                                </div>
                            </div>
                        </div><!-- /.col-lg-4 -->
                        <div class="col-lg-4 rt-mb-md-20">
                            <div class="media">
                                <img src="{{asset('themes/default/images/all-icon/footer-icon-2.png')}}" class="rt-mr-20" alt="post_image">
                                <div class="media-body text-white">
                                    <span class="d-block rt-mb-8 rt-light3 ">Send us a Message</span>
                                    <p class="rt-mb-0 f-size-18 rt-strong">
                                        {{$account_info !=NULL ? $account_info->application_email:'a@b.com'}}
                                    </p>

                                </div>
                            </div>
                        </div><!-- /.col-lg-4 -->
                        <div class="col-lg-4">
                            <div class="media">
                                <img src="{{asset('themes/default/images/all-icon/footer-icon-3.png')}}" class="rt-mr-20" alt="post_image">
                                <div class="media-body text-white">
                                    <span class="d-block rt-mb-8 rt-light3 ">Visit our Location</span>
                                    <p class="rt-mb-0 f-size-18 rt-strong">
                                        {{$account_info !=NULL ? $account_info->Address:'AAA BBBB CCCC'}}
                                    </p>
                                </div>


                            </div>
                        </div><!-- /.col-lg-4 -->
                    </div><!-- /.row -->
                </div><!-- /.container -->
            </div><!-- /.footer-callto -->

            <div class="footer-bottom" style="height: 10px;">
                <div class="rt-container">
                    <div class="row align-items-center">
                        <div class="col-xl-12text-center text-xl-left">
                            Crafted with <i class="fa fa-heart text-danger"></i>
                            by {{$account_info !=NULL ? $account_info->application_name:'CHEF MENU'}}.
                        </div><!-- /.col-lg-4 -->

                    </div><!-- /.row -->
                </div><!-- /.container -->
            </div><!-- /.footer-bottom -->
        </div><!-- /.footer-inner-content -->
    </section>







@endsection
