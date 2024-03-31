@extends("admin.adminlayout")

@section("admin_content")
    <div class="container-fluid mt--6">

        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <div class="row">
                    <div class="col-6">
                        <h3 class="mb-0">All Sliders</h3>
                    </div>
                    <div class="col-6 text-right">
                        <button onclick="event.preventDefault(); document.getElementById('add_new').submit();" class="btn btn-sm btn-primary btn-round btn-icon" data-toggle="tooltip" data-original-title="Add Slider">
                            <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                            <span class="btn-inner--text">Add Slider</span>
                        </button>
                        <form action="{{route('add_slider')}}" method="get" id="add_new"></form>
                    </div>
                </div>
            </div>
            <!-- Light table -->
            <div class="table-responsive">
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                    <tr>
                        <th>PREVIEW</th>
                        <th>SLIDER NAME</th>
                        <th>VISIBILITY</th>
                        <th>ACTION</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sliders as $slider)
                    <tr>
                        <td class="table-user">
                            <img src="{{asset($slider->photo_url)}}" class="avatar rounded-circle mr-3">

                        </td>
                        <td>
                            <span class="text-muted">{{$slider->name}}</span>
                        </td>
                        <td>
                            <span class="badge badge-{{$slider->is_visible == 1 ? "success":"danger"}}">{{$slider->is_visible == 1 ? "LIVE":"HIDDEN"}}</span>
                        </td>
                        <td class="table-actions">

                            <span>
                                    <a href="{{route('update_slider',$slider->id)}}"  class="table-action" data-toggle="tooltip" data-original-title="Edit Slider">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                        </span>
                            <span>
                                    <a class="table-action" onclick="if(confirm('Are you sure you want to delete this item?')){ event.preventDefault();document.getElementById('delete-form-{{$slider->id}}').submit(); }"  data-toggle="tooltip" data-original-title="Delete Slider">
                                          <i class="fas fa-trash"></i>
                                    </a>
                                     <form method="post" action="{{route('delete_slider')}}" id="delete-form-{{$slider->id}}" style="display: none">
                                                @csrf
                                         @method('DELETE')
                                                <input type="hidden" value="{{$slider->id}}" name="id">
                                            </form>
                                    </span>



                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>





    </div>




@endsection
