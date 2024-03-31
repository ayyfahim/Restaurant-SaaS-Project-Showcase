@extends("admin.adminlayout")

@section("admin_content")
    <div class="container-fluid">

        <div class="border-0">
            <div class="row">
                <div class="col-6">

                </div>
                <div class="col-6 text-right">
                    <button onclick="event.preventDefault(); document.getElementById('add_new').submit();"
                            class="btn btn-sm btn-primary btn-round btn-icon" data-toggle="tooltip"
                            data-original-title="Add Tables">
                        <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                        <span class="btn-inner--text">Add Tables</span>
                    </button>
                    <form action="{{route('add_slider')}}" method="get" id="add_new"></form>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row row-example">
                <div class="col-sm-3">
                    <div class="card" style="border: 2px dashed #001354;">
                        <div class="card-body">
                            <!-- List group -->
                            <ul class="list-group list-group-flush list my--3">
                                <li class="list-group-item px-0">
                                    <div class="row align-items-center">

                                        <div class="col">
                                            <h4 >
                                                <b>Table No</b> /  <b>Code</b>
                                            </h4>
                                            <br>
                                           <button type="button" class="btn btn-sm btn-primary">Edit</button>
                                           <button type="button" class="btn btn-sm btn-danger">Delete</button>
                                        </div>
                                        <div class="col-auto">
                                            <label class="custom-toggle">
                                                <input type="checkbox" checked="">
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="Off" data-label-on="On"></span>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card" style="border: 2px dashed #001354;">
                        <div class="card-body">
                            <!-- List group -->
                            <ul class="list-group list-group-flush list my--3">
                                <li class="list-group-item px-0">
                                    <div class="row align-items-center">

                                        <div class="col">
                                            <h4 >
                                                <b>Table No</b> /  <b>Code</b>
                                            </h4>
                                            <br>
                                            <button type="button" class="btn btn-sm btn-primary">Edit</button>
                                            <button type="button" class="btn btn-sm btn-danger">Delete</button>
                                        </div>
                                        <div class="col-auto">
                                            <label class="custom-toggle">
                                                <input type="checkbox" checked="">
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="Off" data-label-on="On"></span>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="col-sm-3">
                    <div class="card" style="border: 2px dashed #001354;">
                        <div class="card-body">
                            <!-- List group -->
                            <ul class="list-group list-group-flush list my--3">
                                <li class="list-group-item px-0">
                                    <div class="row align-items-center">

                                        <div class="col">
                                            <h4 >
                                                <b>Table No</b> /  <b>Code</b>
                                            </h4>
                                            <br>
                                            <button type="button" class="btn btn-sm btn-primary">Edit</button>
                                            <button type="button" class="btn btn-sm btn-danger">Delete</button>
                                        </div>
                                        <div class="col-auto">
                                            <label class="custom-toggle">
                                                <input type="checkbox" checked="">
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="Off" data-label-on="On"></span>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="col-sm-3">
                    <div class="card" style="border: 2px dashed #001354;">
                        <div class="card-body">
                            <!-- List group -->
                            <ul class="list-group list-group-flush list my--3">
                                <li class="list-group-item px-0">
                                    <div class="row align-items-center">

                                        <div class="col">
                                            <h4 >
                                                <b>Table No</b> /  <b>Code</b>
                                            </h4>
                                            <br>
                                            <button type="button" class="btn btn-sm btn-primary">Edit</button>
                                            <button type="button" class="btn btn-sm btn-danger">Delete</button>
                                        </div>
                                        <div class="col-auto">
                                            <label class="custom-toggle">
                                                <input type="checkbox" checked="">
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="Off" data-label-on="On"></span>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="col-sm-3">
                    <div class="card" style="border: 2px dashed #001354;">
                        <div class="card-body">
                            <!-- List group -->
                            <ul class="list-group list-group-flush list my--3">
                                <li class="list-group-item px-0">
                                    <div class="row align-items-center">

                                        <div class="col">
                                            <h4 >
                                                <b>Table No</b> /  <b>Code</b>
                                            </h4>
                                            <br>
                                            <button type="button" class="btn btn-sm btn-primary">Edit</button>
                                            <button type="button" class="btn btn-sm btn-danger">Delete</button>
                                        </div>
                                        <div class="col-auto">
                                            <label class="custom-toggle">
                                                <input type="checkbox" checked="">
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="Off" data-label-on="On"></span>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>





















    </div>




@endsection
