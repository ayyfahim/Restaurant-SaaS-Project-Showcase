@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')


<div class="container-fluid">
    <div class="card mb-4">
        <!-- Card header -->
        <div class="card-header">
            <h3 class="mb-0">Edit Category</h3>
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
            <form method="post" action="{{ route('store_admin.edit_category', ['id' => $data->id]) }}"
                enctype="multipart/form-data" id="cropper_form">
                {{ csrf_field() }}
                @method('PATCH')
                <!-- Form groups used in grid -->
                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols1Input">Image</label>




                            <div class="custom-file">
                                <input name="image_url" class="file-name input-flat ui-autocomplete-input image"
                                    type="file" readonly="readonly" placeholder="Browses photo" autocomplete="off">


                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Category Name</label>
                            <input type="text" name="name" value="{{ $data->name }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Is Enabled</label>
                            <select class="form-control" name="is_active" required>
                                <option value="1" {{ $data->is_active == 1 ? 'selected' : null }}>Enabled</option>
                                <option value="0" {{ $data->is_active == 0 ? 'selected' : null }}>Disabled</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Select Food Menu</label>
                            <select class="form-control js-example-basic-multiple" name="menu_id">
                                @forelse ($menus as $menu)
                                <option value="{{ $menu->id }}" {{ $data->menu_id == $menu->id ? 'selected' : null }}>
                                    {{ $menu->name }}</option>
                                @empty
                                <option value="">No data found</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Select Index
                                Number</label>
                            <input type="number" value="{{ $data->index_number }}" name="index_number"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Select Kitchen
                                Location</label>
                            <select class="form-control js-example-basic-multiple" name="kitchen_location_id">
                                <option value="">No Location</option>
                                @forelse ($kitchen_locations as $location)
                                @isset($data->kitchen_location)
                                <option value="{{ $location->id }}"
                                    {{ $data->kitchen_location->id == $location->id ? 'selected' : null }}>
                                    {{ $location->name }}</option>
                                @else
                                <option value="{{ $location->id }}">
                                    {{ $location->name }}
                                </option>
                                @endisset
                                @empty
                                <option value="">No data found</option>
                                @endforelse


                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <button class="btn btn-primary btn-primary-appetizr" type="submit">Submit</button>
                        </div>
                    </div>


                </div>

            </form>
        </div>



    </div>

    @include('layouts.cropper_modal')
    @endsection
