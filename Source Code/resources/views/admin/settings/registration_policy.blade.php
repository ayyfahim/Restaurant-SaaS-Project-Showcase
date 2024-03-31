
@extends("admin.adminlayout")

@section("admin_content")

    <div class="container-fluid">
        <div class="row">
            @include('admin.admin_layout.settings_menu')

            <div class="col-lg-8 col-md-6">
                <div class="card card-static-2 mb-30">
                    <div class="card-title-2">
                        <h4>Registration Policy</h4>
                    </div>
                    <div class="card-body">
                        @if(session()->has("MSG"))
                            <div class="alert alert-{{session()->get("TYPE")}}">
                                <strong> <a>{{session()->get("MSG")}}</a></strong>
                            </div>
                        @endif
                        @if($errors->any()) @include('admin.admin_layout.form_error') @endif

                        <form class="form-horizontal" method="post" action="{{route('update_registration_policy')}}" enctype="multipart/form-data">
                            {{csrf_field()}}

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">Signup Terms:</label>
                                        <textarea class="ckeditor form-control" name="{{$registration_settings[14]->id}}">{{$registration_settings[14]->value}}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">Signup File:</label>
                                        <input type="file" name="{{$registration_settings[15]->id}}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-email">Document verification Email:</label>
                                        <input type="email" required value="{{$registration_settings[16]->value}}" name="{{$registration_settings[16]->id}}" class="form-control" placeholder="Document Verification Email">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default btn-flat m-b-30 m-l-5 bg-primary border-none m-r-5 -btn">Save Settings</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>




@endsection
