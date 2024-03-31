@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')


    <div class="container-fluid">
        <div class="card mb-4">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">Update Time Restriction</h3>
                @if (session()->has('MSG'))
                    <div class="alert alert-{{ session()->get('TYPE') }}">
                        <strong> <a>{{ session()->get('MSG') }}</a></strong>
                    </div>
                @endif
                @if ($errors->any()) @include('admin.admin_layout.form_error')
                @endif
            </div>
            <!-- Card body -->
            <div class="card-body">
                <form method="post"
                    action="{{ route('store_admin.edittimerestrictions_post', ['id' => $time_restriction->id]) }}"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    @method('PATCH')
                    <!-- Form groups used in grid -->
                    <div class="row">


                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="example3cols2Input">Name</label>
                                <input type="text" name="name" value="{{ $time_restriction->name }}" class="form-control"
                                    required>
                            </div>
                        </div>

                        @php
                            $times_list = times_list();
                        @endphp

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="example3cols2Input">Select
                                    Start Timing</label>
                                <select name="start_timing" class="form-control" required>
                                    @foreach ($times_list as $key => $value)
                                        <option value="{{ $value }}"
                                            {{ $time_restriction->data['start_time'] == $value ? 'selected' : '' }}>
                                            {{ $key }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="exampleFormControlSelect1">Select End
                                    Timing</label>
                                <select name="end_timing" class="form-control" required>
                                    @foreach ($times_list as $key => $value)
                                        <option value="{{ $value }}"
                                            {{ $time_restriction->data['end_time'] == $value ? 'selected' : '' }}>
                                            {{ $key }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit">Update</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>



        </div>

    @endsection
