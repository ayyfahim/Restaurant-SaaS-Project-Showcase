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
                                <div class="col-10">
                                    <h4 class="card-title mb-0">
                                        <i class="fa fa-list" aria-hidden="true"></i> Roles
                                        <small class="text-muted">Details </small>
                                    </h4>
                                    <div class="small text-muted">
                                        All Role Module
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">
                                            <button onclick="window.history.back();"class="btn btn-warning btn-sm ml-1 " data-toggle="tooltip" title="Return Back"><i class="fa fa-reply"></i> Return Back</button>
                                            <a href="{{route('roles.create',['role' => \Config::get('accessAs')])}}" class="btn btn-primary btn-sm ml-1" data-toggle="tooltip" title="" data-original-title="Create Role">
                                            {{-- <button class="btn btn-primary">Add Role</button> --}}
                                        <i class="fas fa-plus-circle"></i>
                                            Create Role
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                {{-- <div class="col-lg-2">
                                    <div class="card mb-4"> --}}
                                    <div class="table-responsive">
                                        <table class="table table-flush">
                                        <tr>
                                                <th>No</th>
                                                <th>Name</th>
                                                {{-- <th>Guard Name</th> --}}
                                                <th>Permission</th>
                                                <th width="280px">Action</th>
                                            </tr>
                                            <?php $i=0;?>
                                            @foreach ($roles as $key => $role)
                                                @php $role = (object)$role @endphp
                                                <tr>
                                                    <td>{{ ++$i }}</td>
                                                    <td>{{ $role->name }}</td>
                                                    {{-- <td>{{ $role->guard_name }}</td> --}}
                                                    <td>
                                                        @if ($role->name == 'superadmin')
                                                            <li>All</li>
                                                        @else
                                                            @foreach($role->getAllPermissions() as $permissions)
                                                                <li>{{ Str::ucfirst(str_replace("_"," ", $permissions->name)) }}</li>
                                                            @endforeach
                                                        @endif

                                                    </td>
                                                    <td>
                                                        <a class="btn btn-info btn-sm" href="{{ route('roles.show',['role'=>$role->id]) }}">Show</a>

                                                        <a class="btn btn-primary btn-sm" href="{{ route('roles.edit',['role'=>$role->id]) }}">Edit</a>
                                                        <form action="{{route('roles.destroy',['role'=>$role->id])}}" method="post" style="display: inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="submit" value="Delete" class="btn btn-danger btn-sm">
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    {{-- </div>
                                </div> --}}
                            </div>
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
                                            {{$roles->links('pagination::bootstrap-4')}}
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
