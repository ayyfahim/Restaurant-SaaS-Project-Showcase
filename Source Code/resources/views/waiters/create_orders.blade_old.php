@extends("waiters.layouts.waiterslayout")

@section('waiterscontent')



    <div class="container-fluid">
        <div class="tab-pane fade show active" id="home2" role="tabpanel" aria-labelledby="home-tab">
            <form action="{{ route('waiter_admin.add_to_cart') }}" method="post">
                <div class="row">

                    <div class="col-md-4">
                        <div class="card">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="form-control-label" for="exampleFormControlSelect1">
                                                Select Product
                                            </label>
                                            <select class="form-control" name="select_product" id="select_product">
                                                <option value="">Choose a product</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label" for="exampleFormControlSelect1">
                                                Product Count
                                            </label>
                                            <input class="form-control" type="number" name="product_count"
                                                id="product_count">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="form-control-label" for="exampleFormControlSelect1">
                                                Select Addon
                                            </label>
                                            {{-- <select class="form-control" name="select_addon[]" multiple=""
                                                id="select_addon" aria-placeholder="None"> --}}
                                                {{-- <option value="">None</option> --}}
                                            {{-- </select> --}}
                                                {{-- @foreach ($products as $product)
                                                    @foreach ($product->addonItems as $addon_category)
                                                        @foreach ($addon_category->categories as $category)
                                                        {{$category->name}}
                                                        @endforeach
                                                    @endforeach
                                                @endforeach --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="form-control-label" for="exampleFormControlSelect1">
                                                Select Extra
                                            </label>
                                            <div id="select_extra_div">

                                            </div>
                                            {{-- <select class="form-control" name="select_extra" multiple="" id="select_extra">
                                            <option value="">None</option>
                                        </select> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        @csrf
                        <button class="btn btn-success" type="submit">Add to Cart</button>
                    </div>

                </div>
            </form>

            @if ($waiter_cart = session()->get('waiter_cart'))
                <div class="row mt-5">
                    <div class="col-md-8">
                        <div class="table-responsive bg-white">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Item Price</th>
                                        <th>Qty</th>
                                        <th>Total Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach (session()->get('waiter_cart')['cart'] as $cart)
                                        <tr>
                                            <th scope="row">{{ $i++ }}</th>

                                            <td><b>{{ $products->firstWhere('id', $cart['product_id'])->name }}</b><br>
                                                @php
                                                    $addonI = 1;
                                                @endphp
                                                {{-- {{dd($cart)}} --}}
                                                @foreach ($cart['addon'] as $addon)
                                                    <span class="badge badge-primary">{{ $addonI++ }}</span>
                                                    Name: <strong>{{ $addons->firstWhere('id', $addon)->addon_name }} (
                                                        {{ $addons->firstWhere('id', $addon)->price }} )</strong>
                                                    x
                                                    <strong> 1</strong> =
                                                    <strong>
                                                        ${{ $addons->firstWhere('id', $addon)->price }}</strong>
                                                    <br>
                                                @endforeach
                                                @foreach ($cart['extra'] as $extra)
                                                    <span class="badge badge-primary">{{ $addonI++ }}</span>
                                                    Name:
                                                    <strong>{{ $addons->firstWhere('id', $extra['id'])->addon_name }}
                                                        (
                                                        {{ $addons->firstWhere('id', $extra['id'])->price }} )</strong>
                                                    x
                                                    <strong> {{ $extra['count'] }} </strong> =
                                                    <strong>
                                                        ${{ $extra['count'] * $addons->firstWhere('id', $extra['id'])->price }}</strong>
                                                    <br>
                                                @endforeach
                                            </td>
                                            <td>{{ $products->firstWhere('id', $cart['product_id'])->price }}
                                            </td>
                                            <td>{{ $cart['count'] }}</td>
                                            <td class="color-primary">
                                                {{ $products->firstWhere('id', $cart['product_id'])->price * $cart['count'] }}
                                            </td>


                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <table class="table table-bordered table-striped bill-calc-table bg-white">

                            <tbody>
                                <tr>
                                    <td class="text-left td-title">SubTotal</td>
                                    <td class="td-data">
                                        @include('layouts.render.currency',["amount"=>$waiter_cart["sub_total"]])
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-left td-title">Service Charge</td>
                                    <td class="td-data">
                                        @include('layouts.render.currency',["amount"=>$waiter_cart["store_charge"]])
                                    </td>
                                </tr>


                                <tr>
                                    <td class="text-left td-title">Tax</td>
                                    <td class="td-data">
                                        @include('layouts.render.currency',["amount"=>$waiter_cart["tax"]])
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-left td-title">Discount</td>
                                    <td class="td-data">-
                                        (@include('layouts.render.currency',["amount"=>$waiter_cart["discount"]]))
                                </tr>


                                <tr>
                                    <td class="text-left td-title"><b>TOTAL</b></td>
                                    <td class="td-data">
                                        @include('layouts.render.currency',["amount"=>$waiter_cart["total"]])
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-12 mt-5">
                        <form action="{{ route('waiter_admin.create_order') }}" method="post">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card">
                                        <!-- Card body -->
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <form action="#" method="post">
                                                        <div class="form-group">
                                                            <label class="form-control-label"
                                                                for="exampleFormControlSelect1">
                                                                Select Table
                                                            </label>
                                                            <select class="form-control" name="select_table"
                                                                id="select_table">
                                                                <option value="">Choose a table</option>
                                                                @foreach ($tables as $table)
                                                                    <option value="{{ $table->id }}">
                                                                        {{ $table->table_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card">
                                        <!-- Card body -->
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    {{-- <form action="#" method="post"> --}}
                                                    <div class="form-group mb-0">
                                                        <label class="form-control-label" for="exampleFormControlSelect1">
                                                            Select a customer
                                                        </label>
                                                        <select class="form-control" name="add_customer" id="add_customer">
                                                            <option value="">None</option>
                                                        </select>
                                                        <button class="btn btn-success btn-sm mt-2" data-toggle="modal"
                                                            data-target="#addCustomerModal" type="button">Add
                                                            Customer</button>
                                                    </div>
                                                    {{-- </form> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    @csrf
                                    <button class="btn btn-success" type="submit">Create Order</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <div class="modal fade addCustomerModal" id="addCustomerModal" data-backdrop="static" data-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- <h4>Please type email, phone-number, or name of the customer</h4> --}}
                    <div class="form-group">
                        <label class="form-control-label" for="exampleFormControlSelect1">
                            Please type email, phone-number, or name of the customer
                        </label>
                        <input type="text" class="form-control" id="add_customer_input">
                    </div>

                    <div id="customers_list">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('custom_scripts')
    <script>
        var timeout = null;
        var users = {};

        $(document).ready(function() {
            var value = $("#select_product").val();

            document.getElementById('select_addon').innerHTML =
                `<option value=''>None</option>`;
            document.getElementById('select_extra_div')
                .innerHTML =
                ``;

            var request = $.ajax({
                url: "{{ route('waiter_admin.waiter_get_product_details') }}",
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    product_id: value
                },
            });

            request.done(function(data) {
                $.each(data.addon_items, function(i, addons) {
                    $.each(addons.categories, function(key, category) {

                        var category_name = category.name;
                        // alert(key + val);

                        if (category.type == "SNG") {

                            // console.log(`sng`, category)

                            $.each(category.addons, function(key, addon) {

                                $('#select_addon').append($('<option>', {
                                    value: addon.id,
                                    text: `${addon.addon_name} - ${addon.price} (${category_name})`
                                }));
                            });

                        } else {
                            $.each(category.addons, function(key, addon) {
                                document.getElementById('select_extra_div')
                                    .innerHTML +=
                                    `<p class="form-control-label mb-0">${addon.addon_name} - ${addon.price}</p>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <input class="form-control mb-1" type="number" name="selectExtra-${addon.id}"></input>`;
                            });

                        }
                    });
                });
            });
        });

        $("#select_product").change(function() {

            var value = $(this).val();

            document.getElementById('select_addon').innerHTML =
                `<option value=''>None</option>`;
            document.getElementById('select_extra_div')
                .innerHTML =
                ``;

            var request = $.ajax({
                url: "{{ route('waiter_admin.waiter_get_product_details') }}",
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    product_id: value
                },
            });

            request.done(function(data) {
                $.each(data.addon_items, function(i, addons) {
                    $.each(addons.categories, function(key, category) {

                        var category_name = category.name;
                        // alert(key + val);

                        if (category.type == "SNG") {

                            // console.log(`sng`, category)

                            $.each(category.addons, function(key, addon) {

                                $('#select_addon').append($('<option>', {
                                    value: addon.id,
                                    text: `${addon.addon_name} - ${addon.price} (${category_name})`
                                }));
                            });

                        } else {
                            $.each(category.addons, function(key, addon) {
                                document.getElementById('select_extra_div')
                                    .innerHTML +=
                                    `<p class="form-control-label mb-0">${addon.addon_name} - ${addon.price}</p>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <input class="form-control mb-1" type="number" name="selectExtra-${addon.id}"></input>`;
                            });

                        }
                    });
                });
            });

            request.fail(function(jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });
        });


        $("#add_customer_input").keyup(function() {

            document.getElementById('customers_list').innerHTML = ``;

            var value = $(this).val();

            clearTimeout(timeout);

            timeout = setTimeout(() => {
                var request = $.ajax({
                    url: "{{ route('waiter_admin.getCustomerDetails') }}",
                    type: "POST",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        value: value
                    },
                });

                request.done(function(data) {
                    users = data.payload.customers;
                    console.log(`data`, data)

                    if (data.payload.customers.length == 0) {
                        document.getElementById('customers_list').innerHTML = `No user found`;
                    }

                    $.each(data.payload.customers, function(i, customer) {
                        document.getElementById('customers_list').innerHTML +=
                            `<div class="customer" data-id="${customer.id}">
                                                                                                                                                                                <b>Name:</b> ${customer.name ?? null}, <b>Email:</b> ${customer.email ?? null}, <b>Phone:</b> ${customer.phone ?? null}
                                                                                                                                                                            </div>`;
                    });
                });

                request.fail(function(jqXHR, textStatus) {
                    document.getElementById('customers_list').innerHTML = `No user found`;
                    console.log(`Request failed:`, textStatus)
                });

            }, 1000);
        });

        $(document).on("click", "body .customer", function() {
            let id = $(this).data("id");

            let customer = users.filter(data => data.id == id);

            console.log(`customer`, customer)

            $('#add_customer').append($('<option>', {
                value: customer[0].id,
                text: `${customer[0].name}`
            }));

            $('#addCustomerModal').modal('hide')

        });

        $("#select_table").change(function() {

            var value = $(this).val();

            var request = $.ajax({
                url: "{{ route('waiter_admin.fetchTableOrderUsers') }}",
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    table_no: value
                },
            });

            request.done(function(data) {

                $.each(data.payload.customers, function(i, customer) {
                    if (customer) {
                        $('#add_customer').append($('<option>', {
                            value: customer.id,
                            text: `${customer.name}`
                        }));
                    }
                });
            });

        });

    </script>
@endsection
