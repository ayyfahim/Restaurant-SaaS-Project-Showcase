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


    <div class="container-fluid">

        <div class="card-body">
            <button class="btn" style="background-color: rgba(211, 0, 0, 1); color: #fff">Waiters</button>
            {{-- <a class="btn btn-secondary" href="{{ route('store_admin.all_waiter_shifts') }}">Waiter Shifts</a> --}}
        </div>

        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">

                <div class="row">
                    <div class="col-6">
                        <h3 class="mb-0"> All Waiters
                            <span class="badge badge-md badge-circle badge-floating badge-gray border-white">
                                {{ $waiters->count() }}</span>
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
                            <th>Waiter Name</th>
                            <th>Waiter Email</th>
                            <th>Waiter Phone</th>
                            <th>Waiter Table</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @php $i=1 @endphp
                        @foreach ($waiters as $data)

                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->email }} </td>
                                <td>
                                    {{ $data->phone }}
                                </td>
                                <td>
                                    {{-- {{dd($data->store_tables)}} --}}
                                    @if ($data->store_tables)
                                    @foreach ($data->store_tables as $table)
                                        {{$table->table_name}} ({{$table->table_number}})<br/>
                                    @endforeach
                                        {{-- {{ $data->table->table_name }} ({{ $data->table->id }}) --}}
                                    @else
                                        null
                                    @endif
                                </td>
                                <td>
                                    @can('edit_waiter')
                                    <a href="{{ route('store_admin.editwaiters', $data->id) }}"
                                        class="btn btn-success btn-sm text-white">Edit Waiter</a>
                                    <button class="btn btn-danger btn-sm text-white"
                                        onclick="if(confirm('Are you sure you want to delete this waiter?')){ event.preventDefault();document.getElementById('delete-form-{{ $data->id }}').submit(); }"><b>Delete
                                            Waiter</b></button>
                                    @endcan
                                    @can('delete_waiter')
                                    <form action="{{ route('store_admin.deletewaiters', $data->id) }}" method="post"
                                        class=" d-none" id="delete-form-{{ $data->id }}">
                                        {{ csrf_field() }}
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm text-white" type="submit">Delete
                                            Waiter</button>
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

    @can('add_waiter')
    <a href="{{ route('store_admin.addwaiters') }}" class="float btn-primary-appetizr">
        <i class="fa fa-plus my-float"></i>
    </a>
    @endcan


@endsection
