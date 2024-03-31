@extends("admin.adminlayout")

@section('admin_content')

    <div class="container-fluid">




        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <div class="row">
                    <div class="col-6">
                        <h3 class="mb-0">All Stores</h3>
                    </div>

                </div>
            </div>
            <!-- Light table -->
            <div class="table-responsive">
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">Id</th>
                            <th class="text-center">Store Name</th>
                            <td class="text-center"> Logo</td>
                            <th class="text-center"> Store Email</th>

                            <th class="text-center">Phone No</th>
                            <th class="text-center">Subscription End Date</th>
                            <th class="text-center">Visibility</th>
                            <th class="text-center">Role</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>



                        @php $i=1 @endphp
                        @foreach ($stores as $store)
                            <tr>

                                <td>
                                    <span class="text-muted">{{ $i++ }}</span>
                                </td>
                                <td> <span class="text-muted">{{ $store->store_name }}</span></td>
                                <td class="table-user text-center">
                                    {{-- <img src="{{ asset($store->logo_url) }}" class="avatar rounded-circle mr-3"> --}}
                                    <img src="{{ $store->logo_url !=NULL ? asset($store->logo_url) : asset("assets/images/avatar/1.jpg") }}" class="avatar rounded-circle mr-3">
                                </td>


                                <td>{{ $store->email }}</td>
                                <td class="text-center">{{ $store->phone }}</td>
                                <td class="text-center">{{ date('d-m-Y', strtotime($store->subscription_end_date)) }}</td>
                                <td class="text-center">
                                    @if ($store->subscription_end_date < date('Y-m-d'))
                                        <span class="badge badge-warning">EXPIRED</span>
                                    @else
                                        <span
                                            class="badge badge-{{ $store->is_visible == 1 ? 'success' : 'danger' }}">{{ $store->is_visible == 1 ? 'LIVE' : 'HIDDEN' }}</span>
                                    @endif
                                </td>
                                <td class="text-center">{{$store->roles[0]->name}}</td>
                                <td class="text-center">
                                    <a href="{{ route('view_bank_details', ['store' => $store->id]) }}"
                                        class="table-action" data-toggle="tooltip" data-original-title="View Bank Details">
                                        <i class="fas fa-money-check-alt"></i>
                                    </a>
                                    <a href="{{ route('view_store', ['view_id' => $store->view_id]) }}"
                                        class="table-action" data-toggle="tooltip" data-original-title="View Menu">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('download_qr', ['view_id' => $store->view_id]) }}"
                                        class="table-action" data-toggle="tooltip" data-original-title="View Qr-code">
                                        <i class="fas fa-print"></i>
                                    </a>

                                    <a href="{{ route('edit_stores', $store->id) }}" data-toggle="tooltip"
                                        data-original-title="Edit Store">
                                        <i class="fas fa-edit"></i>
                                    </a>
                            </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>
        </div>





    </div>

@endsection
