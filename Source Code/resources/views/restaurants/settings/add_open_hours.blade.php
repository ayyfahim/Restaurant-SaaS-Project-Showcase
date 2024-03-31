@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')


    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="left-side-tabs">
                    @include('restaurants.settings.settings_sidebar')
                    <!-- <div class="dashboard-left-links">
                        <a href="{{ route('store_admin.settings') }}" class="user-item">Store Settings</a>
                        <a href="{{ route('store_admin.add_open_hours') }}" class="user-item active">Add Open Hours</a>
                        <a href="{{ route('store_admin.add_bank_details') }}" class="user-item">Add Bank
                            Details</a>
                            <a href="{{ route('store_admin.add_deliverect') }}" class="user-item">Deliverect</a>
                    </div> -->
                </div>
            </div>
            <div class="col-lg-8 col-md-6">
                <div class="card card-static-2 mb-30">
                    <div class="card-title-2">
                        <h4>Add Open Hours</h4>

                    </div>

                    <div class="card-body">
                        @if (session()->has('MSG'))
                            <div class="alert alert-{{ session()->get('TYPE') }}">
                                <strong> <a>{{ session()->get('MSG') }}</a></strong>
                            </div>
                        @endif
                        @if ($errors->any()) @include('admin.admin_layout.form_error')
                        @endif

                        @php
                            $times_list = [
                                'Not Available' => '-1',
                                '00:00' => '00:00:00',
                                '01:00' => '01:00:00',
                                '01:30' => '01:30:00',
                                '02:00' => '02:00:00',
                                '02:30' => '02:30:00',
                                '03:00' => '03:00:00',
                                '03:30' => '03:30:00',
                                '04:00' => '04:00:00',
                                '04:30' => '04:30:00',
                                '05:00' => '05:00:00',
                                '05:30' => '05:30:00',
                                '06:00' => '06:00:00',
                                '06:30' => '06:30:00',
                                '07:00' => '07:00:00',
                                '07:30' => '07:30:00',
                                '08:00' => '08:00:00',
                                '08:30' => '08:30:00',
                                '09:00' => '09:00:00',
                                '09:30' => '09:30:00',
                                '10:00' => '10:00:00',
                                '10:30' => '10:30:00',
                                '11:00' => '11:00:00',
                                '11:30' => '11:30:00',
                                '12:00' => '12:00:00',
                                '12:30' => '12:30:00',
                                '13:00' => '13:00:00',
                                '13:30' => '13:30:00',
                                '14:00' => '14:00:00',
                                '14:30' => '14:30:00',
                                '15:00' => '15:00:00',
                                '15:30' => '15:30:00',
                                '16:00' => '16:00:00',
                                '16:30' => '16:30:00',
                                '17:00' => '17:00:00',
                                '17:30' => '17:30:00',
                                '18:00' => '18:00:00',
                                '18:30' => '18:30:00',
                                '19:00' => '19:00:00',
                                '19:30' => '19:30:00',
                                '20:00' => '20:00:00',
                                '20:30' => '20:30:00',
                                '21:00' => '21:00:00',
                                '21:30' => '21:30:00',
                                '22:00' => '22:00:00',
                                '22:30' => '22:30:00',
                                '23:00' => '23:00:00',
                                '23:30' => '23:30:00',
                            ];
                        @endphp

                        <form class="form-horizontal" method="post" action="{{ route('store_admin.update_open_hours') }}"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <h6 class="heading-small mb-4">Open Hours</h6>
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Monday</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Start Timing:
                                            </label><br>
                                            <select title="Start Timing" name="monday_start_time">
                                                @foreach ($times_list as $key => $value)
                                                    <option value="{{ $value }}"
                                                        @isset($open_hours['monday'])
                                                            {{ $open_hours['monday']['start_time'] == $value ? 'selected' : null }}
                                                        @endisset
                                                        >
                                                        {{ $key }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">End Timing: </label> <br>
                                            <select title="Start Timing" name="monday_end_time">
                                                @foreach ($times_list as $key => $value)
                                                    <option value="{{ $value }}"
                                                        @isset($open_hours['monday'])
                                                            {{ $open_hours['monday']['end_time'] == $value ? 'selected' : null }}
                                                        @endisset
                                                        >
                                                        {{ $key }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Tuesday</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Start Timing:
                                            </label><br>
                                            <select title="Start Timing" name="tuesday_start_time">
                                                @foreach ($times_list as $key => $value)
                                                    <option value="{{ $value }}"
                                                        @isset($open_hours['tuesday'])
                                                            {{ $open_hours['tuesday']['start_time'] == $value ? 'selected' : null }}
                                                        @endisset
                                                        >
                                                        {{ $key }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">End Timing: </label> <br>
                                            <select title="Start Timing" name="tuesday_end_time">
                                                @foreach ($times_list as $key => $value)
                                                    <option value="{{ $value }}"
                                                        @isset($open_hours['tuesday'])
                                                            {{ $open_hours['tuesday']['end_time'] == $value ? 'selected' : null }}
                                                        @endisset
                                                        >
                                                        {{ $key }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Wednesday</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Start Timing:
                                            </label><br>
                                            <select title="Start Timing" name="wednesday_start_time">
                                                @foreach ($times_list as $key => $value)
                                                    <option value="{{ $value }}"
                                                        @isset($open_hours['wednesday'])
                                                            {{ $open_hours['wednesday']['start_time'] == $value ? 'selected' : null }}
                                                        @endisset
                                                        >
                                                        {{ $key }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">End Timing: </label> <br>
                                            <select title="Start Timing" name="wednesday_end_time">
                                                @foreach ($times_list as $key => $value)
                                                    <option value="{{ $value }}"
                                                        @isset($open_hours['wednesday'])
                                                            {{ $open_hours['wednesday']['end_time'] == $value ? 'selected' : null }}
                                                        @endisset
                                                        >
                                                        {{ $key }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Thursday</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Start Timing:
                                            </label><br>
                                            <select title="Start Timing" name="thursday_start_time">
                                                @foreach ($times_list as $key => $value)
                                                    <option value="{{ $value }}"
                                                        @isset($open_hours['thursday'])
                                                            {{ $open_hours['thursday']['start_time'] == $value ? 'selected' : null }}
                                                        @endisset
                                                        >
                                                        {{ $key }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">End Timing: </label> <br>
                                            <select title="Start Timing" name="thursday_end_time">
                                                @foreach ($times_list as $key => $value)
                                                    <option value="{{ $value }}"
                                                        @isset($open_hours['thursday'])
                                                            {{ $open_hours['thursday']['end_time'] == $value ? 'selected' : null }}
                                                        @endisset
                                                        >
                                                        {{ $key }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Friday</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Start Timing:
                                            </label><br>
                                            <select title="Start Timing" name="friday_start_time">
                                                @foreach ($times_list as $key => $value)
                                                    <option value="{{ $value }}"
                                                        @isset($open_hours['friday'])
                                                            {{ $open_hours['friday']['start_time'] == $value ? 'selected' : null }}
                                                        @endisset
                                                        >
                                                        {{ $key }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">End Timing: </label> <br>
                                            <select title="Start Timing" name="friday_end_time">
                                                @foreach ($times_list as $key => $value)
                                                    <option value="{{ $value }}"
                                                        @isset($open_hours['friday'])
                                                            {{ $open_hours['friday']['end_time'] == $value ? 'selected' : null }}
                                                        @endisset
                                                        >
                                                        {{ $key }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Saturday</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Start Timing:
                                            </label><br>
                                            <select title="Start Timing" name="saturday_start_time">
                                                @foreach ($times_list as $key => $value)
                                                    <option value="{{ $value }}"
                                                        @isset($open_hours['saturday'])
                                                            {{ $open_hours['saturday']['start_time'] == $value ? 'selected' : null }}
                                                        @endisset
                                                        >
                                                        {{ $key }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">End Timing: </label> <br>
                                            <select title="Start Timing" name="saturday_end_time">
                                                @foreach ($times_list as $key => $value)
                                                    <option value="{{ $value }}"
                                                        @isset($open_hours['saturday'])
                                                            {{ $open_hours['saturday']['end_time'] == $value ? 'selected' : null }}
                                                        @endisset
                                                        >
                                                        {{ $key }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Sunday</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Start Timing:
                                            </label><br>
                                            <select title="Start Timing" name="sunday_start_time">
                                                @foreach ($times_list as $key => $value)
                                                    <option value="{{ $value }}"
                                                        @isset($open_hours['sunday'])
                                                            {{ $open_hours['sunday']['start_time'] == $value ? 'selected' : null }}
                                                        @endisset
                                                        >
                                                        {{ $key }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">End Timing: </label> <br>
                                            <select title="Start Timing" name="sunday_end_time">
                                                @foreach ($times_list as $key => $value)
                                                    <option value="{{ $value }}"
                                                        @isset($open_hours['sunday'])
                                                            {{ $open_hours['sunday']['end_time'] == $value ? 'selected' : null }}
                                                        @endisset
                                                        >
                                                        {{ $key }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
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
