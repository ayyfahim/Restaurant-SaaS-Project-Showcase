@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')


<div class="container-fluid mt-3">
    <div class="card mb-4">
        <!-- Card header -->
        <div class="card-header">
            <h3 class="mb-0">Add Products</h3>
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
            <form id="cropper_form" method="post" action="{{ route('store_admin.addproducts_post') }}"
                enctype="multipart/form-data">
                {{ csrf_field() }}
                <!-- Form groups used in grid -->
                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols1Input">Image</label>
                            <div class="custom-file">
                                <input name="image_url" id="file"
                                    class="file-name image input-flat ui-autocomplete-input image" type="file"
                                    readonly="readonly" placeholder="Browses photo" autocomplete="off">
                                {{-- <input type="file" name="image_url" class="image"> --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Product Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name')}}" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Price</label>
                            <input type="text" name="price" class="form-control" value="{{ old('price')}}" required>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Cooking Time</label>
                            <input type="number" name="cooking_time" class="form-control" value="{{ old('cooking_time')}}" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Select Category</label>
                            <select class="form-control js-example-basic-multiple" name="category_id" required>
                                @foreach ($category as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : null}}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Select Addon
                                Category</label>
                            <select class="form-control js-example-basic-multiple" name="addon_category_id[]" multiple>
                                @foreach ($addon_category as $cat)
                                <option value="{{ $cat->id }}" {{in_array($cat->id, old("addon_category_id") ?: []) ? "selected": ""}}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Select Allergens</label>
                            <select class="form-control js-example-basic-multiple" name="allergens[]" multiple>
                                @foreach ($allergens as $cat)
                                <option value="{{ $cat->id }}" {{in_array($cat->id, old("allergens") ?: []) ? "selected": ""}}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Select Diet
                                Preferences</label>
                            <select class="form-control js-example-basic-multiple" name="food_preferences[]" multiple>
                                @foreach ($food_preferences as $cat)
                                <option value="{{ $cat->id }}" {{in_array($cat->id, old("food_preferences") ?: []) ? "selected": ""}}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Is Enabled</label>
                            <select class="form-control" name="is_active" required>
                                <option value="1" {{ old('is_active') == 1 ? 'selected' : null}}>Yes</option>
                                <option value="0" {{ old('is_active') == 0 ? 'selected' : null}}>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Is Recommended</label>
                            <select class="form-control" name="is_recommended" required>
                                <option value="1" {{ old('is_recommended') == 1 ? 'selected' : null}}>Yes</option>
                                <option value="0" {{ old('is_recommended') == 0 ? 'selected' : null}}>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Is Veg</label>
                            <select class="form-control" name="is_veg" required>
                                <option value="1" {{ old('is_veg') == 1 ? 'selected' : null}}>Yes</option>
                                <option value="0" {{ old('is_veg') == 0 ? 'selected' : null}}>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Select Kitchen
                                Location</label>
                            <select class="form-control js-example-basic-multiple" name="kitchen_location_id">
                                <option value="">No Location</option>
                                @forelse ($kitchen_locations as $data)
                                <option value="{{ $data->id }}" {{ old('kitchen_location_id') == $data->id ? 'selected' : null}}>{{ $data->name }}</option>
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
                            <a href="javascript:void(0)" id="infoQuestionButton"
                                class="btn btn-sm btn-primary btn-round btn-icon mb-1 ml-1" data-toggle="tooltip"
                                data-original-title="Select the availability for your product. Add the time restrictions from the menu page.">
                                <span class="btn-inner--icon"><i class="fas fa-question"></i></span>
                            </a>
                            <select class="form-control js-example-basic-multiple" name="time_restriction">
                                <option value="">No Restriction</option>
                                @forelse ($time_restrictions as $restriction)
                                <option value="{{ $restriction->id }}" {{ old('time_restriction') == $restriction->id ? 'selected' : null}}>
                                    {{ $restriction->name }}
                                </option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Product SKU (PLU)</label>
                            <a href="javascript:void(0)" id="infoQuestionButton"
                                class="btn btn-sm btn-primary btn-round btn-icon mb-1 ml-1" data-toggle="tooltip"
                                data-original-title="Get the SKU/PLU from your Deliverect Product page.">
                                <span class="btn-inner--icon"><i class="fas fa-question"></i></span>
                            </a>
                            <input type="text" name="sku" class="form-control" placeholder="SKU" value="{{old('sku')}}" required>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Description</label>
                            <textarea class="form-control" name="description" rows="15" required>{{old('description')}}</textarea>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <button class="btn btn-primary btn-primary-appetizr" type="submit">Submit</button>
                        </div>


                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('layouts.cropper_modal')

@endsection
