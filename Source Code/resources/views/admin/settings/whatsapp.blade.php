
@extends("admin.adminlayout")

@section("admin_content")

    <div class="container-fluid">

        <div class="row">
             @include('admin.admin_layout.settings_menu')
            <div class="col-lg-8 col-md-6">
                <div class="card card-static-2 mb-30">
                    <div class="card-title-2">
                        <h4>Whatsapp Notification Settings</h4>
                    </div>
                    <div class="card-body">
                        @if(session()->has("MSG"))
                            <div class="alert alert-{{session()->get("TYPE")}}">
                                <strong> <a>{{session()->get("MSG")}}</a></strong>
                            </div>
                        @endif
                        @if($errors->any()) @include('admin.admin_layout.form_error') @endif

                        <form class="form-horizontal" method="post" action="{{route('update_whatsapp')}}" enctype="multipart/form-data">
                            {{csrf_field()}}

                            <table class="table align-items-left table-flush table-hover">

                                <tbody>


                                <tr>
                                    <td class="table-user">
                                        <b>Whatsapp Notification</b>
                                    </td>
                                    <td>
                                        <label class="custom-toggle">
                                            <input type="checkbox" name="{{$whatsapp[4]->id}}" {{$whatsapp[4]->value ==1 ? "checked":NULL}} >
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="Off" data-label-on="On"></span>
                                        </label>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="table-user">
                                        <b>Whatsapp Notification for Store Owners</b>
                                    </td>
                                    <td>
                                        <label class="custom-toggle">
                                            <input type="checkbox" name="{{$whatsapp[8]->id}}" {{$whatsapp[8]->value ==1 ? "checked":NULL}} >
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="Off" data-label-on="On"></span>
                                        </label>
                                    </td>
                                </tr>


                                </tbody>
                            </table>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">Twillo Whatsapp No:</label>
                                        <input type="text"  value="{{$whatsapp[5]->value}}" name="{{$whatsapp[5]->id}}"  class="form-control" placeholder="PhoneCode">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">SandBoxID:</label>
                                        <input type="text"  value="{{$whatsapp[6]->value}}" name="{{$whatsapp[6]->id}}" class="form-control" placeholder="SandBoxID">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-email">SandBoxToken:</label>
                                        <input type="text" value="{{$whatsapp[7]->value}}" name="{{$whatsapp[7]->id}}"  class="form-control" placeholder="SandBoxToken">
                                    </div>
                                </div>


                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-email">SandBox Trail Text:</label>
                                        <input type="text" value="{{$whatsapp[10]->value}}" name="{{$whatsapp[10]->id}}"  class="form-control" placeholder="SandBox Trail Text">
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
