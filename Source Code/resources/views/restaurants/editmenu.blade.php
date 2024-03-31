@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')


<div class="container-fluid">
    <div class="card mb-4">
        <!-- Card header -->
        <div class="card-header">
            <h3 class="mb-0">Edit Menu</h3>

            @if (session()->has('MSG'))
            <div class="alert alert-{{ session()->get('TYPE') }}">
                <strong> <a>{{ session()->get('MSG') }}</a></strong>
            </div>
            @endif

            @if ($errors->any())
            @include('admin.admin_layout.form_error')
            @endif
        </div>
        <!-- Card body -->
        <div class="card-body">
            <form method="post" action="{{ route('store_admin.edit_menues', ['id' => $menu->id]) }}"
                enctype="multipart/form-data" id="cropper_form">
                {{ csrf_field() }}
                @method('PATCH')
                <!-- Form groups used in grid -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Category Name</label>
                            <input type="text" name="name" value="{{ $menu->name }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Is Enabled</label>
                            <select class="form-control" name="is_active" required>
                                <option value="1" {{ $menu->is_active == 1 ? 'selected' : null }}>Enabled</option>
                                <option value="0" {{ $menu->is_active == 0 ? 'selected' : null }}>Disabled</option>
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
    @endsection