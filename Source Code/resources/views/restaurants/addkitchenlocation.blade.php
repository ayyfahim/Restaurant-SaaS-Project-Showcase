@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')


    <div class="container-fluid mt--6">
        <div class="card mb-4">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">Add Kitchen Location</h3>
                @if (session()->has('MSG'))
                    <div class="alert alert-{{ session()->get('TYPE') }}">
                        <strong> <a>{{ session()->get('MSG') }}</a></strong>
                    </div>
                @endif
                @if ($errors->any()) @include('admin.admin_layout.form_error')
                @endif
            </div>
            <!-- Card body -->
            <div class="card-body">
                <form method="post" action="{{ route('store_admin.addkitchenlocation_post') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <!-- Form groups used in grid -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="example3cols2Input">Location Name</label>
                                <input type="text" name="location" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <button class="btn btn-primary btn-primary-appetizr" type="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
