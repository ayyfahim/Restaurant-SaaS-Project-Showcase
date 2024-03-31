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
            <a href="{{ route('store_admin.categories') }}" class="btn btn-secondary">Category</a>
            <a class="btn btn-secondary" href="{{ route('store_admin.products') }}">Products</a>

            <a href="{{ route('store_admin.addon_categories') }}" class="btn btn-secondary">Addon Categories</a>
            <a class="btn" style="background-color: rgba(211, 0, 0, 1); color: #fff">Addons</a>





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
                        <h3 class="mb-0">Addons
                            <span
                                class="badge badge-md badge-circle badge-floating badge-gray border-white">{{ $addon_count }}</span>
                        </h3>


                    </div>
                    <div class="col-6 text-right">


                        <a href="#" class="btn btn-sm btn-primary btn-round btn-icon btn-primary-appetizr"
                            data-toggle="modal" data-target="#modal-form">
                            <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                            <span class="btn-inner--text">Add Addons</span>
                        </a>

                    </div>
                </div>
            </div>
            <!-- Light table -->
            <div class="table-responsive">
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Category</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th></th>

                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1 @endphp
                        @foreach ($addon as $add)
                            <tr>
                                <td>{{ $i++ }}</td>

                                @foreach ($add->addon_categories($add->addon_category_id) as $value)
                                    <td>{{ $value->name }}
                                    </td>
                                @endforeach

                                <td>{{ $add->addon_name }}</td>
                                <td> @include('layouts.render.currency',["amount"=>$add->price])</td>
                                <td><a href="{{ route('store_admin.update_addon', ['id' => $add->id]) }}" title="edit"
                                        class="btn btn-sm btn-primary">
                                        Edit
                                    </a>
                                    <button class="btn btn-sm btn-danger text-white"
                                        onclick="if(confirm('Are you sure you want to delete this item?')){ event.preventDefault();document.getElementById('delete-form-{{ $add->id }}').submit(); }"><b>Delete</b></button>
                                    <form method="post" action="{{ route('store_admin.delete_addon') }}"
                                        id="delete-form-{{ $add->id }}" style="display: none">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" value="{{ $add->id }}" name="id">
                                    </form>

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

                            <form method="post" action="{{ route('store_admin.add_addon') }}"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-group mb-3">
                                    <label class="form-control-label" for="exampleFormControlSelect1">Name</label>

                                    <input class="form-control" placeholder="Name" type="text" name="addon_name" required>

                                </div>


                                <div class="form-group mb-3">
                                    <label class="form-control-label" for="exampleFormControlSelect1">Select
                                        Category</label>
                                    <select class="form-control" name="addon_category_id" required>
                                        @foreach ($addons_category as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-control-label" for="exampleFormControlSelect1">Price</label>

                                    <input class="form-control" placeholder="Price" type="text" name="price" required>

                                </div>

                                <div class="form-group">
                                    <label class="form-control-label" for="exampleFormControlSelect1">Select Kitchen
                                        Location</label>
                                    <select class="form-control" name="kitchen_location_id" required>
                                        @forelse ($kitchen_locations as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                        @empty
                                            <option value="">No data found</option>
                                        @endforelse
                                    </select>
                                </div>



                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary my-4 btn-primary-appetizr">ADD</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    @endsection
