@extends('layouts.app')

@section('content')

    <style>
        .login-btn {
            display: block;
            border-radius: 50px;
            white-space: nowrap;
            margin: auto !important;
            margin-bottom: 15px !important;
            padding: .625rem 1.35rem !important;
            text-align: left !important;
        }

        .dashboard_icons {
            width: 20px;
            margin-right: 15px;
            filter: brightness(0) invert(1);
        }

    </style>

    <!-- Main content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header bg-red py-7 py-lg-8 pt-lg-9">

            <div class="separator separator-bottom separator-skew zindex-100">
                <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1"
                    xmlns="http://www.w3.org/2000/svg">
                    <polygon class="fill-black" points="2560 0 2560 100 0 100"></polygon>
                </svg>
            </div>
        </div>
        <!-- Page content -->
        <div class="container mt--8 pb-5">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="card bg-secondary border-0 mb-0">

                        <div class="card-body px-lg-5 py-lg-5">
                            <div class="text-center w-75 m-auto">
                                <img src="{{ asset('images/only_appetizr.png') }}" alt="Appetizr" class="img-fluid">
                            </div>
                            <h3 class="mt-3" style="text-align: center; color: #000;">{{ __('Login Methods') }}</h3><br>
                            <div class="row no-gutters align-items-center justify-content-center">
                                <div class="col-lg-7 col-md-7 col-sm-6">
                                    <a href="{{ route('store.login') }}" class="btn btn-primary-appetizr my-4 login-btn">
                                        <img class="dashboard_icons"
                                            src="{{ asset('images/icons/dashboard/dashboard.png') }}"
                                            alt="dashboard">{{ __('Manager Login') }}
                                    </a>
                                </div>
                            </div>
                            <div class="row no-gutters align-items-center justify-content-center">
                                <div class="col-lg-7 col-md-7 col-sm-6">
                                    <a href="{{ route('waiter.login') }}"
                                        class="btn btn-primary-appetizr my-4 login-btn"><img class="dashboard_icons"
                                            src="{{ asset('images/icons/dashboard/waiters.png') }}"
                                            alt="waiters">{{ __('Waiter Login') }}
                                    </a>
                                </div>
                            </div>
                            <div class="row no-gutters align-items-center justify-content-center">
                                <div class="col-lg-7 col-md-7 col-sm-6">
                                    <a href="{{ route('kitchen.login') }}"
                                        class="btn btn-primary-appetizr my-4 login-btn"><img class="dashboard_icons"
                                            src="{{ asset('images/icons/dashboard/kitchens.png') }}"
                                            alt="kitchens">{{ __('Kitchen Login') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>



@endsection

@section('custom_scripts')
    <script type="module">
        var element = document.querySelector('body') // Using a class instead, see note below.
        if (element.classList.contains("bg-default")) {
            element.classList.remove("bg-default");
            element.classList.add("bg-black");
        }

    </script>
@endsection
