<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> {{$account_info != NULL ?$account_info->application_name:"Chef Digital Menu"}}</title>

    <!-- ================= Favicon ================== -->
    <!-- Standard -->
    <link rel="shortcut icon" href="{{asset($account_info != NULL ?$account_info->application_logo:"http://placehold.it/144.png/000/fff")}}">
    <!-- Retina iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="144x144" href="{{asset($account_info != NULL ?$account_info->application_logo:"http://placehold.it/144.png/000/fff")}}">
    <!-- Retina iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="114x114" href="{{asset($account_info != NULL ?$account_info->application_logo:"http://placehold.it/144.png/000/fff")}}">
    <!-- Standard iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="72x72" href="{{asset($account_info != NULL ?$account_info->application_logo:"http://placehold.it/144.png/000/fff")}}">
    <!-- Standard iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="57x57" href="{{asset($account_info != NULL ?$account_info->application_logo:"http://placehold.it/144.png/000/fff")}}">

    <link href={{asset('assets/css/lib/calendar2/pignose.calendar.min.css')}} rel="stylesheet"/>
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <!-- Icons -->
    <link rel="stylesheet" href={{asset('new/vendor/nucleo/css/nucleo.css')}} type="text/css">
    <link rel="stylesheet" href={{asset('new/vendor/@fortawesome/fontawesome-free/css/all.min.css')}} type="text/css">
    <!--  CSS -->
    <link rel="stylesheet" href={{asset('new/css/argon.css?v=1.1.0')}} type="text/css">
    <link rel="stylesheet" href={{asset('new/css/chef.css')}} type="text/css">
    <!-- Page plugins -->
    <link rel="stylesheet" href={{asset('new/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}>
    <link rel="stylesheet" href={{asset('new/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}>
    <link rel="stylesheet" href={{asset('new/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css')}}>

    <!-- Page plugins -->
    <link rel="stylesheet" href={{asset('new/vendor/select2/dist/css/select2.min.css')}}>
    <link rel="stylesheet" href={{asset('new/vendor/quill/dist/quill.core.css')}}>

    <style>

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            margin: 0;
        }
    </style>




</head>

<body class="bg-default">








@yield("register_content")


<!-- Page content -->



</div>

<script src={{asset("new/vendor/jquery/dist/jquery.min.js")}}></script>
<script src={{asset("new/vendor/bootstrap/dist/js/bootstrap.bundle.min.js")}}></script>
<script src={{asset("new/vendor/js-cookie/js.cookie.js")}}></script>
<script src={{asset("new/vendor/jquery.scrollbar/jquery.scrollbar.min.js")}}></script>
<script src={{asset("new/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js")}}></script>
<script src={{asset("new/vendor/chart.js/dist/Chart.min.js")}}></script>
<script src={{asset("new/vendor/chart.js/dist/Chart.extension.js")}}></script>
<script src={{asset("new/vendor/jvectormap-next/jquery-jvectormap.min.js")}}></script>
<script src={{asset("new/js/vendor/jvectormap/jquery-jvectormap-world-mill.js")}}></script>
<script src={{asset("new/js/argon.js?v=1.1.0")}}></script>



<script src={{asset("new/vendor/select2/dist/js/select2.min.js")}}></script>
<script src={{asset("new/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js")}}></script>
<script src={{asset("new/vendor/nouislider/distribute/nouislider.min.js")}}></script>
<script src={{asset("new/vendor/quill/dist/quill.min.js")}}></script>
<script src={{asset("new/vendor/dropzone/dist/min/dropzone.min.js")}}></script>
<script src={{asset("new/vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js")}}></script>



</body>

</html>
