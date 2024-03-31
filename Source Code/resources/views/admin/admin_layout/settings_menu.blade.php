<div class="col-lg-4 col-md-6">
    <div class="left-side-tabs">
        <div class="dashboard-left-links">
            <a href="{{route('settings')}}" class="user-item {{Request::is('admin/dashboard/settings') ? 'active' : '' }}">Site Settings</a>
            <a href="{{route('account_settings')}}" class="user-item {{Request::is('admin/dashboard/settings/account') ? 'active' : '' }}">Account Settings</a>
            <a href="{{route('paymentsettings')}}" class="user-item {{Request::is('admin/dashboard/settings/payment') ? 'active' : '' }}"> Payment Settings</a>
            <a href="{{route('whatsapp')}}" class="user-item {{Request::is('admin/dashboard/settings/whatsapp') ? 'active' : '' }}"> Whatsapp Notification Settings</a>
            <a href="{{route('privacy_policy')}}" class="user-item {{Request::is('admin/dashboard/settings/privacy') ? 'active' : '' }}">  Privacy Policy</a>
            <a href="{{route('registration_policy')}}" class="user-item {{Request::is('admin/dashboard/settings/registration') ? 'active' : '' }}">  Registration Policy</a>
            <a href="{{route('cache_settings')}}" class="user-item {{Request::is('admin/dashboard/settings/cache') ? 'active' : '' }}">  Cache Settings</a>

        </div>
    </div>
</div>