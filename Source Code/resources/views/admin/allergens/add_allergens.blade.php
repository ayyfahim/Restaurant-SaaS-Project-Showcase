@extends("admin.adminlayout")

@section("admin_content")


<div class="container-fluid">
    <div class="card mb-4">
        <!-- Card header -->
        <div class="card-header">
            <h3 class="mb-0">Add New Allergen</h3>
            @if(session()->has("MSG"))
            <div class="alert alert-{{session()->get("TYPE")}}">
                <strong> <a>{{session()->get("MSG")}}</a></strong>
            </div>
            @endif
            @if($errors->any()) @include('admin.admin_layout.form_error') @endif
        </div>
        <!-- Card body -->
        <div class="card-body">
            <form method="POST" action="{{ route('create_allergen') }}" enctype="multipart/form-data">
                {{csrf_field()}}

                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols1Input">Image</label>

                            <div class="custom-file">
                                <input value="{{old('image_url')}}" name="image_url"
                                    class="file-name input-flat ui-autocomplete-input" type="file" readonly="readonly"
                                    placeholder="Browses photo" autocomplete="off" required>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols1Input">Active Image</label>

                            <div class="custom-file">
                                <input value="{{old('active_image_url')}}" name="active_image_url"
                                    class="file-name input-flat ui-autocomplete-input" type="file" readonly="readonly"
                                    placeholder="Browses photo" autocomplete="off" required>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Name</label>
                            <input type="text" value="{{old('name')}}" name="name" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Type</label>
                            <select class="form-control" name="type" required>
                                <option value="1" selected>Allergen</option>
                                <option value="2">Food Preference</option>
                            </select>
                        </div>
                    </div>


                    <div class="col-12 form-group">
                        <input type="submit" value="Save"
                            class="btn btn-default btn-flat m-b-30 m-l-5 bg-primary border-none m-r-5 -btn">
                    </div>
                </div>

            </form>
        </div>


    </div>





    @endsection