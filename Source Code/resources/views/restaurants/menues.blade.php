@extends("restaurants.layouts.restaurantslayout")

@section("restaurantcontant")

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
</style>




<div class="container-fluid">

    <div class="card-body text-center scrolling-wrapper col-md-6 mx-auto pos-menu">
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
            <a href="{{ route('store_admin.addon_categories') }}" class="btn btn-secondary">Addon Categories</a>
        @endcan
        @can('view_timerestrictions')
            <a href="{{route('store_admin.timerestrictions')}}" class="btn btn-secondary mt-2">Time Restrictions</a>
        @endcan
        @can('view_food_menues')
            <button class="btn mt-2" style="background-color: rgba(211, 0, 0, 1); color: #fff">Food Menues</button>
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
                    <h3 class="mb-0">Food Menues
                        <span class="badge badge-md badge-circle badge-floating badge-gray border-white">{{ $menu_count
                            }}</span>
                    </h3>


                </div>
                <div class="col-6 text-right">
                    @can('add_food_menues')
                    <a href="#" class="btn btn-sm btn-primary btn-primary-appetizr btn-round btn-icon"
                        data-toggle="modal" data-target="#modal-form">
                        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                        <span class="btn-inner--text">Add Food Menu</span>
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
                        <th>Name</th>
                        <th>Active?</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    @php $i=1 @endphp

                    @foreach ($menues as $menue)
                    <tr>
                        <td>{{ $i++ }}</td>

                        <td>{{ $menue->name }}</td>
                        <td>{{ $menue->is_active == 1 ? 'Yes' : 'No' }}</td>
                        <td style="text-align: left">
                            @can('edit_food_menues')
                            <a href="{{ route('store_admin.update_menues', $menue->id) }}" title="edit"
                                class="btn btn-sm btn-primary">
                                Edit
                            </a>
                            @endcan
                            @can('delete_food_menues')
                            <button class="btn btn-sm btn-danger text-white"
                                onclick="if(confirm('Are you sure you want to delete this item?')){ event.preventDefault();document.getElementById('delete-form-{{ $menue->id }}').submit(); }"><b>Delete</b></button>
                            <form method="post" action="{{ route('store_admin.delete_menu') }}"
                                id="delete-form-{{ $menue->id }}" style="display: none">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" value="{{ $menue->id }}" name="id">
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
                            <small>Add Food Menu</small>
                        </div>

                        <form method="post" action="{{ route('store_admin.add_menues') }}"
                            enctype="multipart/form-data">

                            {{ csrf_field() }}

                            <div class="form-group mb-3">
                                <label class="form-control-label" for="exampleFormControlSelect1">Name</label>
                                <div class="input-group input-group-merge input-group-alternative">
                                    <input class="form-control" placeholder="Name" type="text" name="name" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-control-label" for="exampleFormControlSelect1">Is Enabled</label>
                                <select class="form-control" name="is_active" required>
                                    <option value="1">Enabled</option>
                                    <option value="0">Disabled</option>
                                </select>
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


</div>
@can('add_food_menues')
<a href="{{route('store_admin.addcategories')}}" class="float btn-primary-appetizr">
    <i class="fa fa-plus my-float"></i>
</a>
@endcan

@endsection
