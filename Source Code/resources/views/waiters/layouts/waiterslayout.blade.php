<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ Auth::user()->store->store_name }}</title>

    <!-- ================= Favicon ================== -->
    <!-- Standard -->
    <link rel="shortcut icon" href="http://placehold.it/64.png/000/fff">
    <!-- Retina iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="144x144" href="http://placehold.it/144.png/000/fff">
    <!-- Retina iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="114x114" href="http://placehold.it/114.png/000/fff">
    <!-- Standard iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="72x72" href="http://placehold.it/72.png/000/fff">
    <!-- Standard iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="57x57" href="http://placehold.it/57.png/000/fff">

    <link href={{ asset('assets/css/lib/calendar2/pignose.calendar.min.css') }} rel="stylesheet" />
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <!-- Icons -->
    <link rel="stylesheet" href={{ asset('new/vendor/nucleo/css/nucleo.css') }} type="text/css">
    <link rel="stylesheet" href={{ asset('new/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}
        type="text/css">
    <!--  CSS -->
    <link rel="stylesheet" href={{ asset('new/css/argon.css?v=1.1.0') }} type="text/css">

    <!-- Page plugins -->
    <link rel="stylesheet" href={{ asset('new/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}>
    <link rel="stylesheet" href={{ asset('new/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}>
    <link rel="stylesheet" href={{ asset('new/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}>

    <!-- Page plugins -->
    <link rel="stylesheet" href={{ asset('new/vendor/select2/dist/css/select2.min.css') }}>
    <link rel="stylesheet" href={{ asset('new/vendor/quill/dist/quill.core.css') }}>

    <link rel="stylesheet" href={{ asset('new/css/chef.css') }} type="text/css">
    <link rel="stylesheet" href={{ asset('new/css/toastr.min.css') }} type="text/css">

    <!-- Appetizr CSS -->
    <link rel="stylesheet" href={{ asset('css/appetizr.css') }} type="text/css">

    @yield("custom_styles")

</head>

<body>

    {{-- @include('waiters.notification.call_waiter_notification') --}}


    @include('waiters.layouts.sidebar')



    <!-- Main content -->
    <div class="main-content" id="panel">
        <!-- Topnav -->
        <nav class="navbar navbar-top navbar-expand navbar-dark border-bottom">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Search form -->
                    <div class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main">
                        <div class="form-group mb-0">
                            <h1>{{ $root_name }}</h1>
                        </div>
                        <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main"
                            aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <!-- Navbar links -->
                    <ul class="navbar-nav align-items-center ml-md-auto">
                        <li class="nav-item d-xl-none">
                            <!-- Sidenav toggler -->
                            <div class="pr-3 sidenav-toggler sidenav-toggler-light" data-action="sidenav-pin"
                                data-target="#sidenav-main">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                </div>
                            </div>
                        </li>

                        @isset($IsStoreEnabled)

                            @if ($IsStoreEnabled)
                                <li>
                                    <a class="btn btn-primary btn-md" style="background-color: #322A7D"
                                        href="http://wa.me/{{ $sanboxNumber }}?text=join%20tongue-getting"
                                        target="_blank">Enable WhatsApp notifications</a>
                                </li>
                            @endif

                        @endisset

                    </ul>
                    <ul class="navbar-nav align-items-center ml-auto ml-md-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <div class="media align-items-center">
                                    <span class="avatar avatar-sm rounded-circle">
                                        <img alt="Image placeholder" src={{ asset('assets/images/avatar/1.jpg') }}>
                                    </span>
                                    <div class="media-body ml-2 d-none d-lg-block">
                                        <span class="mb-0 text-sm  font-weight-bold"
                                            style="color: #000000;">{{ Auth::user()->store_name }}</span>
                                    </div>
                                </div>
                            </a>

                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="header" style="margin-top: 10px;">

        </div>
        <!-- Page content -->


        @yield("waiterscontent")


        <!-- Page content -->





        <script src={{ asset('new/vendor/jquery/dist/jquery.min.js') }}></script>
        <script src={{ asset('new/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}></script>
        <script src={{ asset('new/vendor/js-cookie/js.cookie.js') }}></script>
        <script src={{ asset('new/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}></script>
        <script src={{ asset('new/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}></script>
        <script src={{ asset('new/vendor/chart.js/dist/Chart.min.js') }}></script>
        <script src={{ asset('new/vendor/chart.js/dist/Chart.extension.js') }}></script>
        <script src={{ asset('new/vendor/jvectormap-next/jquery-jvectormap.min.js') }}></script>
        <script src={{ asset('new/js/vendor/jvectormap/jquery-jvectormap-world-mill.js') }}></script>
        <script src={{ asset('new/js/argon.js?v=1.1.0') }}></script>





        <script src={{ asset('new/vendor/select2/dist/js/select2.min.js') }}></script>
        <script src={{ asset('new/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}></script>
        <script src={{ asset('new/vendor/nouislider/distribute/nouislider.min.js') }}></script>
        <script src={{ asset('new/vendor/quill/dist/quill.min.js') }}></script>
        <script src={{ asset('new/vendor/dropzone/dist/min/dropzone.min.js') }}></script>
        <script src={{ asset('new/vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}></script>
        <script src={{ asset('assets/js/printthis.js') }}></script>

        <script src={{ asset('new/js/toastr.min.js') }}></script>
        {!! Toastr::message() !!}



        <script>
            $('#printButton').on('click', function() {
                $('#printThis').printThis();
            })
            //on single click, accpet order and disable button
            $('body').on("click", ".acceptOrderBtn", function(e) {
                $(this).addClass('pointer-none');
            });
            //on Single click donot cancel order
            $('body').on("click", ".cancelOrderBtn", function(e) {
                return false;
            });
            //cancel order on double click
            $('body').on("dblclick", ".cancelOrderBtn", function(e) {
                $(this).addClass('pointer-none');
                window.location = this.href;
                return false;
            });

        </script>
        <script>
            $('#modal-notification').modal('show')

        </script>
        @yield("custom_scripts")
        <script src="https://js.stripe.com/v3/"></script>
        <script type="text/javascript">
            // Create an instance of the Stripe object with your publishable API key
            var stripe = Stripe('{{ $publishableKey ?? '' }}');
            var checkoutButton = document.getElementById('checkout-button');
            var rozorpaybutton = document.getElementsByClassName("razorpay-payment-button");





            function triggerStripePayment(id) {

                fetch('{{ route('store_admin.subscription_complete_payment') }}' + "?plan_id=" + id, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                        body: JSON.stringify({
                            plan_id: id
                        })
                    })
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(session) {
                        return stripe.redirectToCheckout({
                            sessionId: session.id
                        });
                    })
                    .then(function(result) {
                        if (result.error) {
                            alert(result.error.message);
                        }
                    })
                    .catch(function(error) {
                        console.log("err");
                        alert('PAYMENT_ERROR #404');
                        console.error('Error:', error);
                    });
            }

        </script>

</body>

</html>
