@extends("admin.adminlayout")

@section('admin_content')

    <div class="container-fluid">
        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <div class="row">
                    <div class="col-6">
                        <h3 class="mb-0">Store: {{ $store->store_name }}</h3>
                    </div>

                </div>
            </div>
            <!-- Light table -->
            <div class="table-responsive">
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th class="">Id</th>
                            <th class="">Name of Bank</th>
                            <td class="">IBAN</td>
                            <th class="">BIC</th>
                            <th class="">Account Holder Name</th>
                            <th class="">Added</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bank_details as $detail)
                            <tr>
                                <td>
                                    <span class="text-muted">{{ $loop->iteration }}</span>
                                </td>
                                <td> <span class="text-muted">{{ $detail->name_of_bank }}</span></td>
                                <td>{{ $detail->iban }}</td>
                                <td>{{ $detail->bic }}</td>
                                <td>{{ $detail->account_holder_name }}</td>
                                <td>{{ $detail->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td rowspan="6">No Data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
