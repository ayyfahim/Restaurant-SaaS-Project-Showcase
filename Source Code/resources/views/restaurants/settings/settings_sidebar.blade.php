<div class="dashboard-left-links">
    @can('store_settings')
    <a href="{{ route('store_admin.settings') }}" class="user-item {{ Request::is('admin/store/settings') ? 'active' : '' }}">Store Settings</a>
    @endcan
    @can('add_open_hours')
    <a href="{{ route('store_admin.add_open_hours') }}" class="user-item {{ Request::is('admin/store/settings/add-open-hours') ? 'active' : '' }}">Add Open Hours</a>
    @endcan
    @can('add_bank_details')
    <a href="{{ route('store_admin.add_bank_details') }}" class="user-item {{ Request::is('admin/store/settings/add-bank-details') ? 'active' : '' }}">Add Bank Details</a>
    @endcan
    @can('deliverect_setting')
    <a href="{{ route('store_admin.add_deliverect') }}" class="user-item {{ Request::is('admin/store/settings/deliverect') ? 'active' : '' }}">Deliverect</a>
    @endcan
    @can('add_store_location')
    <a href="{{ route('store_admin.add_store_location') }}" class="user-item {{ Request::is('admin/store/settings/add-store-location') ? 'active' : '' }}">Add Store Location</a>
    @endcan
</div>
