@extends('Home.index_layout.layout')
@section('content')

<script src="{{ asset('vendor/html5qrcode/html5-qrcode.min.js') }}"></script>

<style>
    @font-face {
        font-family: "TwCenMT";
        src: url("/fonts/tw_cen_mt/normal.eot");
        src: url("/fonts/tw_cen_mt/normal.eot?#iefix") format("embedded-opentype"), url("/fonts/tw_cen_mt/normal.ttf") format("truetype"), url("/fonts/tw_cen_mt/normal.svg#Tw Cen MT") format("svg");
        font-weight: 400;
        font-style: normal;
    }

    @font-face {
        font-family: "TwCenMT-Bold";
        src: url("/fonts/tw_cen_mt/bold.eot");
        src: url("/fonts/tw_cen_mt/bold.eot?#iefix") format("embedded-opentype"), url("/fonts/tw_cen_mt/bold.ttf") format("truetype"), url("/fonts/tw_cen_mt/bold.svg#Tw Cen MT") format("svg");
        font-weight: 500;
        font-style: normal;
    }

    @font-face {
        font-family: "TwCenMT-ExtraBold";
        src: url("/fonts/tw_cen_mt/condensed_extra_bold.eot");
        src: url("/fonts/tw_cen_mt/condensed_extra_bold.eot?#iefix") format("embedded-opentype"), url("/fonts/tw_cen_mt/condensed_extra_bold.ttf") format("truetype"), url("/fonts/tw_cen_mt/condensed_extra_bold.svg#Tw Cen MT") format("svg");
        font-weight: 700;
        font-style: normal;
    }

    #get_started {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-family: "TwCenMT";
        text-align: center;
    }

    #camera_container {
        position: relative;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #0000009e;
    }

    #camera_container #reader {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        min-width: 600px;
    }

    .round-btn {
        background-color: red;
        border-radius: 35px;
        text-align: center;
    }

    .more {
        width: 100%;
    }
</style>

<div id="get_started">
    <div class="container">
        <div class="row">
            <div class="col-12 mb-3">
                <h1>Good Evening!</h1>
                <br>
                <p>Scan the QR code to beign</p>
                <br>
                <div onclick="startCamera()"
                    class="shadow d-flex align-items-center p-3 text-white text-center round-btn">
                    <div class="more w-100">
                        <h6 class="m-0">LET'S GO</h6>
                    </div>
                    <div class="ml-auto">
                        <i class="icofont-simple-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="camera_container" class="d-none">
    <div id="reader" width="600px"></div>
</div>

<script>
    function isValidUrl(_string) {
        let url_string;
        try {
            url_string = new URL(_string);
        } catch (_) {
            return false;
        }
        return url_string.protocol === "http:" || url_string.protocol === "https:" ;
    }

    function startCamera() {
        const el = document.querySelector('#camera_container');
        if (el.classList.contains("d-none")) {
            el.classList.remove("d-none");
        }

        const html5QrCode = new Html5Qrcode("reader");

        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            window.location.href = decodedText;
        };

        const config = { fps: 10, qrbox: 250 };

        // If you want to prefer back camera
        html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);
    }
</script>

@endsection
