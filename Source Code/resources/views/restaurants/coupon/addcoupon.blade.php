@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')

<div class="container-fluid">
    <div class="card mb-4">
        <!-- Card header -->
        <div class="card-header">
            <h3 class="mb-0">Add Coupon</h3>
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
            <form method="post" action="{{ route('store_admin.addcoupon_post') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <!-- Form groups used in grid -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Coupon Code</label>
                            <input type="text" name="coupon_code" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Coupon Amount</label>
                            <input type="number" name="coupon_amount" min="0" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        {{-- Break the cols --}}
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Minimum Spend</label>
                            <input type="number" name="coupon_minimum_spend" min="0" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Maximum Spend</label>
                            <input type="number" name="coupon_maximum_spend" min="0" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-12">
                        {{-- Break the cols --}}
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Coupon Expiry Date</label>
                            <input type="date" name="coupon_epiration" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        {{-- Break the cols --}}
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label d-block" for="example3cols2Input">Select
                                Products</label>
                            <select class="js-example-basic-multiple form-control w-100" name="accepted_products[]"
                                multiple="multiple" style="width: 100%; height: 50px !important;">
                                @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label d-block" for="example3cols2Input">Exclude
                                Products</label>
                            <select class="js-example-basic-multiple form-control w-100" name="excluded_products[]"
                                multiple="multiple" style="width: 100%; height: 50px !important;">
                                @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label d-block" for="example3cols2Input">Select
                                Categories</label>
                            <select class="js-example-basic-multiple form-control w-100" name="accepted_categories[]"
                                multiple="multiple" style="width: 100%; height: 50px !important;">
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label d-block" for="example3cols2Input">Exclude
                                Categories</label>
                            <select class="js-example-basic-multiple form-control w-100" name="excluded_categories[]"
                                multiple="multiple" style="width: 100%; height: 50px !important;">
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Limit Per User</label>
                            <input type="number" name="limit_per_user" min="0" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <button class="btn btn-primary btn-primary-appetizr" type="submit">Submit form</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

@endsection
