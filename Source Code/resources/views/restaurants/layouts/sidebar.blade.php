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
                @include('partials.appetizr_logo')
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

                    @can('view_dashboard')
                    <li class="{{ Route::currentRouteNamed('store_admin.dashboard') ? $navActiveClass : null }}">
                        <a class="nav-link" href="{{ route('store_admin.dashboard') }}">
                            <img class="dashboard_icons" src="{{ asset('images/icons/dashboard/dashboard.png') }}" alt="dashboard">
                            <span class="nav-link-text">Dashboard</span>
                        </a>
                    </li>
                    @endcan

                    @can('view_orders')
                    <li class="{{ Route::currentRouteNamed('store_admin.orders') ? $navActiveClass : null }}">
                        <a class="nav-link" href="{{ route('store_admin.orders') }}">
                            <img class="dashboard_icons" src="{{ asset('images/icons/dashboard/orders.png') }}" alt="orders">
                            <span class="nav-link-text">Orders</span>
                        </a>
                    </li>
                    @endcan

                    @can('view_waiter')
                    <li class="{{ Request::is('admin/store/allwaiters') ? $navActiveClass : null }}">
                        <a class="nav-link" href="{{ route('store_admin.all_waiters') }}">
                            <img class="dashboard_icons" src="{{ asset('images/icons/dashboard/waiters.png') }}" alt="waiters">
                            <span class="nav-link-text">Waiters</span>
                        </a>
                    </li>
                    @endcan

                    @can('view_kitchen')
                    <li class="{{ Request::is('admin/store/allkitchens') ? $navActiveClass : null }}">
                        <a class="nav-link" href="{{ route('store_admin.all_kitchens') }}">
                            <img class="dashboard_icons" src="{{ asset('images/icons/dashboard/kitchens.png') }}" alt="kitchens">
                            <span class="nav-link-text">Kitchens</span>
                        </a>
                    </li>
                    @endcan

                    @can('view_waiter_call')
                    <li class="{{ Request::is('admin/store/waiter/calls') ? $navActiveClass : null }}">
                        <a class="nav-link" href="{{ route('store_admin.waiter_calls') }}">
                            <img class="dashboard_icons" src="{{ asset('images/icons/dashboard/waiter_call.png') }}" alt="waiter_call">
                            <span class="nav-link-text">Waiter Call</span>
                        </a>
                    </li>
                    @endcan

                    @can('view_orders_status')
                    <li class="{{ Request::is('admin/store/orders/status*') ? $navActiveClass : null }}">
                        <a class="nav-link" href="{{ route('store_admin.orderstatus') }}">
                            <img class="dashboard_icons" src="{{ asset('images/icons/dashboard/order_status.png') }}" alt="order_status">
                            <span class="nav-link-text">Orders Status Screen</span>
                        </a>
                    </li>
                    @endcan

                    @can('view_banner')
                    <li class="{{ Route::currentRouteNamed('store_admin.banner') || Route::currentRouteNamed('store_admin.discount') || Route::currentRouteNamed('store_admin.coupon') ? $navActiveClass : null }}">
                        <a class="nav-link" href="{{ route('store_admin.banner') }}">
                            <img class="dashboard_icons" src="{{ asset('images/icons/dashboard/promo_banner.png') }}" alt="promo_banner">
                            <span class="nav-link-text">Discounts and Banners</span>
                        </a>
                    </li>
                    @endcan

                    @can('view_menu')
                    <li class="{{ Route::currentRouteNamed('store_admin.categories') ? $navActiveClass : null }}">
                        <a class="nav-link" href="{{ route('store_admin.categories') }}">
                            <img class="dashboard_icons" src="{{ asset('images/icons/dashboard/menu.png') }}" alt="menu">
                            <span class="nav-link-text">Menu</span>
                        </a>
                    </li>
                    @endcan

                    @can('view_tables')
                    <li class="{{ Route::currentRouteNamed('store_admin.all_tables') ? $navActiveClass : null }}">
                        <a class="nav-link" href="{{ route('store_admin.all_tables') }}">
                            <img class="dashboard_icons" src="{{ asset('images/icons/dashboard/tables.png') }}" alt="tables">
                            <span class="nav-link-text">Tables</span>
                        </a>
                    </li>
                    @endcan

                    @can('view_qr_code')
                    <li class="{{ Route::currentRouteNamed('download_qr') ? $navActiveClass : null }}">
                        <a class="nav-link" href="{{ route('download_qr', [Auth::user()->view_id]) }}">
                            <img class="dashboard_icons" src="{{ asset('images/icons/dashboard/qr_code_builder.png') }}" alt="qr_code_builder">
                            <span class="nav-link-text"> QR-Code Builder</span>
                        </a>
                    </li>
                    @endcan

                    @can('view_subscription')
                    <li class="{{ Route::currentRouteNamed('store_admin.subscription_plans') ? $navActiveClass : null }}">
                        <a class="nav-link" href="{{ route('store_admin.subscription_plans') }}">
                            <img class="dashboard_icons" src="{{ asset('images/icons/dashboard/subscription_plans.png') }}" alt="subscription_plans">
                            <span class="nav-link-text"> Subscription Plans</span>
                        </a>
                    </li>
                    @endcan

                    @can('view_analytics')
                    <li class="{{ Route::currentRouteNamed('store_admin.customers') ? $navActiveClass : null }}">
                        <a class="nav-link" href="{{ route('store_admin.customers') }}">
                            <img class="dashboard_icons" src="{{ asset('images/icons/dashboard/analytics.png') }}" alt="analytics">
                            <span class="nav-link-text"> Analytics</span>
                        </a>
                    </li>
                    @endcan

                    @can('store_settings')
                    <li class="{{ Route::currentRouteNamed('store_admin.settings') ? $navActiveClass : null }}">
                        <a class="nav-link" href="{{ route('store_admin.settings') }}">
                            <img class="dashboard_icons" src="{{ asset('images/icons/dashboard/settings.png') }}" alt="settings">
                            <span class="nav-link-text"> Settings</span>
                        </a>
                    </li>
                    @endcan

                    <li class="{{ Route::currentRouteNamed('store.logout') ? $navActiveClass : null }}" style="cursor: pointer;">
                        <a class="nav-link"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <img class="dashboard_icons" src="{{ asset('images/icons/dashboard/logout.png') }}" alt="logout">
                            <span class="nav-link-text"> Logout</span>
                        </a>
                    </li>

                    <form id="logout-form" action="{{ route('store.logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>

                </ul>




                </ul>
                <!-- Divider -->



            </div>
        </div>
    </div>
</nav>
