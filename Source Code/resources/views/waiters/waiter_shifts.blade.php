@extends("waiters.layouts.waiterslayout")

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
            // height: 205, // set height of table (in CSS or here), this enables the Virtual DOM and improves render speed dramatically (can be any valid css height value)
            // data: tabledata, //assign data to table
            ajaxURL: '/api/web/store/waiter_shift/{{ auth()->id() }}',
            ajaxConfig: "POST",
            ajaxParams: {
                store_id: '{{ auth()->user()->store_id }}',
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


                },
                {
                    title: "Monday",
                    field: "Monday",


                },
                {
                    title: "Tuesday",
                    field: "Tuesday",


                },
                {
                    title: "Wednesday",
                    field: "Wednesday",


                },
                {
                    title: "Thursday",
                    field: "Thursday",


                },
                {
                    title: "Friday",
                    field: "Friday",


                },
                {
                    title: "Saturday",
                    field: "Saturday",


                },
                {
                    title: "Sunday",
                    field: "Sunday",


                },
            ],
        });

    </script>
@endsection

@section('waiterscontent')

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

        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">

                <div class="row">
                    <div class="col-6">
                        <h3 class="mb-0">Current Waiter Shifts</h3>
                    </div>
                </div>
            </div>

            <div id="example-table"></div>

        </div>

    @endsection
