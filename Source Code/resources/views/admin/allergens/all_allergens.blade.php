@extends("admin.adminlayout")

@section('admin_content')

<div class="container-fluid">




    <div class="alert alert-danger alert-dismissible fade show" style="margin-bottom: 15px;" role="alert">
        <span class="alert-icon"><i class="ni ni-like-2"></i></span>
        <span class="alert-text"><strong>Sync Alert!</strong> You need to manually add images if you syncronize allergens.</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>

    <div class="card">
        <!-- Card header -->
        <div class="card-header border-0">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="mb-0 d-inline-block">All Allergens</h3>
                    <a href="{{ route('add_allergens') }}" class="btn btn-success btn-sm ml-2 text-white">+ Add
                        Allergen</a>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{ route('sync_allergens') }}" class="btn btn-primary btn-sm ml-2 text-white">+ Sync
                        Allergens</a>
                </div>
            </div>
        </div>

        <!-- Light table -->
        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                        <th class="text-left">Id</th>
                        <th class="text-left">Name</th>
                        <td class="text-left">Image</td>
                        <th class="text-left">Action</th>
                    </tr>
                </thead>
                <tbody>



                    @php $i=1 @endphp
                    @foreach ($allergens as $allergen)
                    <tr>

                        <td>
                            <span class="text-muted">{{ $i++ }}</span>
                        </td>
                        <td> <span class="text-muted">{{ $allergen->name }}</span></td>
                        <td class="table-user">
                            <img src="{{ asset($allergen->image_url) }}" class="avatar rounded-circle mr-3">

                        </td>
                        <td>
                            <a href="{{ route('edit_allergen', $allergen->id) }}" data-toggle="tooltip"
                                data-original-title="Edit allergen">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('delete_allergen', $allergen->id) }}" method="post"
                                class="d-inline-block">
                                @method('DELETE')
                                @csrf
                                <button type="submit" data-toggle="tooltip" data-original-title="Delete allergen"
                                    class="border-0"><i class="fas fa-trash-alt"></i></button>

                            </form>
                            {{-- <a href="{{ route('delete_allergen', $allergen->id) }}" data-toggle="tooltip"
                            data-original-title="Delete allergen">
                            <i class="fas fa-trash-alt"></i>
                            </a> --}}
                    </tr>
                    @endforeach


                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
