@extends("kitchens.layouts.kitchenslayout")

@section('kitchenscontent')

    <div class="container-fluid">

        <div class="card-body">
            @if (auth()->user()->is_main)
                <a href="{{ route('kitchen_admin.dashboard') }}" class="btn btn-secondary">Main Kitchen</a>
            @endif
            <a href="{{ route('kitchen_admin.kitchenlocation') }}" class="btn"
                style="background-color: rgba(211, 0, 0, 1); color: #fff">{{ auth()->user()->name }}</a>
            {{-- @foreach ($kitchenLocations as $location)
                @if ($location->id == $kitchenLocation->id)
                    <a class="btn" style="background-color: rgba(211, 0, 0, 1); color: #fff"
                        href="{{ route('kitchen_admin.kitchenlocation', $location->id) }}">{{ $location->location }}</a>
                @else
                    <a class="btn btn-secondary"
                        href="{{ route('kitchen_admin.kitchenlocation', $location->id) }}">{{ $location->location }}</a>
                @endif
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
                                                            @if ($data['kitchen_location_id'] == $kitchenLocation->id && $data['status'] != 1)
                                                                <div class="status_changable" data-type="order_detail"
                                                                    data-id="{{ $data['id'] }}">
                                                                    <b>{{ $data['name'] }}</b>
                                                                </div>
                                                            @endif
                                                            @foreach ($data['order_details_extra_addon'] as $key => $exra)
                                                                @if ($exra['kitchen_location_id'] == $kitchenLocation->id && $exra['status'] != 1)
                                                                    <div class="status_changable"
                                                                        data-type="order_details_extra_addon"
                                                                        data-id="{{ $exra['id'] }}">
                                                                        Name: <strong>{{ $exra['addon_name'] }} (
                                                                            {{ $exra['addon_price'] }})</strong>
                                                                        x
                                                                        <strong> {{ $exra['addon_count'] }}</strong>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <a class="btn btn-outline-success btn-sm float-right"
                                                        id="status_changable_btn">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <form style="visibility: hidden" method="post"
                                            action="{{ route('kitchen_admin.update_order_status_changables') }}"
                                            id="change_status_form">
                                            @csrf
                                        </form>
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

@section('custom_scripts')
<script>
    $(document).ready(function() {
        $('body #status_changable_btn').click(function() {
            let e = $(this);
            let status_changables = e.parent().parent().children()[0].querySelectorAll(
                ".status_changable");

            // console.log('status_changables', status_changables);

            let datas = [];

            $.each(status_changables, function(key, changable) {
                data = {
                    "type": changable.dataset.type,
                    "id": changable.dataset.id,
                }
                datas.push(data)
            });

            // var input = $("<input>")
            //     .attr("type", "hidden")
            //     .attr("name", "data").val(datas);

            // $('#change_status_form').append(input);
            // $("#change_status_form").submit();

            $.ajax({
                type: "POST",
                url: '{{ route('kitchen_admin.update_order_status_changables') }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'datas': datas,
                },
                success: function() {
                    location.reload();
                },
                // dataType: dataType
            });

            // console.log('datas', datas)
        });
    });

</script>
@endsection
