@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')


    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="left-side-tabs">
                    @include('restaurants.settings.settings_sidebar')
                    <!-- <div class="dashboard-left-links">
                        <a href="{{ route('store_admin.settings') }}" class="user-item">Store Settings</a>
                        <a href="{{ route('store_admin.add_open_hours') }}" class="user-item">Add Open Hours</a>
                        <a href="{{ route('store_admin.add_bank_details') }}" class="user-item active">Add Bank
                            Details</a>
                            <a href="{{ route('store_admin.add_deliverect') }}" class="user-item">Deliverect</a>
                    </div> -->
                </div>
            </div>
            <div class="col-lg-8 col-md-6">
                <div class="card card-static-2 mb-30">
                    <div class="card-title-2">
                        <h4>Add Bank Details</h4>

                    </div>

                    <div class="card-body">
                        @if (session()->has('MSG'))
                            <div class="alert alert-{{ session()->get('TYPE') }}">
                                <strong> <a>{{ session()->get('MSG') }}</a></strong>
                            </div>
                        @endif
                        @if ($errors->any()) @include('admin.admin_layout.form_error')
                        @endif

                        <form class="form-horizontal" method="post"
                            action="{{ route('store_admin.update_bank_details') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <h6 class="heading-small mb-4">Bank Details</h6>
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Bank Name</label>
                                            <input type="text" name="name_of_bank" class="form-control"
                                                value="{{ $bankDetail->name_of_bank ?? '' }}" placeholder="Bank Name">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">IBAN</label>
                                            <input type="text" name="iban" class="form-control"
                                                value="{{ $bankDetail->iban ?? '' }}" placeholder="IBAN">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">BIC</label>
                                            <input type="text" name="bic" class="form-control"
                                                value="{{ $bankDetail->bic ?? '' }}" placeholder="BIC">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Account Holder
                                                Name</label>
                                            <input type="text" name="account_holder_name" class="form-control"
                                                value="{{ $bankDetail->account_holder_name ?? '' }}"
                                                placeholder="Account Holder Name">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pl-lg-4">
                                <div class="form-group row">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit"
                                            class="btn btn-default btn-flat m-b-30 m-l-5 bg-primary border-none m-r-5 -btn">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>





@endsection
