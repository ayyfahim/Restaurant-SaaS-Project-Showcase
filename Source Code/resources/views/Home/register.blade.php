@extends('Home.home_layout.registerpage')



@section('register_content')




    <nav id="navbar-main" class="navbar navbar-horizontal navbar-transparent navbar-main navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{route('home')}}">
                <img src="{{asset($account_info !=NULL ? $account_info->application_logo:'assets_home/images/logo/logo.png')}}" width="175px">
            </a>
            <a href="{{route('store_pricing')}}" class="btn btn-neutral btn-icon">

                <span class="nav-link-inner--text">Pricing</span>
            </a>

        </div>
    </nav>
    <!-- Main content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">


            <div class="container">
                <div class="header-body text-center mb-7">
                    <div class="row justify-content-center">
                        <div class="col-xl-5 col-lg-6 col-md-8 px-5">
                            <h1 class="text-white">Create an account</h1>

                        </div>
                    </div>
                </div>
            </div>




            <div class="separator separator-bottom separator-skew zindex-100">
                <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
                </svg>
            </div>
        </div>
        <!-- Page content -->
        <div class="container mt--8 pb-5">
            <!-- Table -->
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card bg-secondary border-0">

                        <div class="card-body px-lg-5 py-lg-5">
                            <div class="text-muted text-center mt-2 mb-4"><h3>Sign up</h3></div>

                            <form role="form" method="POST" action="{{route('register_new_store')}}" enctype="multipart/form-data">
                                @if(session()->has("MSG"))
                                    <div class="alert alert-{{session()->get("TYPE")}}">
                                        <strong> <a>{{session()->get("MSG")}}</a></strong>
                                    </div>
                                @endif
                                @if($errors->any()) @include('admin.admin_layout.form_error') @endif
                                    {{csrf_field()}}
                                <div class="form-group">
                                    <div class="input-group input-group-merge input-group-alternative mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-shop"></i></span>
                                        </div>
                                        <input class="form-control" required value="{{old('store_name')}}" name="store_name" placeholder="Store Name" type="text">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group input-group-merge input-group-alternative mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-mobile-button"></i></span>
                                        </div>
                                        <input class="form-control" required value="{{old('phone')}}" name="phone" placeholder="Contact Number" type="text">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-merge input-group-alternative mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                        </div>
                                        <input class="form-control" required  name="email" value="{{old('email')}}" placeholder="Email" type="email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                        </div>
                                        <input class="form-control" required name="password" value="{{old('password')}}" placeholder="Password" type="password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-merge input-group-alternative mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-cloud-upload-96"></i></span>
                                        </div>
                                        <input type="file" id="files" class="form-control" name="files[]" multiple required>
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <select name="plan" required class="form-control">
                                            @foreach($subscription as $data)
                                            <option value="{{$data->id}}">{{$data->name}} - {{$data->days}} - Days</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> -->
                                 <div class="row my-4">
                                    <div class="col-9">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            <input class="custom-control-input" id="customCheckRegister" type="checkbox" required>
                                            <label class="custom-control-label" for="customCheckRegister">
                                                <span class="text-muted">I agree with the <a href="#!">Privacy Policy</a></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <a href="{{ route('register_file_download') }}">Download</a>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary mt-4">Create account</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection