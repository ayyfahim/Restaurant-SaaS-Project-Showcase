@extends('layouts.app')

@section('content')



    <!-- Main content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header bg-red py-7 py-lg-8 pt-lg-9">

            <div class="separator separator-bottom separator-skew zindex-100">
                <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1"
                    xmlns="http://www.w3.org/2000/svg">
                    <polygon class="fill-black" points="2560 0 2560 100 0 100"></polygon>
                </svg>
            </div>
        </div>
        <!-- Page content -->
        <div class="container mt--8 pb-5">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="card bg-secondary border-0 mb-0">

                        <div class="card-body px-lg-5 py-lg-5">
                            <div class="text-center w-75 m-auto">
                                @include('partials.appetizr_logo')
                            </div>
                            <h3 class="mt-3" style="text-align: center;  color: #000;">Restaurant {{ __('Login') }}</h3>
                            <br>
                            <form method="POST" action="{{ route('store.login') }}">
                                @csrf
                                @if (session()->has('MSG'))
                                    <div class="alert alert-{{ session()->get('TYPE') }}">
                                        <strong> <a>{{ session()->get('MSG') }}</a></strong>
                                    </div>
                                @endif
                                @if ($errors->any())
                                    @include('admin.admin_layout.form_error') @endif
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                        </div>

                                        <input id="email" type="email" placeholder="{{ __('E-Mail Address') }}"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" required autofocus>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                        </div>

                                        <input id="password" placeholder="{{ __('Password') }}" type="password"
                                            class="form-control" name="password" required autocomplete="current-password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="form-check-input" style="cursor: pointer;" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}

                                </div>
                                <div class="text-center">
                                    @csrf
                                    <button type="submit" class="btn btn-primary-appetizr my-4">
                                        {{ __('Login') }}</button>

                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>



@endsection

@section('custom_scripts')
    <script type="module">
        var element = document.querySelector('body') // Using a class instead, see note below.
        if (element.classList.contains("bg-default")) {
            element.classList.remove("bg-default");
            element.classList.add("bg-black");
        }

    </script>
@endsection
