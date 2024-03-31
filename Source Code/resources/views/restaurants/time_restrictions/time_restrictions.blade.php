@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')

<style>
    .float {
        position: fixed;
        width: 60px;
        height: 60px;
        bottom: 40px;
        right: 40px;
        background-color: #322A7D;
        color: #FFA101;
        border-radius: 50px;
        text-align: center;
        box-shadow: 2px 2px 3px #999;
    }


    .my-float {
        margin-top: 22px;
    }

    @media (max-width: 575.98px) {
        .card-body.menu {
            text-align: center;
        }

        .card-body.menu .btn {
            margin-top: 15px;
        }
    }
</style>







<div class="container-fluid">


    <div class="card-body menu scrolling-wrapper col-md-6 mx-auto pos-menu">

        @can('view_categories')
        <a class="btn btn-secondary mt-2" href="{{route('store_admin.categories')}}">Category</a>
        @endcan
        @can('view_products')
        <a class="btn btn-secondary" href="{{ route('store_admin.products') }}">Products</a>
        @endcan
        @can('view_setmenus')
        <a class="btn btn-secondary mt-2" href="{{route('store_admin.setmenus')}}">Set Menus</a>
        @endcan
        @can('view_addon_categories')
            <a href="{{ route('store_admin.addon_categories') }}" class="btn btn-secondary">Addon Categories</a>
        @endcan
        @can('view_timerestrictions')
            <button class="btn" style="background-color: rgba(211, 0, 0, 1); color: #fff">Time Restrictions</button>
        @endcan
        @can('view_food_menues')
            <a href="{{route('store_admin.menues')}}" class="btn btn-secondary mt-2">Food Menues</a>
        @endcan



    </div>




    <div class="card">
        <!-- Card header -->



        @if (session()->has('MSG'))
        <div class="card-header border-0">
            <div class="alert alert-{{ session()->get('TYPE') }}">
                <strong> <a>{{ session()->get('MSG') }}</a></strong>
            </div>
        </div>
        @endif
        @if ($errors->any()) @include('admin.admin_layout.form_error') @endif




        <div class="card-header border-0">
            <div class="row">
                <div class="col-6">
                    <h3 class="mb-0">Time Restrictions
                        <span class="badge badge-md badge-circle badge-floating badge-gray border-white">{{
                            $time_restrictions_count }}</span>
                    </h3>


                </div>
                <div class="col-6 text-right">
                    @can('add_timerestrictions')
                    <a href="#" class="btn btn-sm btn-primary btn-primary-appetizr btn-round btn-icon"
                        data-toggle="modal" data-target="#modal-form">
                        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                        <span class="btn-inner--text">Add Time Restriction</span>
                    </a>
                    @endcan

                </div>
            </div>
        </div>
        <!-- Light table -->
        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i=1 @endphp
                    @foreach ($time_restrictions as $restriction)
                    <tr>
                        <td>{{ $i++ }}</td>

                        <td>{{ $restriction->name }}</td>
                        <td style="text-align: center">
                            @can('edit_timerestrictions')
                            <a href="{{ route('store_admin.edittimerestrictions', $restriction->id) }}" title="edit"
                                class="btn btn-sm btn-primary">
                                Edit
                            </a>
                            @endcan
                            @can('delete_timerestrictions')
                            <button class="btn btn-sm btn-danger text-white"
                                onclick="if(confirm('Are you sure you want to delete this item?')){ event.preventDefault();document.getElementById('delete-form-{{ $restriction->id }}').submit(); }"><b>Delete</b></button>
                            <form method="post"
                                action="{{ route('store_admin.deletetimerestrictions', $restriction->id) }}"
                                id="delete-form-{{ $restriction->id }}" style="display: none">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" value="{{ $restriction->id }}" name="id">
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>


</div>


<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card bg-secondary border-0 mb-0">

                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <small>Time Restriction</small>
                        </div>
                        <form method="post" action="{{ route('store_admin.addtimerestrictions') }}"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group mb-3">
                                <div class="input-group input-group-merge input-group-alternative">

                                    <input class="form-control" placeholder="Name" type="text" name="name" required>
                                </div>
                            </div>
                            @php
                            $times_list = [
                            // 'Not Available' => '-1',
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
                            <div class="form-group mb-3">
                                <label class="form-control-label" for="exampleFormControlSelect1">Select Start
                                    Timing</label>
                                <select name="start_timing" class="form-control" required>
                                    @foreach ($times_list as $key => $value)
                                    <option value="{{ $value }}">
                                        {{ $key }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-control-label" for="exampleFormControlSelect1">Select End
                                    Timing</label>
                                <select name="end_timing" class="form-control" required>
                                    @foreach ($times_list as $key => $value)
                                    <option value="{{ $value }}">
                                        {{ $key }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-primary-appetizr my-4">ADD</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
