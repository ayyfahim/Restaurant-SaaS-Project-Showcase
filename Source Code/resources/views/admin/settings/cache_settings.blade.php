
@extends("admin.adminlayout")

@section("admin_content")

    <div class="container-fluid">

        <div class="row">
             @include('admin.admin_layout.settings_menu')
            <div class="col-lg-8 col-md-6">
                <div class="card card-static-2 mb-30">
                    <div class="card-title-2">
                        <h4>Cache Settings</h4>
                    </div>
                    <div class="card-body">
                        @if(session()->has("MSG"))
                            <div class="alert alert-{{session()->get("TYPE")}}">
                                <strong> <a>{{session()->get("MSG")}}</a></strong>
                            </div>
                        @endif
                        @if($errors->any()) @include('admin.admin_layout.form_error') @endif





                                <table class="table align-items-left table-flush table-hover">

                                    <tbody>
                                    <tr>
                                        <td class="table-user">
                                            <b>Update Latest Database</b>
                                        </td>

                                        <td>
                                            <a href="{{route('clear_app')}}" class="btn btn-primary btn-sm">Update Latest Database</a>
                                        </td>
                                    </tr>


                                    <tr>
                                        <td class="table-user">
                                            <b>Clear Config Cache</b>
                                        </td>
                                        <td>
                                            <a href="{{route('config_cache')}}" class="btn btn-primary btn-sm">Clear Config Cache</a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="table-user">
                                            <b>Clear Application Cache</b>
                                        </td>
                                        <td>
                                            <a href="{{route('app_cache')}}" class="btn btn-primary btn-sm">Clear Application Cache</a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="table-user">
                                            <b>Clear View Cache</b>
                                        </td>
                                        <td>
                                            <a href="{{route('view_cache')}}" class="btn btn-primary btn-sm">Clear View Cache</a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="table-user">
                                            <b>Update Whatsapp Notification</b>
                                        </td>
                                        <td>
                                            <a href="{{route('newvalue')}}" class="btn btn-primary btn-sm">Update Whatsapp Notification</a>
                                        </td>
                                    </tr>


                                    <tr>
                                        <td class="table-user">
                                            <b>Insert Privacy Policy Data</b>
                                        </td>
                                        <td>
                                            <a href="{{route('privacynew')}}" class="btn btn-primary btn-sm">Insert Privacy Policy Data</a>
                                        </td>
                                    </tr>


                                    </tbody>
                                </table>
                                <hr>







                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
