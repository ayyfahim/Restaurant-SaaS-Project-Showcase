@extends("restaurants.layouts.restaurantslayout")

@section('custom_styles')
    <link href="https://unpkg.com/tabulator-tables@4.9.3/dist/css/tabulator.min.css" rel="stylesheet">
@endsection

@section('custom_scripts')
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.9.3/dist/js/tabulator.min.js"></script>

    <script>
        var tabledata = [{
                "id": "time_to_start_working",
                "name": "time_to_start_working",
                "Monday": "2:00 PM",
                "Tuesday": "2:00 PM",
                "Wednesday": "2:00 PM",
                "Thursday": "2:00 PM",
                "Friday": "2:00 PM",
                "Saturday": "2:00 PM",
                "Sunday": "2:00 PM"
            },
            {
                "id": "end_time_for_work",
                "name": "end_time_for_work",
                "Monday": "2:00 PM",
                "Tuesday": "2:00 PM",
                "Wednesday": "2:00 PM",
                "Thursday": "2:00 PM",
                "Friday": "2:00 PM",
                "Saturday": "2:00 PM",
                "Sunday": "2:00 PM"
            },
            {
                "id": "start_lunch_break_time",
                "name": "start_lunch_break_time",
                "Monday": "2:00 PM",
                "Tuesday": "2:00 PM",
                "Wednesday": "2:00 PM",
                "Thursday": "2:00 PM",
                "Friday": "2:00 PM",
                "Saturday": "2:00 PM",
                "Sunday": "2:00 PM"
            },
            {
                "id": "end_lunch_break_time",
                "name": "end_lunch_break_time",
                "Monday": "2:00 PM",
                "Tuesday": "2:00 PM",
                "Wednesday": "2:00 PM",
                "Thursday": "2:00 PM",
                "Friday": "2:00 PM",
                "Saturday": "2:00 PM",
                "Sunday": "2:00 PM"
            }
        ];

        var editCheck = function(cell) {
            if (cell.getColumn().getField() == "name") {
                return false;
            }

            return true;
        }


        var table = new Tabulator("#example-table", {
            ajaxURL: '/api/web/store/waiter_shift/{{ $waiter->id }}',
            ajaxConfig: "POST",
            ajaxParams: {
                store_id: '{{ auth()->id() }}',
                // _token: '{{ csrf_token() }}',
            },
            layout: "fitColumns", //fit columns to width of table
            responsiveLayout: "hide", //hide columns that dont fit on the table
            tooltips: true, //show tool tips on cells
            addRowPos: "top", //when adding a new row, add it to the top of the table
            history: true, //allow undo and redo actions on the table
            pagination: "local", //paginate the data
            paginationSize: 7, //allow 7 rows per page of data
            movableColumns: true, //allow column order to be changed
            resizableRows: true, //allow row order to be changed
            columns: [ //Define Table Columns
                {
                    title: "Detail",
                    field: "name",
                    editable: editCheck,
                    editor: "input"
                },
                {
                    title: "Monday",
                    field: "Monday",
                    editable: editCheck,
                    editor: "input"
                },
                {
                    title: "Tuesday",
                    field: "Tuesday",
                    editable: editCheck,
                    editor: "input"
                },
                {
                    title: "Wednesday",
                    field: "Wednesday",
                    editable: editCheck,
                    editor: "input"
                },
                {
                    title: "Thursday",
                    field: "Thursday",
                    editable: editCheck,
                    editor: "input"
                },
                {
                    title: "Friday",
                    field: "Friday",
                    editable: editCheck,
                    editor: "input"
                },
                {
                    title: "Saturday",
                    field: "Saturday",
                    editable: editCheck,
                    editor: "input"
                },
                {
                    title: "Sunday",
                    field: "Sunday",
                    editable: editCheck,
                    editor: "input"
                },
            ],
            cellEdited: function(cell) {
                // This callback is called any time a cell is edited.
                console.log('field name', cell.getColumn().getField())
                console.log('row data', cell.getRow().getData())
                console.log('table data', table.getData())
                $.ajax({
                    url: "/api/web/store/update/waiter_shifts",
                    data: {
                        data: table.getData(),
                        cellData: cell.getValue(),
                        waiter_id: '{{ $waiter->id }}',
                    },
                    type: "post",
                    success: function(response, textStatus, xhr) {
                        // alert("AJAX result: " + response + "; status: " + textStatus);
                        alert("Successfully changed the data");
                    },
                    error: function(XMLHttpRequest, textStatus, error) {
                        alert("Error: " + textStatus + "; " + error);
                    }
                })
            },
        });

    </script>
@endsection

@section('restaurantcontant')


    <div class="container-fluid">
        <div class="card mb-4">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">Edit Waiter</h3>
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
                <form method="post" action="{{ route('store_admin.editwaiters_post', $waiter->id) }}"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    @method('PATCH')
                    <!-- Form groups used in grid -->
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="example3cols2Input">Waiter Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $waiter->name }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="example3cols2Input">Waiter Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $waiter->email }}"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="example3cols2Input">Waiter Phone (optional)</label>
                                <input type="text" name="phone" class="form-control" value="{{ $waiter->phone }}"
                                    >
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <button class="btn btn-primary btn-primary-appetizr" type="submit">Submit</button>
                            </div>
                        </div>


                    </div>

                </form>

                <div id="example-table"></div>
            </div>


        </div>




    @endsection
