@extends("admin.adminlayout")

@section('admin_content')

<div class="container-fluid">
    <div class="c-body mb-4">
        <main class="c-main">
            <div class="animated fadeIn">
                <!-- Main content block -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <h4 class="card-title mb-0">
                                    <i class="fa fa-list" aria-hidden="true"></i> Permissions
                                    <small class="text-muted">Details </small>
                                </h4>
                                <div class="small text-muted">
                                    All Permissions Module
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">
                                    <button onclick="window.history.back();"class="btn btn-warning btn-sm ml-1 " data-toggle="tooltip" title="Return Back"><i class="fa fa-reply"></i> Return Back</button>
                                    <a href="{{route('permissions.create')}}" class="btn btn-primary btn-sm ml-1" data-toggle="tooltip" title="" data-original-title="Create Permission">
                                        {{-- <button class="btn btn-primary">Add Role</button> --}}
                                    <i class="fas fa-plus-circle"></i>
                                        Create Permission
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- DataTales Example -->
                        <div class="card shadow mt-4 mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Permissions</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-flush " width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Name</th>
                                                <th>Guard Name</th>
                                                <th width="280px">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i=0 @endphp
                                            @foreach ($permissions as $key => $permission)
                                            @php $permission = (object)$permission @endphp
                                                <tr>
                                                    <td>{{ ++$i }}</td>
                                                    <td>{{ $permission->name }}</td>
                                                    <td>{{ $permission->guard_name }}</td>
                                                    <td>
                                                        <a class="btn btn-info btn-sm" href="{{ route('permissions.show',['role' => \Config::get('accessAs'),'permission'=>$permission->id]) }}">Show</a>
                                                        <a class="btn btn-primary btn-sm" href="{{ route('permissions.edit',['role' => \Config::get('accessAs'),'permission'=>$permission->id]) }}">Edit</a>
                                                        <form action="{{route('permissions.destroy',['role' => \Config::get('accessAs'),'permission'=>$permission->id])}}" method="post" style="display: inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="submit" value="Delete" class="btn btn-danger btn-sm">
                                                        </form>
                                                    </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-7">
                                            <div class="float-left">
                                                {{-- Total {{$roles->count()}} --}}
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="float-right">
                                                <div class="margin-top-30 margin-bottom-30 mt-3">
                                                    {{$permissions->links('pagination::bootstrap-4')}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
