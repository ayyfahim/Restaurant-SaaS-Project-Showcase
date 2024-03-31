@extends("restaurants.layouts.restaurantslayout")

@section("restaurantcontant")


    <div class="container-fluid">
        <div class="card mb-4">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">Edit Promo Banner <small style="color: red">(recommended: 976px x 359px)</small></h3>
                @if(session()->has("MSG"))
                    <div class="alert alert-{{session()->get("TYPE")}}">
                        <strong> <a>{{session()->get("MSG")}}</a></strong>
                    </div>
                @endif
                @if($errors->any()) @include('admin.admin_layout.form_error') @endif
            </div>
            <!-- Card body -->
            <div class="card-body">
                <form  method="post" action="{{route('store_admin.banneredit',['id'=>$id->id])}}" enctype="multipart/form-data">
                {{csrf_field()}}
                @method('PATCH')
                <!-- Form groups used in grid -->
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="example3cols1Input">Banner Image</label>




                                <div class="custom-file">
                                    <input required name="photo_url"  class="file-name input-flat ui-autocomplete-input" type="file" readonly="readonly" placeholder="Browses photo" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="example3cols2Input">Banner Name</label>
                                <input type="text" name="name" value="{{$id->name}}" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="exampleFormControlSelect1">Visibility</label>
                                <div class="col-auto">
                                    <label class="custom-toggle">
                                        <input type="checkbox" name="is_visible" {{$id->is_visible? "checked":null}}>
                                        <span class="custom-toggle-slider rounded-circle" data-label-off="Off" data-label-on="On"></span>
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
