<!-- Authentication Links -->
@guest('store')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('store.login') }}">{{ __('Store Login') }}</a>
    </li>
@else
    <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            {{ \Illuminate\Support\Facades\Auth::guard('store')->user()->store_id }}
            <span class="caret"></span>
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ route('store.logout') }}"
               onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('store.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </li>
@endguest