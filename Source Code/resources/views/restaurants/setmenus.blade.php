@extends("restaurants.layouts.restaurantslayout")

@section("restaurantcontant")

<style>
    .float {
        position: fixed;
        width: 60px;
        height: 60px;
        bottom: 40px;
        right: 40px;
        background-color: #322A7D;
        color: #FFA101;
        border-radius: 50px;
        text-align: center;
        box-shadow: 2px 2px 3px #999;
    }


    .my-float {
        margin-top: 22px;
    }
</style>

<style>
    .main {
        margin: 0 auto;
        min-width: 320px;
        max-width: 100%;
        text-align: center;
    }

    .content {
        color: black;
        text-align: left;
    }

    .content>div {
        display: none;
        padding: 20px 25px 5px;
    }

    .radio input {
        display: none;
    }

    .radio label {
        display: inline-block;
        padding: 8px 25px;
        font-weight: 600;
        background: #dbdcde;
        text-align: center;
        border-radius: 10px;
    }

    .radio label:hover {
        color: #000000;
        cursor: pointer;
    }

    .radio input:checked+label {
        background: #bce8b3;
        color: #000;
    }

    #tab1:checked~.content #content1,
    #tab2:checked~.content #content2,
    #tab3:checked~.content #content3,
    #tab4:checked~.content #content4,
    #tab5:checked~.content #content5,
    #tab6:checked~.content #content6,
    #tab7:checked~.content #content7 {
        display: block;
    }

    @media screen and (max-width: 400px) {
        label {
            padding: 15px 10px;
        }
    }
</style>


<div class="container-fluid">


    <table width="32%">
        <tr>
            <td style="padding-right: 10px">
                <input type="text" id="Search" onkeyup="myFunction()" placeholder="Search.." title="Type in a name"
                    class="form-control">
            </td>
        </tr>
    </table>
    <br>


    <div class="flex-row">

        <div class="main radio">
            <div style="float:left;" class="text-center pb-3 scrolling-wrapper col-md-6 mx-auto pos-menu">
                @can('view_categories')
                    <a class="btn btn-secondary mt-2" href="{{route('store_admin.categories')}}">Category</a>
                @endcan
                @can('view_products')
                    <a class="btn btn-secondary mt-2" href="{{route('store_admin.products')}}">Products</a>
                @endcan
                @can('view_setmenus')
                    <button class="btn mt-2" style="background-color: rgba(211, 0, 0, 1); color: #fff">Set Menus</button>
                @endcan
                @can('view_addon_categories')
                    <a href="{{route('store_admin.addon_categories')}}" class="btn btn-secondary mt-2">Addon Categories</a>
                @endcan
                @can('view_timerestrictions')
                    <a href="{{route('store_admin.timerestrictions')}}" class="btn btn-secondary mt-2">Time Restrictions</a>
                @endcan
                @can('view_food_menues')
                    <a href="{{route('store_admin.menues')}}" class="btn btn-secondary mt-2">Food Menues</a>
                @endcan
            </div>

            <input id="tab1" type="radio" name="tabs" checked="">
            <label for="tab1">All</label>

            <input id="tab2" type="radio" name="tabs">
            <label for="tab2">Non-Veg</label>

            <input id="tab3" type="radio" name="tabs">
            <label for="tab3">Veg</label>

            <input id="tab4" type="radio" name="tabs">
            <label for="tab4">Disabled</label>


            <div class="content">


                <div class="row" id="content1">


                    <div class="tab-pane fade show active" id="home2" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">


                            @php $i=1 @endphp
                            @foreach($products as $pro)

                            <div class="col-md-3 target">

                                <div class="card">
                                    <!-- Card body -->
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <img src="{{ $pro->image_url !=NULL ? Storage::disk('s3')->url($pro->image_url) : asset('themes/default/images/all-img/empty.png') }}" width="121" height="121" style="border-radius: 15px">
                                            </div>

                                            <div class="col text-right">
                                                Price: @include('layouts.render.currency',["amount"=>$pro->price])<br>
                                                Recommended &nbsp;<span
                                                    class="badge badge-{{$pro->is_recommended == 1 ? "
                                                    success":"danger"}}">{{$pro->is_recommended == 1 ?
                                                    "Yes":"No"}}</span>
                                                <br>
                                                Enabled &nbsp;<span class="badge badge-{{$pro->is_active == 1 ? "
                                                    success":"danger"}}">{{$pro->is_active == 1 ? "Yes":"No"}}</span>
                                                <br>
                                                Veg &nbsp;<span class="badge badge-{{$pro->is_veg == 1 ? "
                                                    success":"danger"}}">{{$pro->is_veg == 1 ? "Yes":"No"}}</span>
                                            </div>


                                        </div>


                                        <div class="row">
                                            <div class="col">

                                                <h3 style="padding: 5px;">
                                                    Name: <b>{{ $pro->name }}</b>
                                                </h3>


                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row" style="margin-top: -18px;">
                                            <div class="col">
                                                @can('edit_setmenus')
                                                <a href="{{route('store_admin.update_products',$pro->id)}}"
                                                    style="color: #0b72c6"><b>Edit</b></a>
                                                @endcan

                                            </div>
                                            <div class="col">
                                                @can('delete_setmenus')
                                                <a onclick="if(confirm('Are you sure you want to delete this item?')){ event.preventDefault();document.getElementById('delete-form-{{$pro->id}}').submit(); }"><b
                                                        style="color: red">Delete</b></a>
                                                <form method="post" action="{{route('store_admin.delete_product')}}"
                                                    id="delete-form-{{$pro->id}}" style="display: none">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" value="{{$pro->id}}" name="id">
                                                </form>
                                                @endcan
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                    </div>

                </div>

                <div class="row" id="content2">

                    <div class="tab-pane fade show active" id="home2" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">


                            @php $i=1 @endphp
                            @foreach($productsnonveg as $nonveg)

                            <div class="col-md-3 target">

                                <div class="card">
                                    <!-- Card body -->
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <img src="{{asset($nonveg->image_url !=NULL ? $nonveg->image_url:'themes/default/images/all-img/empty.png')}}"
                                                    width="121" height="121" style="border-radius: 15px">
                                            </div>

                                            <div class="col text-right">
                                                Price:
                                                @include('layouts.render.currency',["amount"=>$nonveg->price])<br>
                                                Recommended &nbsp;<span
                                                    class="badge badge-{{$nonveg->is_recommended == 1 ? "
                                                    success":"danger"}}">{{$nonveg->is_recommended == 1 ?
                                                    "Yes":"No"}}</span>
                                                <br>
                                                Enabled &nbsp;<span class="badge badge-{{$nonveg->is_active == 1 ? "
                                                    success":"danger"}}">{{$nonveg->is_active == 1 ? "Yes":"No"}}</span>
                                                <br>
                                                Veg &nbsp;<span class="badge badge-{{$nonveg->is_veg == 1 ? "
                                                    success":"danger"}}">{{$nonveg->is_veg == 1 ? "Yes":"No"}}</span>
                                            </div>


                                        </div>


                                        <div class="row">
                                            <div class="col">

                                                <h3 style="padding: 5px;">
                                                    Name: <b>{{ $nonveg->name }}</b>
                                                </h3>


                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row" style="margin-top: -18px;">
                                            <div class="col">
                                                @can('edit_setmenus')
                                                <a href="{{route('store_admin.update_products',$nonveg->id)}}"
                                                    style="color: #0b72c6"><b>Edit</b></a>
                                                @endcan

                                            </div>
                                            <div class="col">
                                                @can('delete_setmenus')
                                                <a onclick="if(confirm('Are you sure you want to delete this item?')){ event.preventDefault();document.getElementById('delete-form-{{$nonveg->id}}').submit(); }"><b
                                                        style="color: red">Delete</b></a>
                                                <form method="post" action="{{route('store_admin.delete_product')}}"
                                                    id="delete-form-{{$nonveg->id}}" style="display: none">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" value="{{$nonveg->id}}" name="id">
                                                </form>
                                                @endcan
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                    </div>

                </div>

                <div class="row" id="content3">

                    <div class="tab-pane fade show active" id="home2" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">


                            @php $i=1 @endphp
                            @foreach($productsveg as $veg)

                            <div class="col-md-3 target">

                                <div class="card">
                                    <!-- Card body -->
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <img src="{{asset($veg->image_url !=NULL ? $veg->image_url:'themes/default/images/all-img/empty.png')}}"
                                                    width="121" height="121" style="border-radius: 15px">
                                            </div>

                                            <div class="col text-right">
                                                Price: @include('layouts.render.currency',["amount"=>$veg->price])<br>
                                                Recommended &nbsp;<span
                                                    class="badge badge-{{$veg->is_recommended == 1 ? "
                                                    success":"danger"}}">{{$veg->is_recommended == 1 ?
                                                    "Yes":"No"}}</span>
                                                <br>
                                                Enabled &nbsp;<span class="badge badge-{{$veg->is_active == 1 ? "
                                                    success":"danger"}}">{{$veg->is_active == 1 ? "Yes":"No"}}</span>
                                                <br>
                                                Veg &nbsp;<span class="badge badge-{{$veg->is_veg == 1 ? "
                                                    success":"danger"}}">{{$veg->is_veg == 1 ? "Yes":"No"}}</span>
                                            </div>


                                        </div>


                                        <div class="row">
                                            <div class="col">

                                                <h3 style="padding: 5px;">
                                                    Name: <b>{{ $veg->name }}</b>
                                                </h3>


                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row" style="margin-top: -18px;">
                                            <div class="col">
                                                @can('edit_setmenus')
                                                <a href="{{route('store_admin.update_products',$veg->id)}}"
                                                    style="color: #0b72c6" class="text-muted"><b>Edit</b></a>
                                                @endcan

                                            </div>
                                            <div class="col">
                                                @can('delete_setmenus')
                                                <a onclick="if(confirm('Are you sure you want to delete this item?')){ event.preventDefault();document.getElementById('delete-form-{{$pro->id}}').submit(); }"><b
                                                        style="color: red">Delete</b></a>
                                                <form method="post" action="{{route('store_admin.delete_product')}}"
                                                    id="delete-form-{{$veg->id}}" style="display: none">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" value="{{$veg->id}}" name="id">
                                                </form>
                                                @endcan
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                    </div>


                </div>

                <div class="row" id="content4">

                    <div class="tab-pane fade show active" id="home2" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">


                            @php $i=1 @endphp
                            @foreach($productsdisabled as $prodesabled)

                            <div class="col-md-3 target">

                                <div class="card">
                                    <!-- Card body -->
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <img src="{{asset($prodesabled->image_url !=NULL ? $prodesabled->image_url:'themes/default/images/all-img/empty.png')}}"
                                                    width="121" height="121" style="border-radius: 15px">
                                            </div>

                                            <div class="col text-right">
                                                Price:
                                                @include('layouts.render.currency',["amount"=>$prodesabled->price])<br>
                                                Recommended &nbsp;<span
                                                    class="badge badge-{{$prodesabled->is_recommended == 1 ? "
                                                    success":"danger"}}">{{$prodesabled->is_recommended == 1 ?
                                                    "Yes":"No"}}</span>
                                                <br>
                                                Enabled &nbsp;<span
                                                    class="badge badge-{{$prodesabled->is_active == 1 ? "
                                                    success":"danger"}}">{{$prodesabled->is_active == 1 ?
                                                    "Yes":"No"}}</span>
                                                <br>
                                                Veg &nbsp;<span class="badge badge-{{$prodesabled->is_veg == 1 ? "
                                                    success":"danger"}}">{{$prodesabled->is_veg == 1 ?
                                                    "Yes":"No"}}</span>
                                            </div>


                                        </div>


                                        <div class="row">
                                            <div class="col">

                                                <h3 style="padding: 5px;">
                                                    Name: <b>{{ $prodesabled->name }}</b>
                                                </h3>


                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row" style="margin-top: -18px;">
                                            <div class="col">
                                                @can('edit_setmenus')
                                                <a href="{{route('store_admin.update_products',$prodesabled->id)}}"
                                                    style="color: #0b72c6"><b>Edit</b></a>
                                                @endcan

                                            </div>
                                            <div class="col">
                                                @can('delete_setmenus')
                                                <a onclick="if(confirm('Are you sure you want to delete this item?')){ event.preventDefault();document.getElementById('delete-form-{{$prodesabled->id}}').submit(); }"><b
                                                        style="color: red">Delete</b></a>
                                                <form method="post" action="{{route('store_admin.delete_product')}}"
                                                    id="delete-form-{{$prodesabled->id}}" style="display: none">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" value="{{$prodesabled->id}}" name="id">
                                                </form>
                                                @endcan
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                    </div>

                </div>








            </div>

        </div>
    </div>






@can('add_setmenus')
<a href="{{route('store_admin.addsetmenu')}}" class="float btn-primary-appetizr">
    <i class="fa fa-plus my-float"></i>
</a>
@endcan

</div>

<script>
    // Set home as default active page content
            var activeContent = document.getElementById('tutorials');
            activeContent.style.display = 'block';

            // Add active class to home button
            var activeButton = document.getElementById('active-button');
            activeButton.classList.add('active');

            // Show or hide page content on click event
            function openContent(event, contentId){
            var i;

            // Loop through and hide page content
            var contentPage = document.getElementsByClassName('content-page');
            for (i = 0; i < contentPage.length; i++){
            contentPage[i].style.display = 'none';
        }

            // Loop through content buttons and replace the active class to empty
            contentButton = document.getElementsByClassName('content-button');
            for (i = 0; i < contentButton.length; i++){
            contentButton[i].className = contentButton[i].className.replace('active', '');
        }

            // Loop through HTML id's to show the element
            // with an active class during and event

            document.getElementById(contentId).style.display = 'block';
            event.currentTarget.className += ' active';
        }
</script>


<script>
    function myFunction() {
            var input = document.getElementById("Search");
            var filter = input.value.toLowerCase();
            var nodes = document.getElementsByClassName('target');

            for (i = 0; i < nodes.length; i++) {
                if (nodes[i].innerText.toLowerCase().includes(filter)) {
                    nodes[i].style.display = "block";
                } else {
                    nodes[i].style.display = "none";
                }
            }
        }
</script>


@endsection
