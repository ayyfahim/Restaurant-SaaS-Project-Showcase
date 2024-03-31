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

    </style>

    {{-- @include('restaurants.notification.expired_notification') --}}
    {{-- @include('restaurants.notification.new_order_notification') --}}
    {{-- @include('restaurants.notification.call_waiter_notification') --}}


    <div class="container-fluid">

        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">

                <div class="row">
                    <div class="col-6">
                        <h3 class="mb-0"> All kitchens
                            <span class="badge badge-md badge-circle badge-floating badge-gray border-white">
                                {{ $kitchens->count() }}</span>
                        </h3>
                    </div>
                </div>
            </div>
            <!-- Light table -->
            <div class="table-responsive">
                @if (session()->has('MSG'))
                    <div class="alert alert-{{ session()->get('TYPE') }}">
                        <strong> <a>{{ session()->get('MSG') }}</a></strong>
                    </div>
                @endif
                <table class="table align-items-center table-flush text-center">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Kitchen Name</th>
                            <th>Kitchen Email</th>
                            <th>Kitchen Phone</th>
                            <th>Is main</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @php $i=1 @endphp
                        @foreach ($kitchens as $data)

                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->email }} </td>
                                <td>
                                    {{ $data->phone }}
                                </td>
                                <td>@if($data->is_main == 1)  <span class="badge badge-md badge-floating badge-success border-white"> True </span> @endif</td>
                                <td>
                                    @can('edit_kitchen')
                                    <a href="{{ route('store_admin.editkitchens', $data->id) }}"
                                        class="btn btn-success btn-sm text-white">Edit Kitchen</a>
                                    @endcan
                                    @can('delete_kitchen')
                                    <form action="{{ route('store_admin.deletekitchens', $data->id) }}" method="post"
                                        class="d-inline-block">
                                        {{ csrf_field() }}
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm text-white" type="submit">Delete
                                            Kitchen</button>
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

    @can('add_kitchen')
    <a href="{{ route('store_admin.addkitchens') }}" class="float btn-primary-appetizr">
        <i class="fa fa-plus my-float"></i>
    </a>
    @endcan


@endsection
