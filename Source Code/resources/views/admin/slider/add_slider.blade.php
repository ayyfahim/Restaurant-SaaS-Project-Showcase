
@extends("admin.adminlayout")

@section("admin_content")


    <div class="container-fluid mt--6">
        <div class="card mb-4">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">Add New Slider <small>(recommended: 425px x 425px)</small></h3>
                @if(session()->has("MSG"))
                    <div class="alert alert-{{session()->get("TYPE")}}">
                        <strong> <a>{{session()->get("MSG")}}</a></strong>
                    </div>
                @endif
                @if($errors->any()) @include('admin.admin_layout.form_error') @endif
            </div>
            <!-- Card body -->
            <div class="card-body">
                <form  method="post" action="{{route('upload_slider')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <!-- Form groups used in grid -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols1Input">Photo</label>
                            <div class="custom-file">
                                <input required name="photo_url"  class="file-name input-flat ui-autocomplete-input" type="file" readonly="readonly" placeholder="Browses photo" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Slider Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlSelect1">Visibility</label>
                            <select class="form-control" name="is_visible" required>
                                <option value="1">Visible</option>
                                <option value="0">Hidden</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                    <button class="btn btn-primary" type="submit">Submit form</button>
                    </div>


            </div>

                </form>
        </div>



    </div>










@endsection
