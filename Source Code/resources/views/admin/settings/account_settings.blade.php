
@extends("admin.adminlayout")

@section("admin_content")

    <div class="container-fluid">
        <div class="row">
            @include('admin.admin_layout.settings_menu')
            <!-- <div class="col-lg-4 col-md-6">
                <div class="left-side-tabs">
                    <div class="dashboard-left-links">
                        <a href="{{route('settings')}}" class="user-item">Site Settings</a>
                        <a href="#" class="user-item active">Account Settings</a>
                        <a href="{{route('paymentsettings')}}" class="user-item "> Payment Settings</a>
                        <a href="{{route('whatsapp')}}" class="user-item"> Whatsapp Notification Settings</a>
                        <a href="{{route('privacy_policy')}}" class="user-item">  Privacy Policy</a>
                        <a href="{{route('privacy_policy')}}" class="user-item">  Registration Settings</a>
                        <a href="{{route('cache_settings')}}" class="user-item">  Cache Settings</a>

                    </div>
                </div>
            </div> -->

            <div class="col-lg-8 col-md-6">
                <div class="card card-static-2 mb-30">
                    <div class="card-title-2">
                        <h4>Account Settings</h4>
                    </div>
                    <div class="card-body">
                        @if(session()->has("MSG"))
                            <div class="alert alert-{{session()->get("TYPE")}}">
                                <strong> <a>{{session()->get("MSG")}}</a></strong>
                            </div>
                        @endif
                        @if($errors->any()) @include('admin.admin_layout.form_error') @endif

                        <form class="form-horizontal" method="post" action="{{route('update_account_settings')}}" enctype="multipart/form-data">
                            {{csrf_field()}}

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">Name:</label>
                                        <input type="text" required value="{{auth()->user()->name}}" name="name"  class="form-control" placeholder="Name">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">Email:</label>
                                        <input type="email" required value="{{auth()->user()->email}}"  name="email" class="form-control" placeholder="Email">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-email">Change Password:</label>
                                        <input type="text" value="" name="password"  class="form-control" placeholder="New Password">
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
