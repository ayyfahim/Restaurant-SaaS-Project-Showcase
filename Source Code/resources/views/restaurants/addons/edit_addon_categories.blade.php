@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')


<div class="container-fluid">
    <div class="card mb-4">
        <!-- Card header -->
        <div class="card-header">
            <h3 class="mb-0">Update Addon Category</h3>
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
            <form method="post" action="{{ route('store_admin.addon_categories_update', ['id' => $data->id]) }}"
                enctype="multipart/form-data">
                {{ csrf_field() }}
                @method('PATCH')
                <!-- Form groups used in grid -->
                <div class="row">


                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Addon Category Name</label>
                            <input type="text" name="name" value="{{ $data->name }}" class="form-control" required>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input" style="color: red;">Select
                                Category Type</label>
                            <select name="type" class="form-control" required>
                                <option value="SNG" {{ $data->type == 'SNG' ? 'selected' : '' }}>Checkbox</option>
                                <option value="EXT" {{ $data->type == 'EXT' ? 'selected' : '' }}>Extra</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Minimum Amount</label>
                            <input type="number" name="minimum_amount" value="{{ $data->minimum_amount }}"
                                class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Maximum Amount</label>
                            <input type="number" name="maximum_amount" value="{{ $data->maximum_amount }}"
                                class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="sku">Product SKU (PLU)</label>
                            <input type="text" name="sku" value="{{ $data->sku }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6" style="margin-top: 40px">
                        <div class="form-check">
                            <input type="checkbox" id="multi_select" name="multi_select" {{ $data->multi_select ?
                            "checked" : '' }}
                            class="form-check-input" >
                            <label class="form-check-label" for="multi_select">Is Multi Select? (will not work
                                for Extra)</label>

                        </div>
                    </div>


                    <div class="col-md-12 mt-4">
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>



    </div>

    <div class="card">
        <!-- Card header -->



        {{-- @if (session()->has('MSG'))
        <div class="card-header border-0">
            <div class="alert alert-{{ session()->get('TYPE') }}">
                <strong> <a>{{ session()->get('MSG') }}</a></strong>
            </div>
        </div>
        @endif
        @if ($errors->any()) @include('admin.admin_layout.form_error') @endif --}}

        <div class="card-header border-0">
            <div class="row">
                <div class="col-6">
                    <h3 class="mb-0">Addons
                        <span class="badge badge-md badge-circle badge-floating badge-gray border-white">{{ $addon_count
                            }}</span>
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
                    @foreach ($data->addons as $add)
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

                                    <input class="form-control" placeholder="Name" type="text" name="addon_name"
                                        required>

                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-control-label" for="exampleFormControlSelect1">Product SKU
                                        (PLU)</label>

                                    <input class="form-control" placeholder="Product SKU (PLU)" type="text" name="sku">

                                </div>


                                <div class="form-group mb-3 d-none">
                                    <label class="form-control-label" for="exampleFormControlSelect1">Select
                                        Category</label>
                                    <select class="form-control" name="addon_category_id" required>
                                        {{-- @foreach ($addons_category as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach --}}
                                        <option value="{{ $data->id }}" selected>{{ $data->id }}</option>
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
                                        @forelse ($kitchen_locations as $location)
                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
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
    </div>




    @endsection