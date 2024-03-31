@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')


<div class="container-fluid mt--6">
    <div class="card mb-4">
        <!-- Card header -->
        <div class="card-header">
            <h3 class="mb-0">Edit Set Menu</h3>
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
            <form method="post" action="{{ route('store_admin.edit_setmenu', ['id' => $data->id]) }}"
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
                            <label class="form-control-label" for="example3cols2Input">Product Name</label>
                            <input type="text" value="{{ $data->name }}" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Price</label>
                            <input type="text" value="{{ $data->price }}" name="price" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Cooking Time</label>
                            <input type="number" value="{{ $data->cooking_time }}" name="cooking_time"
                                class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Select Addon
                                Category</label>
                            <select class="form-control js-example-basic-multiple" name="addon_category_id[]" multiple>
                                @if ($addon_category_items != null)
                                <option value="">Remove Addon</option>
                                @else
                                <option value=""></option>
                                @endif

                                @foreach ($addon_category as $cat)
                                @if (isset($addon_category_items))
                                <option {{ $addon_category_items->pluck('addon_category_id')->contains($cat->id) ?
                                    'selected' : '' }}
                                    value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @else
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Select Allergens</label>
                            <select class="form-control js-example-basic-multiple" name="allergens[]" multiple>
                                <option value="">Remove Allergens</option>
                                @foreach ($allergens as $cat)
                                <option value="{{ $cat->id }}" {{ $data->allergens->contains($cat->id) ? 'selected' : ''
                                    }}>{{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Select Diet
                                Preferences</label>
                            <select class="form-control js-example-basic-multiple" name="food_preferences[]" multiple>
                                <option value="">Remove Diet Preferences</option>
                                @foreach ($food_preferences as $cat)
                                <option value="{{ $cat->id }}" {{ $data->allergens->contains($cat->id) ? 'selected' : ''
                                    }}>{{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Is Enabled</label>
                            <select class="form-control" name="is_active" required>
                                <option value="1" {{ $data->is_active == 1 ? 'selected' : null }}>Yes</option>
                                <option value="0" {{ $data->is_active == 0 ? 'selected' : null }}>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Is Recommended</label>
                            <select class="form-control" name="is_recommended" required>
                                <option value="1" {{ $data->is_recommended == 1 ? 'selected' : null }}>Yes</option>
                                <option value="0" {{ $data->is_recommended == 0 ? 'selected' : null }}>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Is Veg</label>
                            <select class="form-control" name="is_veg" required>
                                <option value="1" {{ $data->is_veg == 1 ? 'selected' : null }}>Yes</option>
                                <option value="0" {{ $data->is_veg == 0 ? 'selected' : null }}>No</option>
                            </select>
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
                                <option value="{{ $location->id }}" {{ $data->kitchen_location->id == $location->id ?
                                    'selected' : null }}>
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

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Select Time
                                Restriction</label>
                            <select class="form-control js-example-basic-multiple" name="time_restriction">
                                <option value="">No Restriction</option>
                                @forelse ($time_restrictions as $restriction)
                                @if ($data->time_restrictions->count() > 0)
                                <option value="{{ $restriction->id }}" {{ $data->
                                    time_restrictions->pluck('id')->contains($restriction->id) ? 'selected' : null }}>
                                    {{ $restriction->name }}</option>
                                @else
                                <option value="{{ $restriction->id }}">
                                    {{ $restriction->name }}
                                </option>
                                @endisset
                                @empty
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

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Product SKU (PLU)</label>
                            <input type="text" name="sku" class="form-control" placeholder="SKU"
                                value="{{ $data->sku }}">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Description</label>
                            <textarea class="form-control" name="description" rows="15"
                                required>{{ $data->description }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary btn-primary-appetizr" type="submit">Update Set Menu</button>
                    </div>


                </div>

            </form>
        </div>


    </div>





    {{-- <div class="row"> --}}
        {{-- <div class="col-lg-12"> --}}
            {{-- <div class="card alert"> --}}
                {{-- <div class="card-header"> --}}
                    {{-- <h4>Edit Products</h4> --}}
                    {{-- @if (session()->has('MSG')) --}}
                    {{-- <div class="alert alert-{{session()->get(" TYPE")}}"> --}}
                        {{-- <strong> <a>{{session()->get("MSG")}}</a></strong> --}}
                        {{-- </div> --}}
                    {{-- @endif --}}
                    {{-- @if ($errors->any()) @include('admin.admin_layout.form_error') @endif --}}
                    {{-- </div> --}}
                {{-- <div class="card-body"> --}}
                    {{-- <div class="menu-upload-form"> --}}
                        {{-- <form class="form-horizontal" method="post"
                            action="{{route('store_admin.edit_products',['id'=>$data->id])}}"
                            enctype="multipart/form-data"> --}}
                            {{-- {{csrf_field()}} --}}
                            {{-- @method('PATCH') --}}
                            {{-- <div class="form-group"> --}}
                                {{-- <label class="col-sm-2 control-label">Photo</label> --}}
                                {{-- <div class="col-sm-10"> --}}
                                    {{-- <div class="form-control file-input dark-browse-input-box"> --}}

                                        {{-- <input name="image_url" class="file-name input-flat ui-autocomplete-input"
                                            type="file" readonly="readonly" placeholder="Browses photo"
                                            autocomplete="off"> --}}
                                        {{-- </div> --}}
                                    {{-- </div> --}}
                                {{-- </div> --}}
                            {{-- <div class="form-group"> --}}
                                {{-- <label class="col-sm-2 control-label">Name</label> --}}
                                {{-- <div class="col-sm-10"> --}}
                                    {{-- <input type="text" value="{{$data->name}}" name="name" class="form-control"
                                        placeholder="Product Name"> --}}
                                    {{-- </div> --}}
                                {{-- </div> --}}

                            {{-- <div class="form-group"> --}}
                                {{-- <label class="col-sm-2 control-label">Price</label> --}}
                                {{-- <div class="col-sm-10"> --}}
                                    {{-- <input type="number" name="price" value="{{$data->price}}" class="form-control"
                                        placeholder="Price"> --}}
                                    {{-- </div> --}}
                                {{-- </div> --}}

                            {{-- <div class="form-group"> --}}
                                {{-- <label class="col-sm-2 control-label">Cooking Time</label> --}}
                                {{-- <div class="col-sm-10"> --}}
                                    {{-- <input type="text" name="cooking_time" value="{{$data->cooking_time}}"
                                        class="form-control" placeholder="Cooking Time"> --}}
                                    {{-- </div> --}}
                                {{-- </div> --}}

                            {{-- <div class="form-group"> --}}
                                {{-- <label class="col-sm-2 control-label">Description</label> --}}
                                {{-- <div class="col-sm-10"> --}}
                                    {{-- <textarea class="form-control" name="description" rows="3"
                                        placeholder="Description">{{$data->description}}</textarea>
                                    --}}
                                    {{-- </div> --}}
                                {{-- </div> --}}



                            {{-- <div class="form-group"> --}}
                                {{-- <label class="col-sm-2 control-label">Select Category</label> --}}
                                {{-- <div class="col-sm-10"> --}}
                                    {{-- <select class="form-control" name="category_id"> --}}

                                        {{-- <option value="{{$data->category_id}}">{{$data->category_id}}</option> --}}
                                        {{-- @foreach ($category as $cat) --}}
                                        {{-- <option value="{{ $cat->id }}">{{ $cat->name }}</option> --}}



                                        {{-- @endforeach --}}

                                        {{-- </select> --}}
                                    {{-- </div> --}}
                                {{-- </div> --}}


                            {{-- <div class="form-group"> --}}
                                {{-- <label class="col-sm-2 control-label">Is Enabled</label> --}}
                                {{-- <div class="col-sm-10"> --}}
                                    {{-- <select class="form-control" name="is_active"> --}}
                                        {{-- <option value="1" {{$data->is_active == 1 ? "selected":NULL}}>Yes</option>
                                        --}}
                                        {{-- <option value="0" {{$data->is_active == 0 ? "selected":NULL}}>No</option>
                                        --}}

                                        {{-- </select> --}}
                                    {{-- </div> --}}
                                {{-- </div> --}}

                            {{-- <div class="form-group"> --}}
                                {{-- <label class="col-sm-2 control-label">Is Recommended</label> --}}
                                {{-- <div class="col-sm-10"> --}}
                                    {{-- <select class="form-control" name="is_recommended"> --}}
                                        {{-- <option value="1" {{$data->is_recommended == 1 ? "selected":NULL}}>Yes
                                        </option> --}}
                                        {{-- <option value="0" {{$data->is_recommended == 0 ? "selected":NULL}}>No
                                        </option> --}}

                                        {{-- </select> --}}
                                    {{-- </div> --}}
                                {{-- </div> --}}


                            {{-- <div class="form-group"> --}}
                                {{-- <label class="col-sm-2 control-label">Is Veg</label> --}}
                                {{-- <div class="col-sm-10"> --}}
                                    {{-- <select class="form-control" name="is_veg"> --}}
                                        {{-- <option value="1" {{$data->is_veg == 1 ? "selected":NULL}}>Yes</option>
                                        --}}
                                        {{-- <option value="0" {{$data->is_veg == 0 ? "selected":NULL}}>No</option> --}}

                                        {{-- </select> --}}
                                    {{-- </div> --}}
                                {{-- </div> --}}








                            {{-- <div class="form-group"> --}}
                                {{-- <div class="col-sm-offset-2 col-sm-10"> --}}
                                    {{-- <button type="submit" class="btn btn-lg btn-primary">Update Products</button>
                                    --}}
                                    {{-- </div> --}}
                                {{-- </div> --}}
                            {{-- </form> --}}
                        {{-- </div> --}}
                    {{-- </div> --}}
                {{-- </div> --}}
            {{--
            <!-- /# card --> --}}
            {{--
        </div> --}}
        {{--
        <!-- /# column --> --}}
        {{--
    </div> --}}

    @include('layouts.cropper_modal')

    @endsection
