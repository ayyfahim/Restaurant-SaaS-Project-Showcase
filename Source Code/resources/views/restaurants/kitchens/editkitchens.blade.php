@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')


    <div class="container-fluid">
        <div class="card mb-4">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">Edit Waiter</h3>
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
                <form method="post" action="{{ route('store_admin.editkitchens_post', $kitchen->id) }}"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    @method('PATCH')
                    <!-- Form groups used in grid -->
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="example3cols2Input">Kitchen Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $kitchen->name }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="example3cols2Input">Kitchen Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $kitchen->email }}"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="example3cols2Input">Kitchen Phone (optional)</label>
                                <input type="text" name="phone" class="form-control" value="{{ $kitchen->phone }}"
                                    >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-check">
                                <input type="checkbox" name="is_main" class="form-check-input" id="mainKitchen"
                                    {{ $kitchen->is_main ? 'checked' : '' }}>
                                <label class="form-control-label" for="mainKitchen">Is Main Kitchen?</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <button class="btn btn-primary btn-primary-appetizr" type="submit">Submit</button>
                                <a href="{{ route('store_admin.changePassword', $kitchen->id) }}" class="btn btn-primary">Change Password</a>
                            </div>
                        </div>


                    </div>

                </form>
            </div>



        </div>




    @endsection
