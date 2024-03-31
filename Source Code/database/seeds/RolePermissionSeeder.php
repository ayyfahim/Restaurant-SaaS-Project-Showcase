<?php

use App\Models\Store;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Schema::disableForeignKeyConstraints();
        \DB::table('roles')->truncate();
        \DB::table('permissions')->truncate();
        \DB::table('role_has_permissions')->truncate();
        \DB::table('model_has_roles')->truncate();
        \DB::table('model_has_permissions')->truncate();
        Schema::enableForeignKeyConstraints();

        \DB::insert("INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES ('1', 'superadmin', 'web', '2021-10-13 15:16:13', '2021-10-13 15:16:13'), ('2', 'owner', 'web', '2021-10-13 15:16:13', '2021-10-13 15:16:13')");

        \DB::insert("INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES ('1', 'App\\\User', '1')");
        $stores = Store::all();
        foreach ($stores as $key => $store) {
            \DB::insert("INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES ('2', 'App\\\Models\\\store', $store->id)");
        }

        $permissions = [
            //common
            'view_dashboard',
            'view_menu',
            'view_qr_code',
            'view_subscription',

            //admin
            'view_store',
            'add_store',
            'view_bank_details',
            'edit_store',
            'view_roles',
            'add_roles',
            'edit_roles',
            'delete_roles',
            'view_permissions',
            'add_permissions',
            'edit_permissions',
            'delete_permissions',
            'view_users',
            'add_users',
            'edit_users',
            'delete_users',
            'view_allergen',
            'add_allergen',
            'edit_allergen',
            'delete_allergen',
            'add_subscription',
            'edit_subscription',
            'view_translations',
            'add_translations',
            'edit_translations',

            //store
            'view_orders',
            'accept_reject_orders',
            'view_waiter',
            'add_waiter',
            'edit_waiter',
            'delete_waiter',
            'view_kitchen',
            'add_kitchen',
            'edit_kitchen',
            'delete_kitchen',
            'view_waiter_call',
            'completed_waiter_call',
            'view_orders_status',
            'view_banner',
            'add_banner',
            'edit_banner',
            'delete_banner',
            'view_discount',
            'add_discount',
            'edit_discount',
            'delete_discount',
            'view_coupon',
            'add_coupon',
            'edit_coupon',
            'delete_coupon',
            'view_categories',
            'add_categories',
            'edit_categories',
            'delete_categories',
            'view_products',
            'add_products',
            'edit_products',
            'delete_products',
            'view_setmenus',
            'add_setmenus',
            'edit_setmenus',
            'delete_setmenus',
            'view_addon_categories',
            'add_addon_categories',
            'edit_addon_categories',
            'delete_addon_categories',
            'view_timerestrictions',
            'add_timerestrictions',
            'edit_timerestrictions',
            'delete_timerestrictions',
            'view_food_menues',
            'add_food_menues',
            'edit_food_menues',
            'delete_food_menues',
            'view_tables',
            'add_tables',
            'edit_tables',
            'view_table_report',
            'set_waiter',
            'view_analytics',
            'store_settings',
            'view_takeaway',
            'update_takeaway',
            'add_open_hours',
            'add_bank_details',
            'deliverect_setting',
            'add_store_location'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        \DB::insert("INSERT INTO role_has_permissions (`permission_id`, `role_id`) SELECT `permissions`.id AS permission_id, '1' FROM `permissions`");
        \DB::insert("INSERT INTO role_has_permissions (`permission_id`, `role_id`) SELECT `permissions`.id AS permission_id, '2' FROM `permissions`");

        foreach ($stores as $key => $store) {
            \DB::insert("INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) SELECT `permissions`.id AS permission_id,'App\\\User' AS model_type, $store->id AS model_id FROM `permissions`");
        }

    }
}
