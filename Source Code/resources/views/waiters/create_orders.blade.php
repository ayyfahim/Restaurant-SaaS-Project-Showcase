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
                                        <input class="form-control" type="number" name="product_count" id="product_count">
                                    </div>
                                    <div class="form-group d-none">
                                        <label class="form-control-label" for="exampleFormControlSelect1">
                                            list
                                        </label>
                                        <input class="form-control" type="text" name="addon_list" id="addon_list">
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
                                    <div class="form-group" id="select_addons">
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

                {{-- <div class="col-md-4">
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

                                            </div> --}}
                {{-- <select class="form-control" name="select_extra" multiple="" id="select_extra">
                                            <option value="">None</option>
                                        </select> --}}
                {{-- </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}

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
                                    @foreach ($cart['addon'] as $addon => $addon_addon)
                                    <span class="badge badge-primary">{{ $addonI++ }}</span>
                                    Name: <strong>{{ $addons->firstWhere('id', $addon)->addon_name }} (
                                        {{ $addons->firstWhere('id', $addon)->price }} )</strong>
                                    x
                                    <strong>{{$addon_addon->count}} </strong> =
                                    <strong>
                                        ${{ $addons->firstWhere('id', $addon)->price }}</strong>
                                        @isset($addon_addon->nested_addons)
                                            @foreach ($addon_addon->nested_addons as $key => $nested_addon)
                                                <div class="ml-4">
                                                    <strong>{{ $addons->firstWhere('id', $key)->addon_name }} (
                                                    {{ $addons->firstWhere('id', $key)->price }} ) </strong> x
                                                    <strong> {{$nested_addon}}</strong> =
                                                    <strong>
                                                        ${{ $addons->firstWhere('id', $key)->price * $nested_addon}}</strong>
                                                </div>
                                            @endforeach
                                        @endisset
                                    <br>
                                    @endforeach
                                </td>
                                <td>{{ $products->firstWhere('id', $cart['product_id'])->price }}
                                    <br>
                                    @foreach ($cart['addon'] as $addon => $addon_addon)
                                        + {{ $addons->firstWhere('id', $addon)->price }}
                                        @isset($addon_addon->nested_addons)
                                        @foreach ($addon_addon->nested_addons as $key => $nested_addon)
                                                <div class="ml-4">
                                                    + {{ $addons->firstWhere('id', $key)->price * $nested_addon}}
                                                </div>
                                            @endforeach
                                        @endisset
                                    <br/>
                                    @endforeach
                                </td>
                                <td>{{ $cart['count'] }}</td>
                                <td class="color-primary">
                                    @php $tot = 0;
                                    $tot += (float)$products->firstWhere('id', $cart['product_id'])->price * $cart['count']
                                    @endphp
                                    @foreach ($cart['addon'] as $addon => $addon_addon)
                                        @php $tot += (float)$addons->firstWhere('id', $addon)->price @endphp
                                         @isset($addon_addon->nested_addons)
                                            @foreach ($addon_addon->nested_addons as $key => $nested_addon)
                                                    @php $tot += $addons->firstWhere('id', $key)->price * $nested_addon @endphp
                                            @endforeach
                                        @endisset
                                    @endforeach
                                    {{ $tot }}
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
                                @include('layouts.render.currency',["amount"=>$tot])
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
                                @include('layouts.render.currency',["amount"=>$tot + $waiter_cart["store_charge"] + $waiter_cart["tax"] - $waiter_cart["discount"]])
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
                                                    <label class="form-control-label" for="exampleFormControlSelect1">
                                                        Select Table
                                                    </label>
                                                    <select class="form-control" name="select_table" id="select_table">
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
                                                <button class="btn btn-success btn-sm mt-2" data-toggle="modal" data-target="#addCustomerModal" type="button">Add
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

<div class="modal fade addCustomerModal" id="addCustomerModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
    $('#select_product').on('change', function() {
        $('#select_addons').empty();
        $('#select_addons').append(` <label class="form-control-label" for="exampleFormControlSelect1">
                Select Addon
            </label>`);
        var value = $('#select_product').val();
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
                    var addon_val = `<div><label>${category.name}:</label><br>`;
                    if (category.type == "SNG") {
                        $.each(category.addons, function(key, addon) {
                            addon_val += `<ol>`;
                            if (category.multi_select == 1) {
                                addon_val += `<input type="radio"  value="${addon.id}" id="addon_id_${addon.id}" onclick="checkAddon('addon_id_${addon.id}','${category.multi_select}')"> `;
                            } else {
                                addon_val += `<input type="radio" id="addon_id_${addon.id}" name="addon" onclick="checkAddon('addon_id_${addon.id}','${category.multi_select}')"> `;
                            }
                            addon_val += `<label for="addon_id_${addon.id}"> ${addon.addon_name} (${addon.price}) </label>`;
                            $.each(addon.nested_addons, (key, nested_addon) => {
                                addon_val += `<div class="nested_addon d-none" id="nested_addon_id_${addon.id}">
                                                <ol>
                                                    <label>${nested_addon.addon_category.name}:</label>`;
                                $.each(nested_addon.addon_category.addons, (key, nested_addon_addon) => {
                                    if (nested_addon.addon_category.type == "SNG") {
                                        addon_val += `<ul>
                                                                <input type="radio" id="addon_id_${nested_addon_addon.id}">
                                                                <label for="addon_id_${nested_addon_addon.id}"> ${nested_addon_addon.addon_name} </label>
                                                            </ul>`;
                                    } else {
                                        addon_val +=
                                            `<div class="cart-items bg-white position-relative mb-3" style="border-bottom: 1px solid rgb(234, 234, 234);">
                                                                <div class="row align-items-center">
                                                                    <div class="col d-flex align-items-center">
                                                                        <h4 class="m-0 mr-1">${nested_addon_addon.addon_name}</h4>
                                                                        <p class="total_price font-weight-bold m-0">(<price> ${nested_addon_addon.price}</price>)</p>
                                                                    </div>
                                                                    <div class="col d-flex align-items-center md-3">
                                                                        <div class="input-group input-spinner  cart-items-number ml-auto">
                                                                            <div class="input-group-append ">
                                                                                <button style="height: 25px !important;" class="btn btn-success btn-sm" type="button" id="button-minus" onclick="updateAddon('sub', 'addon-${nested_addon_addon.id}-${addons.product_id}-${addon.id}')" name="${nested_addon_addon.addon_name}"> − </button>
                                                                            </div>
                                                                            <input type="text" class="form-control addon_count" id="addon-${nested_addon_addon.id}-${addons.product_id}-${addon.id}" readonly="" value="0" style="height: 25px;">
                                                                            <div class="input-group-prepend">
                                                                                <button style="height: 25px !important;" class="btn btn-success btn-sm" type="button" id="button-plus" onclick="updateAddon('add','addon-${nested_addon_addon.id}-${addons.product_id}-${addon.id}')" name="${nested_addon_addon.addon_name}"> + </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>`
                                    }
                                });
                                addon_val += `</ol>
                                            </div>`;
                            });
                            addon_val += `</ol>`;
                        });
                    } else {
                        $.each(category.addons, function(key, addon) {
                            //  addon_val += `<p class="form-control-label mb-0">${addon.addon_name} - ${addon.price}</p> <input class="form-control mb-1" type="number" name="selectExtra-${addon.id}"></input>`
                            addon_val +=
                                `<div class="cart-items bg-white position-relative mb-3" style="border-bottom: 1px solid rgb(234, 234, 234);">
                                        <div class="row align-items-center">
                                            <div class="col d-flex align-items-center">
                                                <h4 class="m-0 mr-1">${addon.addon_name}</h4>
                                                <p class="total_price font-weight-bold m-0">(<price> ${addon.price}</price>)</p>
                                            </div>
                                            <div class="col d-flex align-items-center md-3">
                                                <div class="input-group input-spinner  cart-items-number ml-auto">
                                                    <div class="input-group-append ">
                                                        <button class="btn btn-success btn-sm" type="button" id="button-minus" onclick="checkAddon('addon_id_${addon.id}','sub')""> − </button>
                                                    </div>
                                                    <input type="text" class="form-control" id="addon_id_${addon.id}" name="${addon.addon_name}" readonly="" value="0" style="height: 25px;">
                                                    <div class="input-group-prepend">
                                                        <button class="btn btn-success btn-sm" type="button" id="button-plus" onclick="checkAddon('addon_id_${addon.id}','add')"> + </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`

                            $.each(addon.nested_addons, (key, nested_addon) => {
                                addon_val += `<div class="nested_addon d-none" id="nested_addon_id_${addon.id}">
                                                <ol>
                                                    <label>${nested_addon.addon_category.name}:</label>`;
                                $.each(nested_addon.addon_category.addons, (key, nested_addon_addon) => {
                                    if (nested_addon.addon_category.type == "SNG") {
                                        addon_val += `<ul>
                                                                <input type="radio" id="addon_id_${nested_addon_addon.id}">
                                                                <label for="addon_id_${nested_addon_addon.id}"> ${nested_addon_addon.addon_name} </label>
                                                            </ul>`;
                                    } else {
                                        addon_val +=
                                            `<div class="cart-items bg-white position-relative mb-3" style="border-bottom: 1px solid rgb(234, 234, 234);">
                                                                <div class="row align-items-center">
                                                                    <div class="col d-flex align-items-center">
                                                                        <h4 class="m-0 mr-1">${nested_addon_addon.addon_name}</h4>
                                                                        <p class="total_price font-weight-bold m-0">(<price> ${nested_addon_addon.price}</price>)</p>
                                                                    </div>
                                                                    <div class="col d-flex align-items-center md-3">
                                                                        <div class="input-group input-spinner  cart-items-number ml-auto">
                                                                            <div class="input-group-append ">
                                                                                <button style="height: 25px !important;" class="btn btn-success btn-sm" type="button" id="button-minus" onclick="updateAddon('sub', 'addon-${nested_addon_addon.id}-${addons.product_id}-${addon.id}')" name="${nested_addon_addon.addon_name}"> − </button>
                                                                            </div>
                                                                            <input type="text" class="form-control addon_count" id="addon-${nested_addon_addon.id}-${addons.product_id}-${addon.id}" readonly="" value="0" style="height: 25px;">
                                                                            <div class="input-group-prepend">
                                                                                <button style="height: 25px !important;" class="btn btn-success btn-sm" type="button" id="button-plus" onclick="updateAddon('add','addon-${nested_addon_addon.id}-${addons.product_id}-${addon.id}')" name="${nested_addon_addon.addon_name}"> + </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>`
                                    }
                                });
                                addon_val += `</ol>
                                            </div>`;
                            });
                        });
                    }
                    addon_val += `</div>`;
                    $('#select_addons').append(addon_val);
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

        // clearTimeout(timeout);

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

                if (data.payload.customers.length == 0) {
                    document.getElementById('customers_list').innerHTML = `No user found`;
                }

                $.each(data.payload.customers, function(i, customer) {
                    document.getElementById('customers_list').innerHTML = ''
                    document.getElementById('customers_list').innerHTML +=
                        `<div class="customer" data-id="${customer.id}"> <b>Name:</b> ${customer.first_name} ${customer.last_name}, <b>Email:</b> ${customer.email ?? null}, <b>Phone:</b> ${customer.phone ?? null} </div>`;
                });
            });

            request.fail(function(jqXHR, textStatus) {
                document.getElementById('customers_list').innerHTML = `No user found`;
            });

        }, 1000);
    });

    $(document).on("click", "body .customer", function() {
        let id = $(this).data("id");

        let customer = users.filter(data => data.id == id);

        $('#add_customer').append($('<option>', {
            value: customer[0].id,
            text: `${customer[0].first_name} ${customer[0].last_name}`
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
                console.log('customer',customer);
                if (customer) {
                    $('#add_customer').append($('<option>', {
                        value: customer.id,
                        text: `${customer.full_name}`
                    }));
                }
            });
        });

    });
</script>

<script>
    let data_addons = {};
    function updateAddon(operation, id) {

        const split_id = id.split("-");
        const nested_addon_id = split_id[1];
        const addon_id = split_id[3];
        // let nested_addon = `nested_addon_id_${addon_id}`;

        if (operation == 'add') {

            $(`#${id}`).val(parseInt($(`#${id}`).val()) + 1);
            // $(`#${nested_addon}`).removeClass('d-none');

        } else if (operation == 'sub') {

            if ($(`#${id}`).val() > 0) {
                $(`#${id}`).val(parseInt($(`#${id}`).val()) - 1);
                // $(`#${nested_addon}`).addClass('d-none');
            }

        }

        if(!data_addons[addon_id]['nested_addons'] && $(`#${id}`).val() > 0 ){

            data_addons[addon_id]['nested_addons'] = {};

        }

        data_addons[addon_id]['nested_addons'][nested_addon_id] = $(`#${id}`).val();
        $('#addon_list').val(JSON.stringify(data_addons));
    }

    function checkAddon(id, multi_select) {

        let only_id = id.replace('addon_id_','');

        if (multi_select == 1) {
            if ($(`#${id}`).attr('checked')) {
                $(`#${id}`).removeAttr('checked');
                $(`#${id}`).prop('checked', false);
                $(`#nested_${id}`).addClass('d-none');
                delete data_addons[only_id];
            } else {
                $(`#${id}`).attr('checked', 'checked');
                $(`#nested_${id}`).removeClass('d-none');
                data_addons[only_id]={count:1}
            }

        } else {
            if ($(`#${id}`).attr('checked')) {
                $(`#nested_${id}`).addClass('d-none');
            } else {
                $('.nested_addon').addClass('d-none');
                $('.addon_count').val("0");
                data_addons = {};
                $(`#nested_${id}`).removeClass('d-none');
                data_addons[only_id]={count:1}
            }
        }
        $('#addon_list').val(JSON.stringify(data_addons));
    }
</script>
@endsection
