<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-dark bg-dark" id="sidenav-main">
    <div class="scrollbar-inner">
        <!-- Brand -->
        <div class="sidenav-header d-flex align-items-center">
            <a class="navbar-brand">
                <h2 class="text-white">{{ __('chef.adminpanel') }}</h2>
            </a>
            <div class="ml-auto">
                <!-- Sidenav toggler -->
                <div class="sidenav-toggler d-none d-xl-block active" data-action="sidenav-unpin"
                    data-target="#sidenav-main">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line" style="background-color: #ffffff;"></i>
                        <i class="sidenav-toggler-line" style="background-color: #ffffff;"></i>
                        <i class="sidenav-toggler-line" style="background-color: #ffffff;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Nav items -->
                <ul class="navbar-nav">
                    @can('view_dashboard')
                        <li class="nav-item">
                            <a @if ($root == 'dashboard') class="nav-link active" @endif class="nav-link"
                                href={{ route('dashboard') }}>
                                <i class="fab fa-delicious text-blue"></i>
                                <span class="nav-link-text">{{ __('chef.dashboard') }}</span>
                            </a>
                        </li>
                    @endcan

                    {{-- <li class="nav-item">
                        <a @if ($root == 'sliders') class="nav-link active" @endif class="nav-link"
                            href={{ route('all_sliders') }}>
                            <i class="ni ni-album-2 text-green"></i>
                            <span class="nav-link-text">Sliders</span>
                        </a>
                    </li> --}}

                    <li class="nav-item">
                        <a @if ($root == 'store') class="nav-link active" @endif class="nav-link" href="#navbar-examples" data-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="navbar-examples">
                            <i class="fas fa-store text-orange"></i>
                            <span class="nav-link-text">{{ __('chef.store') }}</span>
                        </a>
                        <div class="collapse" id="navbar-examples">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('add_store') }}" class="nav-link">{{ __('chef.addstore') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('all_stores') }}" class="nav-link">{{ __('chef.allstore') }}</a>
                                </li>

                            </ul>
                        </div>
                    </li>

                    @can('view_roles')
                    <li class="nav-item">
                        <a @if ($root == 'All Roles' || $root == 'All Permissions') class="nav-link active" @endif class="nav-link" href="#navbar-roles-examples" data-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="navbar-roles-examples">
                            <i class="fas fa-list text-orange"></i>
                            <span class="nav-link-text">Role & Permissions</span>
                        </a>
                        <div class="collapse" id="navbar-roles-examples">
                            <ul class="nav nav-sm flex-column">
                                @can('view_roles')
                                <li class="nav-item">
                                    <a @if ($root == 'Roles') class="nav-link active" @endif class="nav-link"
                                        href={{ route('roles.index') }}>
                                        <span class="nav-link-text"> Roles </span>
                                    </a>
                                </li>
                                @endcan
                                @can('view_permissions')
                                    <li class="nav-item">
                                        <a @if ($root == 'Permissions') class="nav-link active" @endif class="nav-link"
                                            href={{ route('permissions.index') }}>
                                            <span class="nav-link-text"> Permissions </span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                    @endcan

                    @can('view_users')
                    <li class="nav-item">
                        <a @if ($root == 'All Admin' || $root == 'Add Admin') class="nav-link active" @endif class="nav-link" href="#navbar-admin-examples" data-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="navbar-admin-examples">
                            <i class="fas fa-list text-orange"></i>
                            <span class="nav-link-text">Admins</span>
                        </a>
                        <div class="collapse" id="navbar-admin-examples">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a @if ($root == 'All Admin') class="nav-link active" @endif class="nav-link"
                                        href={{ route('user.index') }}>
                                        <span class="nav-link-text"> All Admin </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a @if ($root == 'Add Admin') class="nav-link active" @endif class="nav-link"
                                        href={{ route('user.create') }}>
                                        <span class="nav-link-text"> Add Admin </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endcan

                    <li class="nav-item">
                        <a @if ($root == 'Allergens') class="nav-link active" @endif class="nav-link"
                            href={{ route('all_allergens') }}>
                            <i class="fas fa-receipt text-cyan"></i>
                            <span class="nav-link-text"> Allergens </span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a @if ($root == 'Subscription') class="nav-link active" @endif class="nav-link"
                            href={{ route('all_subscription') }}>
                            <i class="fas fa-receipt text-cyan"></i>
                            <span class="nav-link-text">{{ __('chef.subscriptions') }}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a @if ($root == 'translations') class="nav-link active" @endif class="nav-link"
                            href="{{ route('translations') }}">
                            <i class="fas fa-language text-red"></i>
                            <span class="nav-link-text">Translations</span>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a @if ($root == 'settings') class="nav-link active" @endif class="nav-link"
                            href="{{ route('settings') }}">
                            <i class="ni ni-settings-gear-65 text-flat-lighter"></i>
                            <span class="nav-link-text">{{ __('chef.settings') }}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();" @if ($root == 'logout') class="nav-link active" @endif class="nav-link">
                            <i class="fas fa-sign-out-alt text-pink"></i>
                            <span class="nav-link-text">Logout</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>

                </ul>
                <!-- Divider -->
            </div>
        </div>
    </div>
</nav>
