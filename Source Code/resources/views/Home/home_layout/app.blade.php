<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" href="img/logo.svg">
    <title> {{ isset($account_info) && $account_info != null  ? $account_info->application_name : 'Chef Digital Menu' }}
    </title>


    <!-- ================= Favicon ================== -->
    <!-- Standard -->
    <link rel="shortcut icon"
        href="{{ asset( isset($account_info) && $account_info != null  ? $account_info->application_logo : 'http://placehold.it/144.png/000/fff') }}">
    {{--
    <!-- Retina iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="144x144"
        href="{{ asset($account_info != null ? $account_info->application_logo : 'http://placehold.it/144.png/000/fff') }}">
    <!-- Retina iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="114x114"
        href="{{ asset($account_info != null ? $account_info->application_logo : 'http://placehold.it/144.png/000/fff') }}">
    <!-- Standard iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="72x72"
        href="{{ asset($account_info != null ? $account_info->application_logo : 'http://placehold.it/144.png/000/fff') }}">
    <!-- Standard iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="57x57"
        href="{{ asset($account_info != null ? $account_info->application_logo : 'http://placehold.it/144.png/000/fff') }}">
    --}}
    <!-- Slick Slider -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/slick/slick.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/slick/slick-theme.min.css') }}" />
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}

    <!-- Icofont Icon-->
    <link href="{{ asset('vendor/icons/icofont.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{ asset('css/custom_style.css') }}" rel="stylesheet">
    <!-- Sidebar CSS -->
    <link href="{{ asset('vendor/sidebar/demo.css') }}" rel="stylesheet">
    <!-- Appetizr -->
    <link href="{{ mix('/css/appetizr.css') }}" rel="stylesheet">
    @laravelPWA


    <!-- Required jquery and libraries -->

    @if (Route::is('checkout'))
    <!-- Adyen -->
    <script src="https://checkoutshopper-live.adyen.com/checkoutshopper/sdk/3.15.1/adyen.js"
        integrity="sha384-18bB9irNIu0hQBFN+kxYgjeiMARTx7ukMoeFWLw/Autuyi+w0S9nXsf5Fn/VwmI+" crossorigin="anonymous">
    </script>

    <link rel="stylesheet" href="https://checkoutshopper-live.adyen.com/checkoutshopper/sdk/3.15.1/adyen.css"
        integrity="sha384-4ppagynsoxB4LNdDTdpX1No72EGOMm13+Y89hg+nz+dAIAWchOBTEnLL7u/hi6eM" crossorigin="anonymous" />
    @endif

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"
        type="a8991de296182f37e0c28854-text/javascript"></script>
    <!-- slick Slider JS-->
    <script type="a8991de296182f37e0c28854-text/javascript" src="{{ asset('vendor/slick/slick.min.js') }}"></script>
    <!-- Sidebar JS-->
    <script type="a8991de296182f37e0c28854-text/javascript" src="{{ asset('vendor/sidebar/hc-offcanvas-nav.js') }}">
    </script>
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/osahan.js') }}" type="a8991de296182f37e0c28854-text/javascript"></script>
    <script src="{{ asset('js/rocket-loader.min.js') }}" data-cf-settings="a8991de296182f37e0c28854-|49" defer="">
    </script>
    </body>
    <script src="{{ mix('/js/app.js') }}" defer></script>
    {{-- <script src="{{ asset('firebase_configs.js') }}" defer></script> --}}

    <style>
        @media screen and (-webkit-min-device-pixel-ratio:0) {

            select,
            textarea,
            input {
                font-size: 16px;
            }
        }
    </style>


    <!-- Pixel Code for https://analytics.appetizr.fr/ -->
    {{-- <script async src="https://analytics.appetizr.fr/pixel/FqKkPyc348IxrFSR"></script> --}}
    <!-- END Pixel Code -->
</head>

<body>
    <div id="clientKey" class="d-none">{{ config('app.client_key') }}</div>

    @yield('home_content')

</body>

<script>
    function searchThroughProducts() {
        var input = document.getElementById("Search");
        var filter = input.value.toLowerCase();
        var nodes = document.getElementsByClassName('search');
        for (i = 0; i < nodes.length; i++) {
            if (nodes[i].innerText.toLowerCase().includes(filter)) {
                nodes[i].style.display = "block";
            } else {
                nodes[i].style.display = "none";
            }
        }
    }

    function test() {
        var x = document.getElementById("snackbar");
        x.className = "show";
        setTimeout(function() {
            x.className = x.className.replace("show", "");
        }, 3000);
    }

</script>
<script src="/firebase_configs.js" async=""></script>
<script src="{{ asset('vendor/html5qrcode/html5-qrcode.min.js') }}" async=""></script>







</html>
