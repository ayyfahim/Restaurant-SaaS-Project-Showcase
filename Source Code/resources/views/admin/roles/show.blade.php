@extends("admin.adminlayout")

@section('admin_content')

<div class="container-fluid">
    <div class="c-body mb-4">
        <main class="c-main">
            <div class="container-fluid">
                <div class="animated fadeIn">
                    <!-- Main content block -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-10">
                                    <h4 class="card-title mb-0">
                                        <i class="fa fa-users fa-lg-fw" aria-hidden="true"></i> Role Show
                                        <small class="text-muted"> </small>
                                    </h4>
                                    <div class="small text-muted">
                                        Roles Management
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">
                                         <button onclick="window.history.back();"class="btn btn-warning btn-sm ml-1 " data-toggle="tooltip" title="Return Back"><i class="fa fa-reply"></i> Return Back</button>

                                    <a href="{{route('roles.edit',['role'=>$role->id])}}" class="btn btn-primary btn-sm ml-1" data-toggle="tooltip" title="" data-original-title="Edit Role">
                                        <i class="fas fa-wrench"></i>
                                        Edit Role
                                    </a>
                                </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col">
                                    <div class="table-responsive">
                                        <table class="table table-flush">
                                            <tbody>
                                                <tr>
                                                    <th><strong>Name</strong></th>
                                                    <td>{{$role->name}}</td>
                                                </tr>

                                                <tr>
                                                    <th><strong>Permissions</strong></th>
                                                    <td>
                                                        <ul>
                                                            @foreach ($role->getAllPermissions() as $permission)
                                                                <li>{{ $permission->name }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div><!--table-responsive-->
                                </div>
                                <!--/.col-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
    @endsection
