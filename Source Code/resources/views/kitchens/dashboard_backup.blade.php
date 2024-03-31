@extends("kitchens.layouts.kitchenslayout")

@section('kitchenscontent')

    <div class="container-fluid">

        <div class="card-body">
            <a href="{{ route('kitchen_admin.dashboard') }}" class="btn"
                style="background-color: rgba(211, 0, 0, 1); color: #fff">Main Kitchen</a>
            <a class="btn btn-secondary"
                href="{{ route('kitchen_admin.kitchenlocation', auth()->user()->id) }}">{{ auth()->user()->name }}</a>
            {{-- @foreach ($kitchenLocations as $location)
                <a class="btn btn-secondary"
                    href="{{ route('kitchen_admin.kitchenlocation', $location->id) }}">{{ $location->location }}</a>
            @endforeach --}}
        </div>



        <div class="row">
            @forelse ($tables as $table)
                @if ($table['kitchen_orders'])
                    <div class="col-md-4">
                        <div class="card">
                            <!-- Card header -->
                            <div class="card-header border-0">
                                <div class="row">
                                    <div class="col-6">
                                        <h3 class="mb-0">Table #{{ $table['id'] }}</h3>
                                    </div>
                                    <div class="col-6">
                                        <a class="btn btn-outline-success btn-sm float-right"
                                            onclick="document.getElementById('ready_to_serve_table_{{ $table['id'] }}').submit();">Ready
                                            to Serve</a>
                                    </div>

                                </div>
                            </div>
                            <!-- Light table -->
                            <div class="table-responsive">
                                <table class="table table-flush" id="datatable-basic">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>All accepted orders</th>
                                            <th>
                                                {{ Carbon\Carbon::parse(end($table['kitchen_orders'])['created_at'])->diffForHumans() }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($table['kitchen_orders'] as $order_data)
                                            <tr>
                                                <td>
                                                    @foreach ($order_data['order_details'] as $key => $data)
                                                        <div class="mb-2 border-bottom">
                                                            <b>{{ $data['name'] }}</b> <br>
                                                            @foreach ($data['order_details_extra_addon'] as $key => $exra)
                                                                Name: <strong>{{ $exra['addon_name'] }} (
                                                                    {{ $exra['addon_price'] }})</strong>
                                                                x
                                                                <strong> {{ $exra['addon_count'] }}</strong>
                                                                {{-- = <strong>  {{$account_info!=NULL?$account_info->currency_symbol:"₹"}}{{$exra['addon_count'] * $exra['addon_price']}}</strong> --}}
                                                                <br>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <a class="btn btn-outline-success btn-sm float-right"
                                                        onclick="document.getElementById('ready_to_serve_{{ $order_data['id'] }}').submit();"><i
                                                            class="fas fa-check"></i>
                                                    </a>
                                                    <form style="visibility: hidden" method="post"
                                                        action="{{ route('kitchen_admin.update_order_status', ['order' => $order_data['id']]) }}"
                                                        id="ready_to_serve_{{ $order_data['id'] }}">
                                                        @csrf
                                                        @method('patch')
                                                        <input style="visibility:hidden" name="status" type="hidden" value="5">
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <form style="visibility: hidden" method="post"
                        action="{{ route('kitchen_admin.update_table_status', ['table' => $table['id']]) }}"
                        id="ready_to_serve_table_{{ $table['id'] }}">
                        @csrf
                        @method('patch')
                        <input style="visibility:hidden" name="status" type="hidden" value="5">
                    </form>
                @endif
            @empty
                <p>No orders :(</p>
            @endforelse
        </div>
    </div>

@endsection
