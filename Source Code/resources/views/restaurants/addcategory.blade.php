@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')


<div class="container-fluid">
    <div class="card mb-4">
        <!-- Card header -->
        <div class="card-header">
            <h3 class="mb-0">Add Category</h3>
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
            <form method="post" action="{{ route('store_admin.addcategories_post') }}" enctype="multipart/form-data"
                id="cropper_form">
                {{ csrf_field() }}
                <!-- Form groups used in grid -->
                <div class="row">

                    {{-- <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols1Input">Image</label>

                            <div class="custom-file">
                                <input name="image_url" class="file-name input-flat ui-autocomplete-input image"
                                    type="file" readonly="readonly" placeholder="Browses photo" autocomplete="off">

                            </div>
                        </div>
                    </div> --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Category Name</label>
                            <input type="text" name="name" value="{{old('name')}}" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Is Enabled</label>
                            <select class="form-control" name="is_active" required>
                                <option value="1" @if (old('is_active') == 1) selected @endif >Enabled</option>
                                <option value="0" @if (old('is_active') == 0) selected @endif>Disabled</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Select Kitchen
                                Location</label>
                            <select class="form-control js-example-basic-multiple" name="kitchen_location_id">
                                <option value="">No Location</option>
                                @forelse ($kitchen_locations as $data)
                                    <option value="{{ $data->id }}" {{ $data->is_main ? 'selected' : (old('kitchen_location_id') == $data->id ? 'selected' : null)}}> {{ $data->name }}</option>
                                @empty
                                    <option value="">No data found</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Select Food Menu</label>
                            <select class="form-control js-example-basic-multiple" name="menu_id">
                                @forelse ($menus as $data)
                                <option value="{{ $data->id }}" {{ old('menu_id') == $data->id ? 'selected' : null}}>{{ $data->name }}</option>
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
