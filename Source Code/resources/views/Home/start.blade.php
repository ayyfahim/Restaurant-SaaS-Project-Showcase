@extends('Home.home_layout.app')
@section('home_content')

    <div class="container">

        <div class="card" style="margin-top: 50px;">
            <div class="card-header">
                <h3 class="mb-0">Update</h3>
                <br>

                @if(session()->has("MSG"))
                    <div class="alert alert-{{session()->get("TYPE")}}">
                        <strong> <a>{{session()->get("MSG")}}</a></strong>
                    </div>
                @endif
                @if($errors->any()) @include('admin.admin_layout.form_error') @endif
            </div>
            <div class="card-body">


                <a href="{{route('clear_app')}}" class="btn btn-primary btn-lg btn-block">Update Database</a>
                <a href="{{route('insertdata')}}" class="btn btn-secondary btn-lg btn-block">Reset Seed Data</a>
            </div>
        </div>

    </div>



@endsection
