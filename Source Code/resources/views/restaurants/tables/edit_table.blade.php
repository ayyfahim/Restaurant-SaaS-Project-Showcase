@extends("restaurants.layouts.restaurantslayout")

@section("restaurantcontant")


<div class="container-fluid">
    <div class="card mb-4">
        <!-- Card header -->
        <div class="card-header">
            <h3 class="mb-0">Edit Table </h3>
            @if(session()->has("MSG"))
            <div class="alert alert-{{session()->get("TYPE")}}">
                <strong> <a>{{session()->get("MSG")}}</a></strong>
            </div>
            @endif
            @if($errors->any()) @include('admin.admin_layout.form_error') @endif
        </div>
        <!-- Card body -->
        <div class="card-body">
            <form method="post" action="{{route('store_admin.edit_table_post',$id->id)}}" enctype="multipart/form-data">
                {{csrf_field()}}
                @method('PATCH')
                <!-- Form groups used in grid -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Table Name/Number</label>
                            <input type="text" name="table_name" value="{{$id->table_name}}" class="form-control"
                                required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Table Number</label>
                            <input type="text" name="table_number" class="form-control"
                                value="{{ $id->table_number ?? '' }}">
                            <small class="text-muted">Leave empty if you don't want to count.</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Visibility</label>
                            <div class="col-auto">
                                <label class="custom-toggle">
                                    <input type="checkbox" name="is_active" {{$id->is_active? "checked":null}}>
                                    <span class="custom-toggle-slider rounded-circle" data-label-off="Off"
                                        data-label-on="On"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>



    </div>

    @endsection
