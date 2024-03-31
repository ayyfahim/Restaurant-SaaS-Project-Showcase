@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')


<div class="container-fluid">

    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="left-side-tabs">
                @include('restaurants.settings.settings_sidebar')
            </div>
        </div>
        <div class="col-lg-8 col-md-6">
            <div class="card card-static-2 mb-30">
                <div class="card-title-2">
                    <h4>Add Location Details</h4>

                </div>

                <div class="card-body">
                    @if (session()->has('MSG'))
                    <div class="alert alert-{{ session()->get('TYPE') }}">
                        <strong> <a>{{ session()->get('MSG') }}</a></strong>
                    </div>
                    @endif

                    @if ($errors->any())
                    @include('admin.admin_layout.form_error')
                    @endif

                    <form class="form-horizontal" method="post"
                        action="{{ route('store_admin.update_store_location') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <h6 class="heading-small mb-4">Add Location</h6>
                        <div class="pl-lg-4">
                            <div class="form-group">
                                <label class="form-control-label">Address (Text Address)</label>
                                <textarea rows="4" name="address" class="form-control">{{ $store->address }}</textarea>
                            </div>
                        </div>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" id="address-input" name="google_map_address"
                                            class="form-control map-input" placeholder="Type your location...">

                                        <input type="hidden" name="address_latitude" id="address-latitude" value="0" />
                                        <input type="hidden" name="address_longitude" id="address-longitude"
                                            value="0" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pl-lg-4">
                            <div id="address-map-container" style="width:100%;height:400px;margin: 15px 0;">
                                <div style="width: 100%; height: 100%" id="address-map"></div>
                            </div>
                        </div>
                        <div class="pl-lg-4">
                            <div class="form-group row">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit"
                                        class="btn btn-default btn-flat m-b-30 m-l-5 bg-primary border-none m-r-5 -btn">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>





@endsection

@section('custom_scripts')
<script
    src="https://maps.googleapis.com/maps/api/js?key={{ config('app.GOOGLE_MAP_API') }}&libraries=places&callback=initialize"
    async defer></script>
<script>
    function initialize() {

        $('form').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });
        const locationInputs = document.getElementsByClassName("map-input");

        const autocompletes = [];
        const geocoder = new google.maps.Geocoder;
        for (let i = 0; i < locationInputs.length; i++) {

            const input = locationInputs[i];
            const fieldKey = input.id.replace("-input", "");
            const isEdit = document.getElementById(fieldKey + "-latitude").value != '' && document.getElementById(fieldKey + "-longitude").value != '';

            const latitude = parseFloat(document.getElementById(fieldKey + "-latitude").value) || {{ $store->address_latitude }};
            const longitude = parseFloat(document.getElementById(fieldKey + "-longitude").value) || {{ $store->address_longitude }};

            const map = new google.maps.Map(document.getElementById(fieldKey + '-map'), {
                center: {lat: latitude, lng: longitude},
                zoom: 13
            });
            const marker = new google.maps.Marker({
                map: map,
                position: {lat: latitude, lng: longitude},
            });

            marker.setVisible(isEdit);

            const autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.key = fieldKey;
            autocompletes.push({input: input, map: map, marker: marker, autocomplete: autocomplete});
        }

        for (let i = 0; i < autocompletes.length; i++) {
            const input = autocompletes[i].input;
            const autocomplete = autocompletes[i].autocomplete;
            const map = autocompletes[i].map;
            const marker = autocompletes[i].marker;

            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                marker.setVisible(false);
                const place = autocomplete.getPlace();

                geocoder.geocode({'placeId': place.place_id}, function (results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                        const lat = results[0].geometry.location.lat();
                        const lng = results[0].geometry.location.lng();
                        setLocationCoordinates(autocomplete.key, lat, lng);
                    }
                });

                if (!place.geometry) {
                    window.alert("No details available for input: '" + place.name + "'");
                    input.value = "";
                    return;
                }

                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }
                marker.setPosition(place.geometry.location);
                marker.setVisible(true);

            });
        }
    }

    function setLocationCoordinates(key, lat, lng) {
        const latitudeField = document.getElementById(key + "-" + "latitude");
        const longitudeField = document.getElementById(key + "-" + "longitude");
        latitudeField.value = lat;
        longitudeField.value = lng;
    }

    // function initMap() {
    //     // var mapOptions = {
    //     //     zoom: 8,
    //     //     center: new google.maps.LatLng(44, -110),
    //     //     mapTypeId: 'satellite'
    //     // };

    //     // var map = new google.maps.Map(document.getElementById('map'), mapOptions);
    //     const latitude = -33.8688;
    //     const longitude = 151.2195;

    //     const map = new google.maps.Map(document.getElementById('address-map'), {
    //         center: {lat: latitude, lng: longitude},
    //         zoom: 13
    //     });

    //     const marker = new google.maps.Marker({
    //         map: map,
    //         position: {lat: latitude, lng: longitude},
    //     });
    // }
</script>
@endsection
