@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')

    <div class="container-fluid">
        <div class="card mb-4">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">Add Discounts</h3>
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
                <form method="post" action="{{ route('store_admin.adddiscount_post') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <!-- Form groups used in grid -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="example3cols2Input">Discount Name</label>
                                <input type="text" name="discount_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="form-control-label" for="example3cols2Input">Discount Description</label>
                                <textarea name="discount_description" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="example3cols2Input">Discount Type</label>
                                <select id="discount_type" name="discount_type" class="form-control" required>
                                    <option value="1" selected>
                                        Fixed
                                    </option>
                                    <option value="2">
                                        Percentage
                                    </option>
                                </select>
                            </div>

                            <div class="form-group d_type_fixed">
                                <label class="form-control-label" for="example3cols2Input">Discount Price</label>
                                <input type="number" name="discount_price_fixed" class="form-control">
                            </div>

                            <div class="form-group d_type_percent" style="display: none;">
                                <label class="form-control-label" for="example3cols2Input">Discount Price (%)</label>
                                <input type="number" name="discount_price_percentage" min="0" max="100" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-12">
                            {{-- Break the cols --}}
                        </div>

                        @php
                            $times_list = times_list();
                        @endphp

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="example3cols2Input">Select
                                    Time Restriction</label>
                                <select name="time_restriction" class="form-control">
                                    <option value="">
                                        No Restriction
                                    </option>
                                    @foreach ($time_restrictions as $restriction)
                                        <option value="{{ $restriction->id }}">
                                            {{ $restriction->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            {{-- Break the cols --}}
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label d-block" for="example3cols2Input">Select
                                    Products</label>
                                @foreach ($products as $product)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox"
                                            id="inlineCheckbox{{ $product->id }}" value="{{ $product->id }}"
                                            name="selected_product_ids[]">
                                        <label class="form-check-label"
                                            for="inlineCheckbox{{ $product->id }}">{{ $product->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label" for="exampleFormControlSelect1">Visibility</label>
                                <div class="col-auto">
                                    <label class="custom-toggle">
                                        <input type="checkbox" name="is_active" checked="">
                                        <span class="custom-toggle-slider rounded-circle" data-label-off="Off"
                                            data-label-on="On"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
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

@section('custom_scripts')
    <script>
        $("#discount_type").change(function() {
            var selected_option = $('#discount_type').val();
            if (selected_option === '2') {
                $(".d_type_percent").each(function(index, element) {
                    $(this).css('display', 'block');
                });
                $(".d_type_fixed").each(function(index, element) {
                    $(this).css('display', 'none');
                });
            }
            if (selected_option === '1') {
                $(".d_type_fixed").each(function(index, element) {
                    $(this).css('display', 'block');
                });
                $(".d_type_percent").each(function(index, element) {
                    $(this).css('display', 'none');
                });
            }
        })

    </script>
@endsection
