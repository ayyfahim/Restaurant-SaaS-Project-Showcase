@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')


    <div class="container-fluid">
        <div class="card mb-4">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">Add Waiter</h3>
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
                <form method="post" action="{{ route('store_admin.addwaiters_post') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <!-- Form groups used in grid -->
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="example3cols2Input">Waiter Name</label>
                                <input type="text" name="name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="example3cols2Input">Waiter Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="example3cols2Input">Waiter Password</label>
                                <input type="text" name="password" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="example3cols2Input">Waiter Phone (optional)</label>
                                <input type="text" name="phone" class="form-control">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <button class="btn btn-primary btn-primary-appetizr" type="submit">Submit</button>
                            </div>
                        </div>



                    </div>

                </form>
            </div>



        </div>




    @endsection
