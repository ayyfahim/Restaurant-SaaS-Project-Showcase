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
                                    <i class="fa fa-list" aria-hidden="true"></i> Admin
                                    <small class="text-muted">Details </small>
                                </h4>
                                <div class="small text-muted mt-2">
                                    All Admin Module
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">
                                        <button onclick="window.history.back();"class="btn btn-warning btn-sm ml-1 " data-toggle="tooltip" title="Return Back"><i class="fa fa-reply"></i> Return Back</button>
                                        <a href="{{route('user.create')}}" class="btn btn-primary btn-sm ml-1" data-toggle="tooltip" title="" data-original-title="Add Admin">
                                        {{-- <button class="btn btn-primary">Add Role</button> --}}
                                    <i class="fas fa-plus-circle"></i>
                                        Add Admin
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            {{-- <div class="col-lg-2">
                                <div class="card mb-4"> --}}
                                    <table class="table ">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th width="" class="text-center">Action</th>
                                        </tr>
                                        <?php $i=0;?>
                                        @foreach ($users as $key => $user)
                                            <tr>
                                                <td>{{++$i}}</td>
                                                <td>{{$user->name}}</td>
                                                <td>{{$user->email}}</td>
                                                <td>{{$user->roles[0]->name}}</td>
                                                <td class="justify-content-center d-flex ">
                                                    <a class="btn btn-info btn-sm" href="{{ route('user.show',['user'=>$user->id]) }}">Show</a>

                                                    <a class="btn btn-primary btn-sm" href="{{route('user.edit',['user'=>$user->id])}}">Edit</a>

                                                    <form action="{{route('user.destroy',['user'=>$user->id])}}" method="post">
                                                        {{csrf_field()}}
                                                        {{ method_field('DELETE') }}
                                                        <input type="submit" class="btn btn-danger btn-sm" value="Delete">
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
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
                                        {{$users->links('pagination::bootstrap-4')}}
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
