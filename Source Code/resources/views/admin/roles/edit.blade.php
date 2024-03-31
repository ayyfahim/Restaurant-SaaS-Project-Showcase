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
                                        <i class="fa fa-users fa-lg-fw" aria-hidden="true"></i> Role Edit
                                        <small class="text-muted"> </small>
                                    </h4>
                                    <div class="small text-muted">
                                        Roles Management
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">
                                         <button onclick="window.history.back();"class="btn btn-warning btn-sm ml-1 " data-toggle="tooltip" title="Return Back"><i class="fa fa-reply"></i> Return Back</button>
                                    <a href="{{route('roles.show',['role'=>$role->id])}}" class="btn btn-success btn-sm ml-1" data-toggle="tooltip" title="" data-original-title="Show Role">
                                        <i class="fas fa-desktop"></i>
                                        Show Role
                                    </a>
                                </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col">
                                    <div class="table-responsive">
                                        <table class="table table-flush">
                                            <form action="{{route('roles.update',['role' =>$role->id])}}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <tbody>
                                                    <tr>
                                                        <th><strong>Name</strong></th>
                                                        <td><input type="name" class="form-control" placeholder="Name" name="name" value="{{$role->name}}"></td>
                                                    </tr>
                                                    <tr>
                                                        <th><strong>Permissions</strong></th>
                                                        <td>
                                                            @foreach($permissions as $value)
                                                                {{ Form::checkbox('permissions[]',
                                                                    $value['id'] ,
                                                                    in_array($value['id'], $rolePermissions) ? true : false,
                                                                    array('class' => 'name')
                                                                    )
                                                                }}
                                                                {{ $value['name'] }}
                                                            <br/>
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            <button type="submit" class="btn btn-primary">Submit</button>
                                                        </th>
                                                    </tr>
                                                </tbody>
                                            </form>
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
