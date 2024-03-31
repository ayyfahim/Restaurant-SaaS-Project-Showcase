@extends("admin.adminlayout")
@section("admin_content")
<div class="container-fluid">
    <div class="card mb-4">
        <!-- Card header -->
        <div class="card-header">
            <h3 class="mb-0">{{ $title }}</h3>
            @if(session()->has("MSG"))
            <div class="alert alert-{{session()->get("TYPE")}}">
                <strong> <a>{{session()->get("MSG")}}</a></strong>
            </div>
            @endif
            @if($errors->any()) @include('admin.admin_layout.form_error') @endif
        </div>
        <!-- Card body -->
        <div class="card-body">
            <form method="post" action="{{route('user.change-password',['user_id'=>$user_id])}}">
                {{csrf_field()}}
                @method("PUT")
                <div class="row">
                   <div class="col-md-4" >
                        <div class="form-group">
                            <label class="form-control-label" for="password">New Password</label>
                            <input type="text" value="" name="password" class="form-control" required placeholder="">
                        </div>
                    </div>
                   <div class="col-md-4" >
                        <div class="form-group">
                            <label class="form-control-label" for="password">Re-enter New Password</label>
                            <input type="text" value="" name="re-password" class="form-control" required placeholder="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 form-group">
                        <input type="submit" value="Change Password" class="btn btn-danger btn-flat m-b-30 m-l-5 bg-danger border-none m-r-5 -btn">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('foot-script')
@endsection
