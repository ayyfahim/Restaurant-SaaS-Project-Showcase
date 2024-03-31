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
                            <div class="row mt-4">
                                <div class="col">
                                    <div class="table-responsive">
                                        <table class="table table-flush">
                                            <tbody>
                                                <tr>
                                                    <th><strong>Name</strong></th>
                                                    <td>{{$user->name}}</td>
                                                </tr>
                                                <tr>
                                                    <th><strong>Email</strong></th>
                                                    <td>{{$user->email}}</td>
                                                </tr>
                                                <tr>
                                                    <th><strong>Role</strong></th>
                                                    <td>{{$user->roles[0]->name}}</td>
                                                </tr>

                                                <tr>
                                                    <th><strong>Permissions</strong></th>
                                                    <td>
                                                        <ul>
                                                            @foreach ($user->permissions as $permission)
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
