@extends("restaurants.layouts.restaurantslayout")

@section('restaurantcontant')


    <div class="container-fluid">

        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">

                <div class="row">
                    <div class="col-6">
                        <h3 class="mb-0">Available Plans
                            <span
                                class="badge badge-md badge-circle badge-floating badge-info border-white">{{ $subscription_count }}</span>
                        </h3>

                    </div>

                    <div class="col-6 text-right">
                        <button onclick="event.preventDefault(); document.getElementById('add_new').submit();"
                            class="btn btn-sm btn-primary btn-round btn-icon" data-toggle="tooltip"
                            data-original-title="History">

                            <span class="btn-inner--icon"><i class="fas fa-receipt"></i></span>
                            <span class="btn-inner--text">History</span>
                        </button>
                        <form action="{{ route('store_admin.subscription_history') }}" method="get" id="add_new"></form>
                    </div>



                </div>
            </div>
            <!-- Light table -->
            <div class="table-responsive">
                @if (session()->has('MSG'))
                    <div class="alert alert-{{ session()->get('TYPE') }}">
                        <strong> <a>{{ session()->get('MSG') }}</a></strong>
                    </div>
                @endif
                <table class="table align-items-center table-flush text-center">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Plan Name</th>
                            {{-- <th>Price</th> --}}
                            <th>No of Days</th>
                            <th>Compete Payment</th>
                        </tr>
                    </thead>
                    <tbody>

                        @php $i=1 @endphp
                        @foreach ($subscription as $data)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $data->name }}</td>
                                {{-- <td>{{ $data->price }} </td> --}}
                                <td>
                                    <span class="badge badge-danger">{{ $data->days }} Days</span>
                                </td>
                                @if (auth()->user()->isFrance())
                                    <td style="text-align: center">
                                        <p>Not Availiable</p>
                                    </td>
                                @else
                                    <td style="text-align: center">
                                        <button {{ $isStripeEnabled != 1 ? 'disabled' : null }}
                                            onclick="triggerStripePayment({{ $data->id }})"
                                            class="btn btn-success btn-sm text-white">Stripe</button>
                                        @if ($razorpayEnabled == 1)
                                            <button
                                                style="border: none;background: none;color: inherit;border: none;padding: 0;font: inherit;cursor: pointer;outline: inherit;">
                                                <form
                                                    action="{{ route('store_admin.subscription_razorpay_complete_payment') }}"
                                                    method="POST" id="razorpay-payment">
                                                    @csrf
                                                    <input type="hidden" name="selected_plan" value="{{ $data->id }}">
                                                    <script src="https://checkout.razorpay.com/v1/checkout.js"
                                                        data-key={{ $razorpay_key_id }}
                                                        data-amount={{ explode('.', $data->price)[0] . explode('.', $data->price)[1] }}
                                                        data-currency={{ strtoupper($currency) }}
                                                        data-buttontext="Razorpay"
                                                        data-style="btn btn-success btn-sm text-white"
                                                        data-name={{ $data->name }} data-description="Razorpay"
                                                        data-image="{{ asset($logo) }}"
                                                        data-prefill.name="{{ auth()->user()->store_name }}"
                                                        data-prefill.email={{ auth()->user()->email }}>
                                                    </script>
                                                </form>
                                            </button>
                                        @else
                                            <button disabled class="btn btn-success btn-sm text-white">Razorpay</button>
                                        @endif
                                        {{-- onclick="document.getElementById('form-subscription-{{$data->id}}').submit();" --}}
                                    </td>
                                    <form method="post" id="form-subscription-{{ $data->id }}"
                                        action="{{ route('store_admin.subscription_complete_payment') }}">
                                        <input name="plan_id" value="{{ $data->id }}" style="visibility:hidden" />
                                        @csrf
                                    </form>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
