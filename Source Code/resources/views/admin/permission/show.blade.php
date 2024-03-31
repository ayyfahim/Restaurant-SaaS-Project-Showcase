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
                                <div class="col-8">
                                    <h4 class="card-title mb-0">
                                        <i class="fa fa-users fa-lg-fw" aria-hidden="true"></i> Permission Show
                                        <small class="text-muted"> </small>
                                    </h4>
                                    <div class="small text-muted">
                                        Permissions Management
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">
                                         <button onclick="window.history.back();"class="btn btn-warning btn-sm ml-1 " data-toggle="tooltip" title="Return Back"><i class="fa fa-reply"></i> Return Back</button>

                                    <a href="{{route('permissions.edit',['role' => \Config::get('accessAs'),'permission'=>$permission->id])}}" class="btn btn-primary btn-sm ml-1" data-toggle="tooltip" title="" data-original-title="Edit Permission">
                                        <i class="fas fa-wrench"></i>
                                        Edit Permission
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
                                                    <td>{{$permission->name}}</td>
                                                </tr>

                                                <tr>
                                                    <th><strong>Permissions</strong></th>
                                                    <td>
                                                        {{$permission->guard_name}}
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
