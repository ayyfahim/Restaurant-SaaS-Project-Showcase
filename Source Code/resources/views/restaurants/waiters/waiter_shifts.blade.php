@extends("restaurants.layouts.restaurantslayout")

@section('custom_styles')
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.css"> --}}
    <link href="https://unpkg.com/tabulator-tables@4.9.3/dist/css/tabulator.min.css" rel="stylesheet">
@endsection

@section('custom_scripts')
    {{-- <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"> </script>
     <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js">
    </script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js">
    </script>
    <script type="text/javascript" charset="utf8"
        src="https://editor.datatables.net/extensions/Editor/js/dataTables.editor.min.js"></script> --}}
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.9.3/dist/js/tabulator.min.js"></script>

    {{-- <script>
        var editor; // use a global for the submit and return data rendering in the examples

        $(document).ready(function() {
            editor = new $.fn.dataTable.Editor({
                ajax: '/api/web/store/waiter_shifts',
                table: "#example",
                fields: [{
                    label: "First name:",
                    name: "first_name"
                }, {
                    label: "Last name:",
                    name: "last_name"
                }, {
                    label: "Position:",
                    name: "position"
                }, {
                    label: "Office:",
                    name: "office"
                }, {
                    label: "Extension:",
                    name: "extn"
                }, {
                    label: "Start date:",
                    name: "start_date",
                    type: "datetime"
                }, {
                    label: "Salary:",
                    name: "salary"
                }]
            });

            // Activate an inline edit on click of a table cell
            $('#example').on('click', 'tbody td:not(:first-child)', function(e) {
                editor.inline(this);
            });

            $('#example').DataTable({
                dom: "Bfrtip",
                ajax: "../php/staff.php",
                order: [
                    [1, 'asc']
                ],
                columns: [{
                        data: null,
                        defaultContent: '',
                        className: 'select-checkbox',
                        orderable: false
                    },
                    {
                        data: "first_name"
                    },
                    {
                        data: "last_name"
                    },
                    {
                        data: "position"
                    },
                    {
                        data: "office"
                    },
                    {
                        data: "start_date"
                    },
                    {
                        data: "salary",
                        render: $.fn.dataTable.render.number(',', '.', 0, '$')
                    }
                ],
                select: {
                    style: 'os',
                    selector: 'td:first-child'
                },
                buttons: [{
                        extend: "create",
                        editor: editor
                    },
                    {
                        extend: "edit",
                        editor: editor
                    },
                    {
                        extend: "remove",
                        editor: editor
                    }
                ]
            });
        });

    </script> --}}

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
            ajaxURL: '/api/web/store/waiter_shifts',
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
                        store_id: '{{ auth()->id() }}',
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
            // rowClick: function(e, row) { //trigger an alert message when the row is clicked
            //     alert("Row " + row.getData().id + " Clicked!!!!");
            // },
        });

    </script>
@endsection

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
            <a class="btn btn-secondary" href="{{ route('store_admin.all_waiters') }}">Waiters</a>
            <button class="btn" style="background-color: rgba(211, 0, 0, 1); color: #fff">Waiter Shifts</button>
        </div>

        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">

                <div class="row">
                    <div class="col-6">
                        <h3 class="mb-0"> Waiter Shifts</h3>
                    </div>
                </div>
            </div>

            <div id="example-table"></div>

        </div>

    @endsection
