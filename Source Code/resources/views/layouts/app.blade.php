<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> {{ isset($account_info) ? $account_info->application_name : config('app.name') }}</title>

    <!-- ================= Favicon ================== -->
    <!-- Standard -->
    <link rel="shortcut icon"
        href="{{ asset(isset($account_info) ? $account_info->application_logo : 'http://placehold.it/144.png/000/fff') }}">
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


    <link href={{ asset('assets/css/lib/calendar2/pignose.calendar.min.css') }} rel="stylesheet" />
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <!-- Icons -->
    <link rel="stylesheet" href={{ asset('new/vendor/nucleo/css/nucleo.css') }} type="text/css">
    <link rel="stylesheet" href={{ asset('new/vendor/@fortawesome/fontawesome-free/css/all.min.css') }} type="text/css">
    <!-- Argon CSS -->
    <link rel="stylesheet" href={{ asset('new/css/argon.css?v=1.1.0') }} type="text/css">
    <!-- Appetizr CSS -->
    <link rel="stylesheet" href={{ asset('/css/appetizr.css') }} type="text/css">
</head>

<body class="bg-default">

    @yield('content')


    <!-- jquery vendor -->
    <script src={{ asset('assets/js/lib/jquery.min.js') }}></script>
    <!-- nano scroller -->
    <script src={{ asset('assets/js/lib/jquery.nanoscroller.min.js') }}></script>
    <!-- sidebar -->
    <script src={{ asset('assets/js/lib/menubar/sidebar.js') }}></script>
    <!-- bootstrap -->
    <script src={{ asset('assets/js/lib/bootstrap.min.js') }}></script>


    <script src={{ asset('assets/js/lib/calendar-2/moment.latest.min.js') }}></script>
    <!-- scripit init-->
    <script src={{ asset('assets/js/lib/calendar-2/semantic.ui.min.js') }}></script>

    <!-- scripit init-->
    <script src={{ asset('assets/js/lib/calendar-2/prism.min.js') }}></script>

    <!-- scripit init-->
    <script src={{ asset('assets/js/lib/calendar-2/pignose.calendar.min.js') }}></script>

    <!-- scripit init-->
    <script src={{ asset('assets/js/lib/calendar-2/pignose.init.js') }}></script>
    <script src={{ asset('assets/js/lib/preloader/pace.min.js') }}></script>

    <script src={{ asset('assets/js/scripts.js') }}></script>
    @yield('custom_scripts')
</body>

</html>