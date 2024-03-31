@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')

<style>
    .float {
        position: fixed;
        width: 60px;
        height: 60px;
        bottom: 40px;
        right: 40px;
        background-color: #322A7D;
        color: #FFA101;
        border-radius: 50px;
        text-align: center;
        box-shadow: 2px 2px 3px #999;
    }


    .my-float {
        margin-top: 22px;
    }

    @media (max-width: 575.98px) {
        .card-body.menu {
            text-align: center;
        }

        .card-body.menu .btn {
            margin-top: 15px;
        }
    }
</style>







<div class="container-fluid">


    <div class="card-body menu scrolling-wrapper col-md-6 mx-auto pos-menu">
        @can('view_categories')
            <a class="btn btn-secondary mt-2" href="{{route('store_admin.categories')}}">Category</a>
        @endcan
        @can('view_products')
            <a class="btn btn-secondary" href="{{ route('store_admin.products') }}">Products</a>
        @endcan
        @can('view_setmenus')
            <a class="btn btn-secondary mt-2" href="{{route('store_admin.setmenus')}}">Set Menus</a>
        @endcan
        @can('view_addon_categories')
            <button class="btn" style="background-color: rgba(211, 0, 0, 1); color: #fff">Addon Categories</button>
        @endcan
        @can('view_timerestrictions')
            <a href="{{route('store_admin.timerestrictions')}}" class="btn btn-secondary mt-2">Time Restrictions</a>
        @endcan
        @can('view_food_menues')
            <a href="{{route('store_admin.menues')}}" class="btn btn-secondary mt-2">Food Menues</a>
        @endcan
    </div>




    <div class="card">
        <!-- Card header -->



        @if (session()->has('MSG'))
        <div class="card-header border-0">
            <div class="alert alert-{{ session()->get('TYPE') }}">
                <strong> <a>{{ session()->get('MSG') }}</a></strong>
            </div>
        </div>
        @endif
        @if ($errors->any()) @include('admin.admin_layout.form_error') @endif




        <div class="card-header border-0">
            <div class="row">
                <div class="col-6">
                    <h3 class="mb-0">Addon Categories
                        <span class="badge badge-md badge-circle badge-floating badge-gray border-white">{{
                            $addons_count }}</span>
                    </h3>
                </div>
                <div class="col-6 text-right">
                    @can('add_addon_categories')
                    <a href="#" class="btn btn-sm btn-primary btn-primary-appetizr btn-round btn-icon"
                    data-toggle="modal" data-target="#modal-form">
                    <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                    <span class="btn-inner--text">Add Addon Category</span>
                    </a>
                @endcan

                </div>
            </div>
        </div>
        <!-- Light table -->
        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Category ID</th>
                        <th>Name</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    @php $i=1 @endphp
                    @foreach ($addons as $addon)
                    <tr>
                        <td>{{ $i++ }}</td>

                        <td>{{ $addon->id }}</td>
                        <td>{{ $addon->name }}</td>
                        <td style="text-align: center">
                            @can('edit_addon_categories')
                            <a href="{{ route('store_admin.addon_categories_edit', $addon->id) }}" title="edit"
                                class="btn btn-sm btn-primary">
                                Edit
                            </a>
                            @endcan
                            @can('delete_addon_categories')
                                <button class="btn btn-sm btn-danger text-white"
                                onclick="if(confirm('Are you sure you want to delete this item?')){ event.preventDefault();document.getElementById('delete-form-{{ $addon->id }}').submit(); }"><b>Delete</b></button>
                                <form method="post" action="{{ route('store_admin.delete_addoncategories') }}"
                                id="delete-form-{{ $addon->id }}" style="display: none">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" value="{{ $addon->id }}" name="id">
                                </form>
                            @endcan

                        </td>







                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>


</div>


<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card bg-secondary border-0 mb-0">

                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <small>Addon Category</small>
                        </div>
                        <form method="post" action="{{ route('store_admin.addaddoncategories') }}"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group mb-3">
                                <label class="form-control-label" for="name">Name</label>
                                <div class="input-group input-group-merge input-group-alternative">
                                    <input class="form-control" placeholder="Name" type="text" name="name" required>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-control-label" for="sku">Name Product SKU (PLU)</label>

                                <input class="form-control" placeholder="Product SKU (PLU)" type="text" name="sku">

                            </div>
                            <div class="form-group mb-3">
                                <label class="form-control-label" for="exampleFormControlSelect1">Select Category Type</label>
                                <select name="type" class="form-control" required>
                                    <option value="SNG">Checkbox</option>
                                    <option value="EXT">Extra</option>
                                    {{-- <option value="MULT">Single Select</option> --}}
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-control-label" for="exampleFormControlSelect1">Minimum Amount</label>
                                <input class="form-control" placeholder="Minimum Amount" type="number"
                                    name="minimum_amount" value="0" required>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-control-label" for="exampleFormControlSelect1">Maximum Amount</label>
                                <input class="form-control" placeholder="Maximum Amount" type="number"
                                    name="maximum_amount" value="0" required>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-primary-appetizr my-4">ADD</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
