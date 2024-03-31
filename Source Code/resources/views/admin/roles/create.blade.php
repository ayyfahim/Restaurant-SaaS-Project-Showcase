@extends("admin.adminlayout")

@section('admin_content')

<div class="container-fluid">
        <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-8">
                    <h4 class="card-title mb-0">
                        <i class="c-icon cil-people"></i> Roles
                        <small class="text-muted">Created </small>
                    </h4>
                    <div class="small text-muted">
                        Roles Management
                    </div>
                </div>
                <!--/.col-->
                <div class="col-4">
                    <div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">
                        <button onclick="window.history.back();" class="btn btn-warning btn-sm ml-1 " data-toggle="tooltip" title="" data-original-title="Return Back"><i class="fa fa-reply"></i> Return Back</button>

                    </div>
                </div>
                <!--/.col-->
            </div>
            <!--/.row-->

            <hr>

            <div class="row mt-4">
                <div class="col">
                    <form class="form-horizontal" action="{{route('roles.store',['role' => \Config::get('accessAs')])}}" method="POST">
                        @csrf

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label" for="name">Name</label>

                            <div class="col-md-10">
                                <input class="form-control" type="text" name="name" id="name" placeholder="Name" maxlength="191" required="">
                            </div>
                        </div><!--form-group-->

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label">Abilities</label>

                            <div class="col-12 col-sm-10">
                                <div class="table-responsive">
                                    <table class="table table-flush ">
                                        <thead>
                                            <tr>
                                                <th>Permissions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    @foreach($permissions as $value)
                                                    @php $value = (object)$value @endphp
                                                    <div class="">
                                                            {{ Form::checkbox('permissions[]', $value->id, false, array('class' => 'name')) }}
                                                            {{ $value->name }}
                                                    </div>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!--form-group-->

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary " data-toggle="tooltip" title="" data-original-title="Create Role">
                                        <i class="fas fa-plus-circle"></i>
                                        Create Role
                                    </button>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- <div class="card-footer">
            <div class="row">
                <div class="col">
                    <small class="float-right text-muted">

                    </small>
                </div>
            </div>
        </div> --}}
    </div>
</div>
    @endsection
