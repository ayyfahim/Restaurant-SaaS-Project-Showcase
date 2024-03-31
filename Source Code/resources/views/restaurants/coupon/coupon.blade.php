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
        <a href="{{ route('store_admin.banner') }}" class="btn btn-secondary">Banners</a>
        @endcan
        @can('view_discount')
        <a href="{{ route('store_admin.discount') }}" class="btn btn-secondary">Discounts</a>
        @endcan
        @can('view_coupon')
        <a class="btn" style="background-color: rgba(211, 0, 0, 1); color: #fff">Coupons</a>
        @endcan
    </div>

    <div class="card">
        <!-- Card header -->
        <div class="card-header border-0">

            <div class="row">
                <div class="col-6">
                    <h3 class="mb-0"> All Coupons
                        <span class="badge badge-md badge-circle badge-floating badge-gray border-white">
                            {{ $coupons->count() }}</span>
                    </h3>
                </div>
            </div>
        </div>
        <!-- Light table -->
        <div class="table-responsive">
            @if (session()->has('MSG'))
            <div class="alert alert-{{ session()->get('TYPE') }}">
                <strong> <a>{{ session()->get('MSG') }}</a></strong>
            </div>
            @endif
            <table class="table align-items-center table-flush text-center">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Coupon Code</th>
                        <th>Coupon Amount</th>
                        <th>Coupon Limit (Per User)</th>
                        <th>Coupon Expiry Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    @php $i=1 @endphp
                    @foreach ($coupons as $data)

                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $data->code }}</td>
                        <td>{{ $data->fixed_amount }} </td>
                        <td>
                            {{ $data->limit_per_user }}
                        </td>
                        <td>
                            {{ $data->expires_at->format('d-m-Y') }} ({{ $data->expires_at->diffForHumans() }})
                        </td>
                        <td>
                            @can('edit_coupon')
                            <a href="{{ route('store_admin.editcoupon', $data->id) }}"
                                class="btn btn-success btn-sm text-white">Edit Coupon</a>
                            @endcan
                            @can('delete_coupon')
                            <form action="{{ route('store_admin.deletecoupon', $data->id) }}" method="post"
                                class="d-inline-block">
                                {{ csrf_field() }}
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm text-white" type="submit">Delete
                                    Coupon</button>
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
@can('add_coupon')
<a href="{{ route('store_admin.addcoupon') }}" class="float btn-primary-appetizr">
    <i class="fa fa-plus my-float"></i>
</a>
@endcan


@endsection
