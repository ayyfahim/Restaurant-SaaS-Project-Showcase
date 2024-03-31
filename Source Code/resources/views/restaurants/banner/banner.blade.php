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
</style>


<div class="container-fluid">

    <div class="card-body">
        @can('view_banner')
            <a class="btn" style="background-color: rgba(211, 0, 0, 1); color: #fff">Banners</a>
        @endcan
        @can('view_discount')
            <a href="{{ route('store_admin.discount') }}" class="btn btn-secondary">Discounts</a>
        @endcan
        @can('view_coupon')
            <a href="{{ route('store_admin.coupon') }}" class="btn btn-secondary">Coupons</a>
        @endcan
    </div>

    <div class="row">

        @foreach ($banner as $ban)
        <div class="col-md-3">

            <div class="card">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <img src="{{ asset($ban->photo_url) }}" width="121" height="121"
                                style="border-radius: 15px">
                        </div>

                        <div class="col-auto text-right">

                            <label class="custom-toggle">
                                <input type="checkbox" disabled {{ $ban->is_visible == 1 ? 'checked' : null }}>
                                <span class="custom-toggle-slider rounded-circle" data-label-off="Off"
                                    data-label-on="On"></span>
                            </label>

                            <br>
                            <h3 style="padding: 5px;">
                                Name: <b>{{ $ban->name }}</b>
                            </h3>

                        </div>


                    </div>


                    <div class="row">
                        <div class="col">




                        </div>

                    </div>
                    <hr>
                    <div class="row" style="margin-top: -18px;">
                        <div class="col">
                            @can('edit_banner')
                            <a href="{{ route('store_admin.banneredit', $ban->id) }}"><b>Edit</b></a>
                            @endcan

                        </div>
                        <div class="col">
                            @can('delete_banner')
                                <a onclick="if(confirm('Are you sure you want to delete this item?')){ event.preventDefault();document.getElementById('delete-form-{{ $ban->id }}').submit(); }"><b style="color: red">Delete</b></a>

                                <form method="post" action="{{ route('store_admin.delete_slider') }}"
                                    id="delete-form-{{ $ban->id }}" style="display: none">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" value="{{ $ban->id }}" name="id">
                                </form>
                            @endcan
                        </div>

                    </div>

                </div>
            </div>
        </div>

        @endforeach
    </div>

</div>
@can('add_banner')
<a href="{{ route('store_admin.addbanner') }}" class="float btn-primary-appetizr">
    <i class="fa fa-plus my-float"></i>
</a>
@endcan


@endsection
