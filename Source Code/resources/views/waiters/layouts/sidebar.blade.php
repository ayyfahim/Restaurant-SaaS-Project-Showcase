<style>
    .dashboard_icons {
        width: 20px;
        margin-right: 15px;
    }

    .nav-item.active .nav-link img {
        filter: brightness(0) invert(1);
    }

</style>
<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <!-- Brand -->
        <div class="sidenav-header d-flex align-items-center">
            <a class="navbar-brand">
                {{-- <h2 class="text-white">{{ Auth::user()->store_name }}</h2> --}}
                @include('partials.appetizr_logo_waiter')
            </a>
            <div class="ml-auto">
                <!-- Sidenav toggler -->
                <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line" style="background-color: #000000;"></i>
                        <i class="sidenav-toggler-line" style="background-color: #000000;"></i>
                        <i class="sidenav-toggler-line" style="background-color: #000000;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Nav items -->
                <ul class="navbar-nav">


                    @php
                        $navActiveClass = 'nav-item active';
                    @endphp


                    <li class="{{ Route::currentRouteNamed('waiter_admin.waiter_calls') ? $navActiveClass : null }}">
                        <a class="nav-link" href="{{ route('waiter_admin.waiter_calls') }}">
                            <img class="dashboard_icons" src="{{ asset('images/icons/dashboard/waiter_call.png') }}"
                                alt="waiter_call">
                            <span class="nav-link-text">Waiter Calls</span>
                        </a>
                    </li>

                    <li class="{{ Route::currentRouteNamed('waiter_admin.waiter_shifts') ? $navActiveClass : null }}">
                        <a class="nav-link" href="{{ route('waiter_admin.waiter_shifts') }}">
                            <img class="dashboard_icons" src="{{ asset('images/icons/dashboard/waiters.png') }}"
                                alt="waiter_shifts">
                            <span class="nav-link-text">Current Shifts</span>
                        </a>
                    </li>

                    <li class="{{ Route::currentRouteNamed('waiter_admin.createOrder') ? $navActiveClass : null }}">
                        <a class="nav-link" href="{{ route('waiter_admin.createOrder') }}">
                            <img class="dashboard_icons" src="{{ asset('images/icons/dashboard/orders.png') }}"
                                alt="orders">
                            <span class="nav-link-text">Create Order</span>
                        </a>
                    </li>

                    <li class="{{ Route::currentRouteNamed('waiter.logout') ? $navActiveClass : null }}" style="cursor: pointer;">
                        <a class="nav-link"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <img class="dashboard_icons" src="{{ asset('images/icons/dashboard/logout.png') }}"
                                alt="logout">
                            <span class="nav-link-text"> Logout</span>
                        </a>
                    </li>

                    <form id="logout-form" action="{{ route('waiter.logout') }}" method="POST"
                        style="display: none;">
                        {{ csrf_field() }}
                    </form>

                </ul>




                </ul>
                <!-- Divider -->



            </div>
        </div>
    </div>
</nav>
