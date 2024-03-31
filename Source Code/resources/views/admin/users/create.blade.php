@extends("admin.adminlayout")
@section("admin_content")
<style>
    .input-group .copy-icons{
        position: absolute;
    right: 13px;
    top: 8px;
    z-index: 999
    }
    .input-group .copy-icons i{
        cursor: pointer;
    /* margin: 4px 10px; */
    border: 1px solid white;
    border-radius: 5px;
    background: #fff;
    padding: 7px 23px 6px 8px;
    }
    .input-group .copy-icons i:nth-child(2){
        padding: 7px 20px 6px 8px;
    }
</style>
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
            <form method="post" action="@if(isset($data)){{route('user.update',['user'=>$data->id])}}@else{{ route('user.store') }}@endif">
                {{csrf_field()}}
                @if (isset($data))
                    @method("PUT")
                @endif
                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="name">Name</label>
                            <input type="text" value="{{ (isset($data) && isset($data->name)) ? $data->name : old('name') }}" name="name" class="form-control" required placeholder="Name">
                        </div>
                    </div>

                   <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="email">Email</label>
                            <input type="email" name="email" value="{{ (isset($data) && isset($data->email)) ? $data->email : old('email') }}" class="form-control" required placeholder="Email">
                        </div>
                    </div>

                   <div class="col-md-4 @if(isset($data) && isset($data->password)) d-none @endif" >
                        <div class="form-group">
                            <label class="form-control-label" for="password">Password</label>
                            <div class="input-group">
                                <input type="text" name="password" id="password" value="{{ (isset($data) && isset($data->password)) ? $data->password : old('password') }}" name="password" class="form-control" required readonly placeholder="password">
                                <div class="copy-icons">
                                    <i class="fas fa-sync fa-fw mr-1" data-toggle="tooltip" title="Refresh Password" id="sync-password" style="cursor: pointer"></i>
                                    <i class="far fa-clipboard fa-fw" data-toggle="tooltip" title="Copy to Clipboard" id="clipboard" style="cursor: pointer"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="role">Role</label>
                            <select class="form-control" name="role" id="role" required>
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value={{$role->id}} @if (isset($data) && $data->roles[0]->id == $role->id) selected @endif >{{$role->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="permission">Permission</label>
                            <div class="card mb-4">
                                <ul id="permission" >
                                    {{-- {{$data->permissions}} --}}
                                    @if (isset($data))
                                        @foreach ($data->permissions as $permission)
                                            <li style="margin-left: 10px; height:100%"> {{$permission->name}} </li>
                                        @endforeach
                                    @else
                                        <li style="margin-left: 10px; height:100%"> - </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 form-group">
                        <input type="submit" value="Save" class="btn btn-default btn-flat m-b-30 m-l-5 bg-primary border-none m-r-5 -btn">
                        @if(isset($data) && isset($data->password))
                            <a href="{{route('user.change-password-get',['user_id'=>$data->id])}}" class="btn btn-danger btn-flat m-b-30 m-l-5 bg-danger border-none m-r-5 -btn">Change Password</a>
                        @endif
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
@section('foot-script')
<script>
    $(document).ready(function(){
        function generateP() {
            var pass = '';
            var str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#$!%&*';

            for (i = 1; i <= 8; i++) {
                var char = Math.floor(Math.random()* str.length + 1);
                pass += str.charAt(char)
            }
            return pass;
        }

        $('#password').val(generateP());

        $('#sync-password').on('click',function(){
            generateP();
            $('#password').val(generateP());
        });

        $('#clipboard').on('click',function(){
            var copyText = $('#password').val();
            if (navigator.clipboard != undefined) {//Chrome
                navigator.clipboard.writeText(copyText);
            }else if(window.clipboardData) { // Internet Explorer
                window.clipboardData.setData("Text", copyText);
            }
            console.log("Copied");
        });

        $("#role").on("change", function() {
            var role_id = $('#role').val();
            $.ajax ({
                url: '/roles/'+role_id,
                dataType: 'json',
                method:'GET',
                success: function(response) {
                    appendString = "";
                    response.forEach(element => {
                        appendString += `<li style="margin-left: 10px; height:100%">${element.name}</li>`
                    });
                    $("#permission").html(appendString);
                }
            });
        });
    });
</script>
@endsection
