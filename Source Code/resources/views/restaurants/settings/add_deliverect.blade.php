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
                        <a href="{{ route('store_admin.add_bank_details') }}" class="user-item">Add Bank
                            Details</a>
                        <a href="{{ route('store_admin.add_deliverect') }}" class="user-item active">Deliverect</a>
                    </div> -->
                </div>
            </div>
            <div class="col-lg-8 col-md-6">
                <div class="card card-static-2 mb-30">
                    <div class="card-title-2">
                        <h4>Add Deliverect Account</h4>

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
                            action="{{ route('store_admin.update_deliverect_details') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <h6 class="heading-small mb-4">Deliverect Account Details</h6>
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">API Key</label>
                                            <input type="text" name="deliverect_api_key" class="form-control"
                                                value="{{ $store->deliverect_api_key ?? '' }}" placeholder="API Key">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">API Secret Key</label>
                                            <input type="text" name="deliverect_api_secret_key" class="form-control"
                                                value="{{ $store->deliverect_api_secret_key ?? '' }}" placeholder="API Secret Key">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Webhook URL</label>
                                            <input type="text" name="deliverect_webhook_url" class="form-control"
                                                value="{{ $store->deliverect_webhook_url ?? '' }}" placeholder="Webhook URL">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Product Sync URL</label>
                                            <input type="text" name="product_sync_url" class="form-control"
                                                value="{{ config('app.url') }}/api/product-sync/{{ $store->id }}" placeholder="Product Sync URL" disabled>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Channel Link ID</label>
                                            <input type="text" name="deliverect_channel_link_id" class="form-control"
                                                value="{{ $store->deliverect_channel_link_id ?? '' }}" placeholder="Channel Link ID">
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
