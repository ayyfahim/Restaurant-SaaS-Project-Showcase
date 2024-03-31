@extends("waiters.layouts.waiterslayout")

@section('waiterscontent')



    <div class="container-fluid">
        <div class="tab-pane fade show active" id="home2" role="tabpanel" aria-labelledby="home-tab">
            <div class="row">


                @php $i=1 @endphp
                @foreach ($calls as $data)

                    <div class="col-md-3">

                        <div class="card">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <b>{{ $data->customer_name }}</b>
                                    </div>
                                    <div class="col-auto">
                                        @if ($data->is_completed == 0)
                                            <a class="btn btn-outline-success btn-sm"
                                                onclick="document.getElementById('complete-waiter-{{ $data->id }}').submit();"><i
                                                    class="fas fa-check"></i>
                                            </a>
                                        @endif
                                        @if ($data->is_completed == 1)
                                            <a class="btn bg-gradient-success btn-sm text-white"><i
                                                    class="fas fa-check-double"></i>
                                            </a>
                                        @endif
                                        <form style="visibility: hidden" method="post"
                                            action="{{ route('waiter_admin.update_waiter_call_status_order', ['id' => $data->id]) }}"
                                            id="compete-waiter-{{ $data->id }}">
                                            @csrf
                                            @method('patch')
                                            <input style="visibility:hidden" name="is_completed" type="hidden" value="1">
                                        </form>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col">

                                        <span class="h6 surtitle text-muted">
                                            CUSTOMER PHONE
                                        </span>
                                        <div class="h4">{{ $data->customer_phone }}</div>

                                    </div>

                                    <div class="col">

                                        <span class="h6 surtitle text-muted">
                                            Table No .
                                        </span>
                                        <div class="h4">{{ $data->table_name }}</div>

                                    </div>

                                </div>


                                <div class="row">
                                    <div class="col">

                                        <span class="h6 surtitle text-muted">
                                            Comment .
                                        </span>
                                        <div class="h4">{{ $data->comment }}</div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>










    <script language="javascript">
        setTimeout(function() {
            window.location.reload(1);
        }, 10000);

    </script>

    </div>

@endsection
