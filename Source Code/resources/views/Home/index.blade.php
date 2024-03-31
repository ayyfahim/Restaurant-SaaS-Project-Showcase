@extends('Home.index_layout.layout')
@section('content')



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
                                <li><a href="{{route('home')}}">
                                        {{$selected_language->data['home'] ?? 'Home'}}
                                    </a>

                                </li>
                                <li>
                                    <a href="#how">
                                        {{$selected_language->data['how_it_works'] ?? 'How It Works?'}}
                                    </a>
                                </li>
                                <li>
                                    <a href="#service">
                                        {{$selected_language->data['service'] ?? 'Service'}}

                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('store_pricing')}}">
                                        {{$selected_language->data['pricing'] ?? 'Pricing'}}
                                    </a>
                                </li>

                                <li>
                                    <a href="{{route('privacy')}}">
                                        {{$selected_language->data['privacy_policy'] ?? 'Privacy Policy'}}

                                    </a>
                                </li>


                                <li>
                                    <a href="{{route('all.logins')}}">
                                        {{$selected_language->data['login'] ?? 'Login'}}
                                    </a>
                                </li>

                                <li class="current-menu-item">
                                    <a href="{{route('store_register')}}">
                                        {{$selected_language->data['register'] ?? 'Register'}}
                                    </a>
                                </li>

                                <li class="current-menu-item">
                                    <form method="post" action="{{route("change_language")}}">
                                        @csrf
                                        <select class="form-control" name="selected_language" data-width="fit"
                                                onchange="this.form.submit()">
                                            @foreach($languages as $data)
                                                @if(Session::get('selected_language')!=NULL)
                                                    <option
                                                        {{Session::get('selected_language') == $data->id ?"selected": null}} value="{{$data->id}}">{{$data->language_name}}</option>

                                                @endif
                                                @if(Session::get('selected_language')==NULL)
                                                    <option
                                                        {{$data->is_default == 1 ?"selected": null}} value="{{$data->id}}">{{$data->language_name}}</option>
                                                @endif

                                            @endforeach
                                        </select>
                                    </form>
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




    <section class="rt-banner-area home-four-banner">
        <div class="single-rt-banner rt-banner-height3 rtbgprefix-cover"
             style="background-image: url(themes/default/images/banner/banner-4.png);">
            <div class="rt-container">
                <div class="row  rt-banner-height3 align-items-center">
                    <div class="col-lg-6">
                        <div class="rt-banner-content home-four">
                            <h1 class="wow fade-in-bottom" data-wow-duration="1s" data-wow-delay="0.2s">
                                {{$selected_language->data['home_first_title'] ?? 'Re-open your restaurants'}}
                            </h1>
                            <p class="wow fade-in-top" data-wow-duration="1.4s" data-wow-delay="0.4s">
                                {!!$selected_language->data['home_first_sub_title'] ?? 'With a contactless <b>CHEF MENU</b>.<br>Make your restaurant a safe place to eat or grab-and-go by deploying a touch-free QR Code menu.'!!}
                            </p>
                            <div class="rt-button-group">
                                <a href="#" class="rt-btn rt-app-parimary wow fade-in-left" data-wow-duration="1.7s"
                                   data-wow-delay="0.6s">
                                    <div class="app-thumb">
                                        <img src="themes/default/images/banner/play-icon.png" alt="play_icon"
                                             draggable="false">
                                    </div><!-- /.app-thumb -->
                                    <div class="app-text">
                                        <span>Get it on</span>
                                        <span>google Play</span>
                                    </div><!-- /.app-text -->

                                </a>
                                {{--                                <a href="#" class="rt-btn rt-app-secondary wow fade-in-right" data-wow-duration="1.7s"--}}
                                {{--                                   data-wow-delay="0.6s">--}}
                                {{--                                    <div class="app-thumb">--}}
                                {{--                                        <img src="themes/default/images/banner/apple-icon.png" alt="play_icon" draggable="false">--}}
                                {{--                                    </div>--}}
                                {{--                                    <div class="app-text">--}}
                                {{--                                        <span>Get it on</span>--}}
                                {{--                                        <span>App Store</span>--}}
                                {{--                                    </div>--}}

                                {{--                                </a>--}}
                            </div>
                        </div><!-- end banner content -->
                    </div><!-- end column -->
                    <div class="col-lg-6 d-none d-lg-block">
                        <div class="mbl-dash-mockup text-center">
                            <img src="themes/default/images/banner/mbl-mockup-1.png" alt="mainmockup_image"
                                 draggable="false"
                                 class="main_mbl_mockup_img ">
                            <img src="themes/default/images/banner/mbl-dash-1.png" alt="submockup_image"
                                 draggable="false"
                                 class="sub_mockup_img1">
                            <img src="themes/default/images/banner/mbl-dash-2.png" alt="submockup_image"
                                 draggable="false"
                                 class="sub_mockup_img2 ">
                            <img src="themes/default/images/banner/mbl-dash-3.png" alt="submockup_image"
                                 draggable="false"
                                 class="sub_mockup_img3">
                            <img src="themes/default/images/banner/mbl-dash-4.png" alt="submockup_image"
                                 draggable="false"
                                 class="sub_mockup_img4">
                        </div><!-- /.mbl-dash-mockup -->
                    </div><!-- /.col-lg-6 -->
                </div><!-- end row -->
            </div><!-- end container -->
        </div><!-- end single rt banner -->
    </section>

    <!--
        !======== brands area start =========!
     -->






    <!--
       !=========== Servics Area Start =========!
    -->
    <div class="rt-spacer-100 rt-spacer-xs-80"></div><!-- /.rt-spacer-123 -->
    <section class="services-area" id="service">
        <div class="rt-container">
            <div class="row">
                <div class="col-xl-6 col-lg-8 mx-auto text-center " data-wow-duration="1s">
                    <h6 class="rt-section-title">
                        {{$selected_language->data['why_contactless_menu'] ?? 'Why contactless menu?'}}
                    </h6>
                </div><!-- /.col-xl-6 col-lg-8 mx-auto text-center  -->
            </div><!-- /.row -->
            <div class="rt-spacer-40"></div><!-- /.rt-spacer-40 -->
            <div class="row smallgap">
                <div class="col-xl-3 col-lg-4  col-md-6 col-12 mx-auto rt-mb-10">
                    <div
                        class="rt-single-icon-box icon-center text-center shdoaw-style rt-rounded-12 enable-pin rt-pt-50 rt-pb-40 rt-pl-26 rt-pr-26 wow fade-in-bottom">
                        <div class="icon-thumb">
                            <img src="themes/default/images/all-icon/services-icon-29.png" alt="services icon"
                                 draggable="false" class="rt-Bshadow-1 rt-circle">
                        </div><!-- /.icon-thumb -->
                        <div class="iconbox-content">
                            <h5 class="f-size-24 rt-semiblod title-bar bg-3">  {{$selected_language->data['safety_first'] ?? 'Safety First'}}</h5>
                            <p class="f-size-15 line-height-25 rt-mb-0">{{$selected_language->data['safety_first_sub_title'] ?? 'Limiting the use of physical menus and promoting touchless QR Code menus reduces the risk of virus transmission, and keeps your customers and employees safe.'}}</p>

                        </div><!-- /.iconbox-content -->
                    </div><!-- /.@@box_class -->
                </div><!-- /.col-xl-3 col-lg-4  col-md-6 col-12 mx-auto -->
                <div class="col-xl-3 col-lg-4  col-md-6 col-12 mx-auto rt-mb-10">
                    <div
                        class="rt-single-icon-box icon-center text-center shdoaw-style rt-rounded-12 enable-pin rt-pt-50 rt-pb-40 rt-pl-26 rt-pr-26 wow fade-in-bottom"
                        data-wow-duration="1.5s">
                        <div class="icon-thumb">

                            <img src="themes/default/images/all-icon/services-icon-38.png" alt="services icon"
                                 draggable="false" class="rt-Bshadow-3 rt-circle">
                        </div><!-- /.icon-thumb -->
                        <div class="iconbox-content">
                            <h5 class="f-size-24 rt-semiblod title-bar bg-2">{{$selected_language->data['no_app_download_required'] ?? 'No App Download Required'}}</h5>
                            <p class="f-size-15 line-height-25 rt-mb-0">{{$selected_language->data['no_app_download_required_sub_title'] ?? 'Your diners can scan the QR Code using their
                                phone camera'}}></p><br><br><br>

                        </div><!-- /.iconbox-content -->
                    </div><!-- /.@@box_class -->
                </div><!-- /.col-xl-3 col-lg-4  col-md-6 col-12 mx-auto -->
                <div class="col-xl-3 col-lg-4  col-md-6 col-12 mx-auto rt-mb-10">
                    <div
                        class="rt-single-icon-box icon-center text-center shdoaw-style rt-rounded-12 enable-pin rt-pt-50 rt-pb-40 rt-pl-26 rt-pr-26 wow fade-in-bottom"
                        data-wow-duration="1.9s">
                        <div class="icon-thumb">
                            <img src="themes/default/images/all-icon/services-icon-31.png" alt="services icon"
                                 draggable="false" class="rt-Bshadow-2 rt-circle">
                        </div><!-- /.icon-thumb -->
                        <div class="iconbox-content">
                            <h5 class="f-size-24 rt-semiblod title-bar bg-1">{{$selected_language->data['easy_to_build_update'] ?? 'Easy to build & update'}}</h5>
                            <p class="f-size-15 line-height-25 rt-mb-0">{{$selected_language->data['easy_to_build_update_sub_title'] ?? 'Create contactless menu QR Codes under 3
                                minutes. Later, upload & save a new menu to the same QR Code.'}} </p><br><br><br>

                        </div><!-- /.iconbox-content -->
                    </div><!-- /.@@box_class -->
                </div><!-- /.col-xl-3 col-lg-4  col-md-6 col-12 mx-auto -->
                <div class="col-xl-3 col-lg-4  col-md-6 col-12 mx-auto rt-mb-10">
                    <div
                        class="rt-single-icon-box icon-center text-center shdoaw-style rt-rounded-12 enable-pin rt-pt-50 rt-pb-40 rt-pl-26 rt-pr-26 wow fade-in-bottom"
                        data-wow-duration="2s">
                        <div class="icon-thumb">
                            <img src="themes/default/images/all-icon/services-icon-30.png" alt="services icon"
                                 draggable="false" class="rt-Bshadow-4 rt-circle">
                        </div><!-- /.icon-thumb -->
                        <div class="iconbox-content">
                            <h5 class="f-size-24 rt-semiblod title-bar bg-4">{{$selected_language->data['inspires_the_confidence'] ?? 'Inspires the confidence to step out'}}</h5>
                            <p class="f-size-15 line-height-25 rt-mb-0">{{$selected_language->data['inspires_the_confidence_sub_title'] ?? 'Re-align your restaurant functioning with
                                contactless at the core.'}} </p><br><br>

                        </div><!-- /.iconbox-content -->
                    </div><!-- /.@@box_class -->
                </div><!-- /.col-xl-3 col-lg-4  col-md-6 col-12 mx-auto -->
            </div><!-- /.row -->
        </div><!-- /.rt-container -->
    </section>
    <div class="rt-spacer-115 rt-spacer-xs-75"></div><!-- /.rt-spacer-123 -->
    <!--
       !=========== Add Area start =========!
    -->


    <section class="add1-area rt-dim-gray5">
        <div class="rt-spacer-110 rt-spacer-xs-80"></div><!-- /.rt-spacer-115 -->
        <div class="rt-container">
            <div class="row ">
                <div class="col-lg-6 rt-mb-md-30">
                    <div class="rt-flow-parent text-center rtbgprefix-contain" data-scrollax-parent="true">
                        <!-- <div class="rt-inner-overlay"
                            style="background-image: url();"></div> -->
                        <div class="stra_image text-center">
                            <img src="themes/default/images/all-icon/mockup-star.png" alt="" class="">
                        </div><!-- /.stra_image -->

                        <!-- /.rt-inner-overlay -->
                        <div class="flow-box"></div><!-- /.flow-box -->
                        <div class="flow-img-item"
                             data-scrollax="properties: { translateX: '70px',translateY: '-30px' }">
                            <img src="themes/default/images/banner/mbl-mockup-1.png" alt=""
                                 class="pulse_animation_image">
                        </div><!-- /.flow-img-item -->
                    </div><!-- /.rt-flow-parent -->
                </div><!-- /.col-lg-6 -->
                <div class="col-lg-6">
                    <h2 class="rt-section-title">
                        {{$selected_language->data['benefits_contactless_menu'] ?? 'Benefits of a contactless menu'}}
                    </h2>
                    <div class="rt-spacer-50"></div><!-- /.rt-spacer-30 -->
                    <div class="rt-single-icon-box   plain-list3  rt-mb-30">
                        <div class="icon-thumb ">
                            <img src="themes/default/images/all-icon/services-icon-31.png" alt=""
                                 class="rt-circle rt-Bshadow-1">
                        </div><!-- /.icon-thumb -->
                        <div class="iconbox-content">
                            <h5 class="f-size-24 f-size-xs-20 rt-mb-15  rt-strong">{{$selected_language->data['safer_to_use'] ?? 'Safer to use'}}</h5>

                            <p class="f-size-15 line-height-25 rt-mb-0">{{$selected_language->data['safer_to_use_sub_title'] ?? 'Germ-free, greener, quicker and safer than the
                                traditional menu.'}}</p>
                        </div><!-- /.iconbox-content -->
                    </div><!-- /.rt-single-icon-box   plain-list   -->
                    <div class="rt-single-icon-box   plain-list3  rt-mb-30">
                        <div class="icon-thumb ">
                            <img src="themes/default/images/all-icon/services-icon-32.png" alt=""
                                 class="rt-circle rt-Bshadow-3">
                        </div><!-- /.icon-thumb -->
                        <div class="iconbox-content">
                            <h5 class="f-size-24 f-size-xs-20 rt-mb-15  rt-strong">{{$selected_language->data['covid_compliant'] ?? 'COVID Compliant'}}</h5>
                            <p class="f-size-15 line-height-25 rt-mb-0">{{$selected_language->data['covid_compliant_sub_title'] ?? 'COVID compliance without single use paper menus
                                or disinfectant.'}}</p>
                        </div><!-- /.iconbox-content -->
                    </div><!-- /.rt-single-icon-box   plain-list   -->
                    <div class="rt-single-icon-box   plain-list3 ">
                        <div class="icon-thumb ">
                            <img src="themes/default/images/all-icon/services-icon-33.png" alt=""
                                 class="rt-circle rt-Bshadow-2">
                        </div><!-- /.icon-thumb -->
                        <div class="iconbox-content">
                            <h5 class="f-size-24 f-size-xs-20 rt-mb-15  rt-strong">{{$selected_language->data['easy_to_update'] ?? 'Easy to update'}}</h5>
                            <p class="f-size-15 line-height-25 rt-mb-0">{{$selected_language->data['easy_to_update_sub_title'] ?? 'Use the menu builder to instantly change your
                                menu. No re-prints!'}}</p>
                        </div><!-- /.iconbox-content -->
                    </div><!-- /.rt-single-icon-box   plain-list   -->

                </div><!-- /.col-lg-6 -->
            </div><!-- /.row -->
        </div><!-- /.rt-container -->
        <div class="rt-spacer-115 rt-spacer-xs-80"></div><!-- /.rt-spacer-115 -->
    </section>

    <!--
       !=========== Video Play Area start =========!
    -->


    <section class="vieo-play-area rtbgprefix-cover rtbg-fixed"
             style="background-image: url(themes/default/images/all-img/patter-1.png);">
        <div class="rt-spacer-110 rt-spacer-xs-80"></div><!-- /.rt-spacer-115 -->
        <div class="rt-container">
        </div><!-- /.rt-container -->
        <div class="add1-area2 rtbgprefix-full"
             style="background-image: url(themes/default/images/all-img/section-bg-8.png);">
            <div class="rt-spacer-110 rt-spacer-xs-80"></div><!-- /.rt-spacer-115 -->
            <div class="rt-container">
                <div class="row ">
                    <div class="col-lg-6 rt-mb-md-30">
                        <div class="rt-flow-parent text-center rtbgprefix-contain" data-scrollax-parent="true">
                            <!-- <div class="rt-inner-overlay"
                            style="background-image: url();"></div> -->
                            <div class="stra_image text-center">
                                <img src="themes/default/images/all-icon/mockup-star.png" alt="" class="">
                            </div><!-- /.stra_image -->

                            <!-- /.rt-inner-overlay -->
                            <div class="flow-box"></div><!-- /.flow-box -->
                            <div class="flow-img-item rt-pl-80 "
                                 data-scrollax="properties: { translateX: '70px',translateY: '-30px' }">
                                <img src="themes/default/images/all-img/product-2.png" alt=""
                                     class="pulse_animation_image">
                            </div><!-- /.flow-img-item -->
                        </div><!-- /.rt-flow-parent -->
                    </div><!-- /.col-lg-6 -->
                    <div class="col-lg-6" style="margin-top: 65px;">
                        <h2 class="rt-section-title">

                            {{$selected_language->data['see_a_demo_menu'] ?? 'See a Demo online menu'}}

                        </h2>
                        <p class="rt-mb-0">
                            1. {{$selected_language->data['see_a_demo_menu_point1'] ?? 'Use the phone camera or QR Application to scan the code.'}}
                            <br>
                            2. {{$selected_language->data['see_a_demo_menu_point2'] ?? 'Scroll around the menu and make your order.'}}<br>
                            3. {{$selected_language->data['see_a_demo_menu_point3'] ?? 'Your order is instantly received, and it’s coming!'}}

                        </p>

                    </div><!-- /.col-lg-6 -->
                </div><!-- /.row -->
            </div><!-- /.rt-container -->
            <div class="rt-spacer-110 rt-spacer-xs-80"></div><!-- /.rt-spacer-115 -->
        </div>
    </section>



    <div class="rt-spacer-100 rt-spacer-xs-80"></div><!-- /.rt-spacer-123 -->


    <div class="rt-container" id="how">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center " data-wow-duration="1s">
                <h2 class="rt-section-title">
                    How it works?
                </h2>
            </div><!-- /.col-lg-8 mx-auto text-center  -->
        </div><!-- /.row -->
        <div class="rt-spacer-40"></div><!-- /.rt-spacer-40 -->
        <div class="row">
            <div class="col-lg-3 col-md-6 mx-auto rt-mb-30">
                <div class="rt-single-icon-box icon-center  wow fade-in-bottom bg-transparent text-center  animated"
                     data-wow-duration="1s"
                     style="visibility: visible; animation-duration: 1s; animation-name: fade-in-bottom;">
                    <div class="icon-thumb mx-auto has-arrow-1">
                        <img src="themes/default/images/all-icon/step-1.png" alt=" services_icon" draggable="false">
                    </div><!-- /.icon-thumb -->
                    <div class="iconbox-content">
                        <h5 class="f-size-24 rt-semiblod rt-mb-15"> {{$selected_language->data['register'] ?? 'Register'}}</h5>
                        <p class="f-size-15 line-height-26 rt-mb-0">Create Account to get started.
                        </p>

                    </div><!-- /.iconbox-content -->
                </div><!-- /.@@box_class -->
            </div><!-- /.col-lg-4 -->
            <div class="col-lg-3 col-md-6 mx-auto rt-mb-30">
                <div class="rt-single-icon-box icon-center  wow fade-in-bottom bg-transparent text-center animated"
                     data-wow-duration="1s"
                     style="visibility: visible; animation-duration: 1s; animation-name: fade-in-bottom;">
                    <div class="icon-thumb mx-auto has-arrow-2">
                        <img src="themes/default/images/all-icon/step-2.png" alt=" services_icon" draggable="false">
                    </div><!-- /.icon-thumb -->
                    <div class="iconbox-content">
                        <h5 class="f-size-24 rt-semiblod rt-mb-15">{{$selected_language->data['create_menu'] ?? 'Create Menu'}}</h5>
                        <p class="f-size-15 line-height-26 rt-mb-0">{{$selected_language->data['create_menu_footer_subtitle'] ?? 'Create your menu visible for your customers.'}}
                        </p>

                    </div><!-- /.iconbox-content -->
                </div><!-- /.@@box_class -->
            </div><!-- /.col-lg-4 -->
            <div class="col-lg-3 col-md-6 mx-auto rt-mb-30">
                <div class="rt-single-icon-box icon-center  wow fade-in-bottom bg-transparent text-center animated"
                     data-wow-duration="1s"
                     style="visibility: visible; animation-duration: 1s; animation-name: fade-in-bottom;">
                    <div class="icon-thumb mx-auto has-arrow-3">
                        <img src="themes/default/images/all-icon/step-3.png" alt=" services_icon" draggable="false">
                    </div><!-- /.icon-thumb -->
                    <div class="iconbox-content">
                        <h5 class="f-size-24 rt-semiblod rt-mb-15">{{$selected_language->data['print_qr_code'] ?? 'Print QR Code'}}</h5>
                        <p class="f-size-15 line-height-26 rt-mb-0">{{$selected_language->data['print_qr_code_footer_subtitle'] ?? 'Put the printed tags on your tables.'}}
                        </p>

                    </div><!-- /.iconbox-content -->
                </div><!-- /.@@box_class -->
            </div><!-- /.col-lg-4 -->
            <div class="col-lg-3 col-md-6 mx-auto rt-mb-30">
                <div class="rt-single-icon-box icon-center  wow fade-in-bottom bg-transparent text-center animated"
                     data-wow-duration="1s"
                     style="visibility: visible; animation-duration: 1s; animation-name: fade-in-bottom;">
                    <div class="icon-thumb mx-auto">
                        <img src="themes/default/images/all-icon/step-4.png" alt=" services_icon" draggable="false">
                    </div><!-- /.icon-thumb -->
                    <div class="iconbox-content">
                        <h5 class="f-size-24 rt-semiblod rt-mb-15">{{$selected_language->data['receive_orders'] ?? 'Receive Orders'}}</h5>
                        <p class="f-size-15 line-height-26 rt-mb-0">{{$selected_language->data['receive_orders_footer_subtitle'] ?? 'When they order something, you get notified
                            instantly!'}}
                        </p>

                    </div><!-- /.iconbox-content -->
                </div><!-- /.@@box_class -->
            </div><!-- /.col-lg-4 -->
        </div><!-- /.row -->
    </div>




    <section class="rt-site-footer bg_footer2 home-five title_bar_footer rtbgprefix-cover"
             style="background-image: url(themes/default/images/all-img/footer-bg4.png);">
        <div class="footer-inner-content">
            <div class="footer-callto">
                <div class="rt-container">
                    <div class="row">
                        <div class="col-lg-4 rt-mb-md-20">
                            <div class="media">
                                <img src="themes/default/images/all-icon/footer-icon-1.png" class="rt-mr-20"
                                     alt="post_image">
                                <div class="media-body text-white">
                                    <span class="d-block rt-mb-8 rt-light3 ">{{$selected_language->data['give_us_call'] ?? 'Give us a Call'}}</span>
                                    <p class="rt-mb-0 f-size-18 rt-strong">
                                        {{$account_info !=NULL ? $account_info->contact_no:'81297*****'}}
                                    </p>
                                </div>
                            </div>
                        </div><!-- /.col-lg-4 -->
                        <div class="col-lg-4 rt-mb-md-20">
                            <div class="media">
                                <img src="themes/default/images/all-icon/footer-icon-2.png" class="rt-mr-20"
                                     alt="post_image">
                                <div class="media-body text-white">
                                    <span class="d-block rt-mb-8 rt-light3 ">{{$selected_language->data['send_us_message'] ?? 'Send us a Message'}}</span>
                                    <p class="rt-mb-0 f-size-18 rt-strong">
                                        {{$account_info !=NULL ? $account_info->application_email:'a@b.com'}}
                                    </p>

                                </div>
                            </div>
                        </div><!-- /.col-lg-4 -->
                        <div class="col-lg-4">
                            <div class="media">
                                <img src="themes/default/images/all-icon/footer-icon-3.png" class="rt-mr-20"
                                     alt="post_image">
                                <div class="media-body text-white">
                                    <span class="d-block rt-mb-8 rt-light3 ">{{$selected_language->data['visit_our_location'] ?? 'Visit our Location'}}</span>
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
                            {!! $selected_language->data['crafted_with_love'] ?? 'Crafted with <i class="fa fa-heart text-danger"></i> by' !!}
                            {{$account_info !=NULL ? $account_info->application_name:'CHEF MENU'}}.
                        </div><!-- /.col-lg-4 -->

                    </div><!-- /.row -->
                </div><!-- /.container -->
            </div><!-- /.footer-bottom -->
        </div><!-- /.footer-inner-content -->
    </section>




@endsection
