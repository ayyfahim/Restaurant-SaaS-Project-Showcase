@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')

<div class="container-fluid">
    <div class="card mb-4">
        <!-- Card header -->
        <div class="card-header">
            <h3 class="mb-0">Edit Coupon</h3>
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
            <form method="post" action="{{ route('store_admin.updatecoupon', $coupon->id) }}"
                enctype="multipart/form-data">
                @method('PATCH')
                {{ csrf_field() }}
                <!-- Form groups used in grid -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Coupon Code</label>
                            <input type="text" name="coupon_code" class="form-control" value="{{ $coupon->code }}"
                                required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Coupon Amount</label>
                            <input type="number" name="coupon_amount" min="0" class="form-control"
                                value="{{ $coupon->fixed_amount }}" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        {{-- Break the cols --}}
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Minimum Spend</label>
                            <input type="number" name="coupon_minimum_spend" min="0" class="form-control"
                                value="{{ $coupon->minimum_spend }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Maximum Spend</label>
                            <input type="number" name="coupon_maximum_spend" min="0" class="form-control"
                                value="{{ $coupon->maximum_spend }}">
                        </div>
                    </div>

                    <div class="col-md-12">
                        {{-- Break the cols --}}
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Coupon Expiry Date</label>
                            <input type="date" name="coupon_epiration" class="form-control"
                                value="{{ $coupon->expires_at->format('Y-m-d') }}" required>
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
                                <option value="{{ $product->id }}"
                                    {{ in_array($product->id, $coupon->accepted_products ?? []) ? 'selected' : '' }}>
                                    {{ $product->name }}</option>
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
                                <option value="{{ $product->id }}"
                                    {{ in_array($product->id, $coupon->excluded_products ?? []) ? 'selected' : '' }}>
                                    {{ $product->name }}</option>
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
                                <option value="{{ $category->id }}"
                                    {{ in_array($category->id, $coupon->accepted_categories ?? []) ? 'selected' : '' }}>
                                    {{ $category->name }}</option>
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
                                <option value="{{ $category->id }}"
                                    {{ in_array($category->id, $coupon->excluded_categories ?? []) ? 'selected' : '' }}>
                                    {{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="example3cols2Input">Limit Per User</label>
                            <input type="number" name="limit_per_user" min="0" class="form-control"
                                value="{{ $coupon->limit_per_user }}" required>
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

@section('custom_styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2 span.select2-selection {
        display: -webkit-box;
        padding: 10px 0;
    }

    .select2-container .select2-search--inline:first-child .select2-search__field {
        width: 100% !important;
    }
</style>
@endsection
@section('custom_scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2({
            placeholder: 'Select an option'
        });
    });
</script>
@endsection
