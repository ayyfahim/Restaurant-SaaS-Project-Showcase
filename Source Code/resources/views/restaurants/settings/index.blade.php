@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')


<div class="container-fluid">

    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="left-side-tabs">
                @include('restaurants.settings.settings_sidebar')
                <!-- <div class="dashboard-left-links">
                    @can('store_settings')
                        <a href="#" class="user-item active">Store Settings</a>
                    @endcan
                    @can('add_open_hours')
                        <a href="{{ route('store_admin.add_open_hours') }}" class="user-item">Add Open Hours</a>
                    @endcan
                    @can('add_bank_details')
                        <a href="{{ route('store_admin.add_bank_details') }}" class="user-item">Add Bank Details</a>
                    @endcan
                    @can('deliverect_setting')
                        <a href="{{ route('store_admin.add_deliverect') }}" class="user-item">Deliverect</a>
                    @endcan
                    @can('add_store_location')
                        <a href="{{ route('store_admin.add_store_location') }}" class="user-item">Add Store Location</a>
                    @endcan
                </div> -->
            </div>
        </div>
        <div class="col-lg-8 col-md-6">
            <div class="card card-static-2 mb-30">
                <div class="card-title-2">
                    <h4>Site Setting</h4>
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
                        action="{{ route('store_admin.update_store_settings') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">Store Name</label>
                                        <input type="text" name="store_name" class="form-control"
                                            value="{{ $store->store_name }}" placeholder="Store Name" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-email">Email address</label>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ $store->email }}" placeholder="Email address" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">New Password</label>
                                        <input type="password" name="password" class="form-control"
                                            placeholder="New Password">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-first-name"> Phone Number(with
                                            country code:Eg:+33)</label>
                                        <input type="text" class="form-control" value="{{ $store->phone }}"
                                            placeholder="Phone Number" name="phone" required />
                                    </div>
                                </div>

                            </div>
                            <div class="row">


                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-first-name">Subscription end
                                            date</label>
                                        <input type="text" class="form-control"
                                            value="{{ $store->subscription_end_date }}" readonly disabled />
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Store Logo (300px X 300px)</label>
                                        <input type="file" name="logo_url" class="form-control ui-autocomplete-input"
                                            placeholder="Application Logo ()" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Store Logo Wide (650px X 250px)</label>
                                        <input type="file" name="logo_url_wide"
                                            class="form-control ui-autocomplete-input" placeholder="Application Logo ()"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="pl-lg-4">
                            <div class="form-group">
                                <label class="form-control-label">Address</label>
                                <textarea rows="4" name="address" class="form-control">{{ $store->address }}</textarea>
                            </div>
                        </div>

                        <div class="pl-lg-4">
                            <div class="form-group">
                                <label class="form-control-label">Description</label>
                                <textarea rows="4" name="description"
                                    class="form-control">{{ $store->description }}</textarea>
                            </div>
                        </div>
                        <h6 class="heading-small mb-4">Extra Settings</h6>

                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-first-name">Store Currency (USD,
                                            EUR, KRW, etc.)</label>
                                        <input type="text" name="currency_symbol" class="form-control"
                                            value="{{ $store->currency_symbol ?? '' }}" />
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-first-name">Store Service
                                            Charge</label>
                                        <input type="text" name="service_charge" class="form-control"
                                            value="{{ $store->service_charge ?? '' }}" />
                                    </div>
                                </div>
                                {{-- <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-first-name">Store Tax(%)</label>
                                            <input type="text" name="tax" class="form-control"
                                                value="{{ $store->tax ?? '' }}" />
                            </div>
                        </div> --}}
                </div>


            </div>




            <h6 class="heading-small mb-4">Order Settings</h6>
            <div class="pl-lg-4">
                <div class="row">


                    <div class="col-lg-6">
                        <div class="form-group">

                            <label class="form-control-label">Accept order : On/Off</label><br>
                            <label class="custom-toggle">
                                <input type="checkbox" {{ $store->is_accept_order ? 'checked' : null }}
                                    name="is_accept_order">
                                <span class="custom-toggle-slider rounded-circle" data-label-off="No"
                                    data-label-on="Yes"></span>
                            </label>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-control-label" for="input-first-name">Order Limit</label>
                            <input type="number" name="order_limit" class="form-control"
                                value="{{ $store->order_limit ?? 20 }}" />
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">

                            <label class="form-control-label">Auto Accept Orders : On/Off</label><br>
                            <label class="custom-toggle">
                                <input type="checkbox" {{ $store->auto_accept_order ? 'checked' : null }}
                                    name="auto_accept_order">
                                <span class="custom-toggle-slider rounded-circle" data-label-off="No"
                                    data-label-on="Yes"></span>
                            </label>
                        </div>
                    </div>


                </div>
            </div>

            @can('view_takeaway')
            <h6 class="heading-small mb-4">Takeway Settings</h6>
            <div class="pl-lg-4">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Accept Takeway Orders : On/Off</label><br>
                            <label class="custom-toggle">
                                <input type="checkbox" {{ $store->is_takeway_order ? 'checked' : null }}
                                    name="is_takeway_order" {{ (!auth()->user()->can('update_takeaway')) ? 'disabled=disabled' : '' }}>
                                <span class="custom-toggle-slider rounded-circle" data-label-off="No"
                                    data-label-on="Yes"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pl-lg-4">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Prepay Takeway Orders : On/Off</label><br>
                            <label class="custom-toggle">
                                <input type="checkbox" {{ $store->pay_first ? 'checked' : null }}
                                    name="pay_first" {{ (!auth()->user()->can('update_takeaway')) ? 'disabled=disabled' : '' }}>
                                <span class="custom-toggle-slider rounded-circle" data-label-off="No"
                                    data-label-on="Yes"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            @endcan

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
