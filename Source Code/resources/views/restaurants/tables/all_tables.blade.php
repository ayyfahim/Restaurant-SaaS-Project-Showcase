@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')

    <style>
        @media (max-width: 576px) {
            .btn {
                font-size: .75rem;
                line-height: 1.5;
                padding: .25rem .5rem;
                border-radius: .25rem;
                margin-top: 5px;
            }

            .col-x-text-center {
                text-align: center !important;
            }
        }

    </style>

    <div class="container-fluid">


        <div class="card-body">
            <div class="row">
                <div class="col-6">

                </div>
                <div class="col-md-12 text-right col-x-text-center">
                    @can('add_tables')
                    <button onclick="event.preventDefault(); document.getElementById('add_new').submit();"
                        class="btn btn-md btn-primary-appetizr btn-round btn-icon" data-toggle="tooltip"
                        data-original-title="Add Tables">
                        <span class="btn-inner--icon"><i class="fas fa-chair"></i></span>
                        <span class="btn-inner--text">Add Tables</span>
                    </button>
                    <form action="{{ route('store_admin.add_tables') }}" method="get" id="add_new"></form>
                    @endcan
                    @can('view_table_report')
                    <button onclick="event.preventDefault(); document.getElementById('table_report').submit();"
                        class="btn btn-md btn-light btn-round btn-icon" data-toggle="tooltip"
                        data-original-title="Table Report">
                        <span class="btn-inner--icon"><i class="fas fa-receipt"></i></span>
                        <span class="btn-inner--text">Table Report</span>
                    </button>
                    <form action="{{ route('store_admin.table_report') }}" method="get" id="table_report"></form>
                    @endcan
                </div>
            </div>
        </div>

        <div class="tab-pane fade show active" id="home2" role="tabpanel" aria-labelledby="home-tab">
            <div class="row">


                @php $i=1 @endphp
                @foreach ($tables as $table)

                    <div class="col-md-3">

                        <div class="card">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">

                                        <h3 style="padding: 5px;">
                                            Table Name: <b>{{ $table->table_name }}</b>
                                        </h3>

                                        @if ($table->waiters)
                                            @foreach ($table->waiters as $waiter)
                                                <h4 style="padding: 5px;">
                                                    Assigned Waiters: <b>{{ $waiter->name }}</b>
                                                </h4>
                                            @endforeach

                                        @else
                                            <h5 style="padding: 5px;">
                                                <b>No Waiters selected</b>
                                            </h5>
                                        @endif


                                    </div>

                                    <div class="col-auto">

                                        <label class="custom-toggle">
                                            <input type="checkbox" disabled
                                                {{ $table->is_active == 1 ? 'checked' : null }}>
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="Off"
                                                data-label-on="On"></span>
                                        </label>


                                    </div>

                                </div>


                                <hr>
                                <div class="row" style="margin-top: -18px;">
                                    <div class="col">
                                        @can('edit_tables')
                                        <a href="{{ route('store_admin.edit_table', $table->id) }}"
                                            class="text-muted"><b>Edit</b></a>
                                        @endcan

                                    </div>
                                    <div class="col">
                                        @can('set_waiter')
                                        <a href="#" class="text-dark" id="openWaiterModal" data-toggle="modal"
                                            data-target="#setWaiterModal{{ $table->id }}">
                                            <b>Set Waiter</b>
                                        </a>
                                        @endcan

                                    </div>
                                    <div class="col">
                                        @can('view_qr_code')
                                            @if ($table->table_number)
                                            <a href="{{ route('download_tblqr', [Auth::user()->view_id, $table->table_number]) }}"><b style="color: red;">QR Code</b></a>
                                            @endif
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="setWaiterModal{{ $table->id }}" data-backdrop="static"
                        data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Set Waiters</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="post" action="{{ route('store_admin.set_water_to_table') }}"
                                        enctype="multipart/form-data" id="set_water_to_table_form">
                                        {{ csrf_field() }}
                                        <!-- Form groups used in grid -->
                                        <div class="row justify-content-center">
                                            <div class="col-md-10 text-center">
                                                <div class="form-group">
                                                    <input id="modal_table_id" type="hidden" name="table_id"
                                                        class="form-control d-none" required value="{{ $table->id }}">
                                                    <select class="form-control" id="waiter_list" name="waiter_ids[]"
                                                        multiple>
                                                        @foreach ($waiters as $waiter)
                                                            @if ($table->waiters)
                                                                <option value="{{ $waiter->id }}"
                                                                    {{ $table->waiters->pluck('id')->contains($waiter->id) ? 'selected' : '' }}>
                                                                    {{ $waiter->name }}
                                                                    ({{ $waiter->id }})
                                                                </option>
                                                            @else
                                                                <option value="{{ $waiter->id }}">
                                                                    {{ $waiter->name }}
                                                                    ({{ $waiter->id }})
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <p class="text-sm text-gray">Please hold down control to select multiple
                                                    waiters.</p>
                                            </div>
                                            <div class="col-md-12 text-center">
                                                <div class="form-group">
                                                    <button class="btn btn-primary" type="submit">Update</button>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

    </div>






@endsection

{{-- @section('custom_scripts')
    <script>
        $(document).ready(function() {
            $("#home2 #openWaiterModal").click(function() { // Click to only happen on announce links
                $("#modal_table_id").val($(this).data('id'));
                console.log('table_id', $(this).data('id'))
                $('#set_water_to_table_form option[value="' +
                    $(this).data('id') +
                    '"]').remove();
                $('#setWaiterModal').modal('show');
            });

            $("#openWaiterModal #delete_current_waiter").click(
                function() {
                    // $("#waiter_list").val('null');

                    $("#set_water_to_table_form").submit();
                });
        });

    </script>
@endsection --}}
