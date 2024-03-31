<?php

use Illuminate\Database\Seeder;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Translation::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        \App\Translation::create([
            'language_name' => 'English',
            'data' => '{"language_name":"English","home":"Home","how_it_works":"How It Works?","service":"Service","pricing":"Pricing","privacy_policy":"Privacy Policy","login":"Login","register":"Register","home_first_title":"Re-open your restaurants","home_first_sub_title":"Random Text","register_footer_subtitle":"Create Account to get started.","create_menu":"Create Menu","create_menu_footer_subtitle":"Create your menu visible for your customers.","print_qr_code":"Print QR Code","print_qr_code_footer_subtitle":"Put the printed tags on your tables.","receive_orders":"Receive Orders","receive_orders_footer_subtitle":"When they order something, you get notified instantly!","why_contactless_menu":"Why Contactless Menu?","safety_first":"Safety First","safety_first_sub_title":"Limiting the use of physical menus and promoting touchless QR Code menus reduces the risk of virus transmission, and keeps your customers and employees safe.","no_app_download_required":"No App Download Required","no_app_download_required_sub_title":"Your diners can scan the QR Code using their phone\'s camera","easy_to_build_update":"Easy To Build & Update","easy_to_build_update_sub_title":"Create contactless menu QR Codes under 3 minutes. Later, upload & save a new menu to the same QR Code.","inspires_the_confidence":"Inspires The Confidence To Step Out","inspires_the_confidence_sub_title":"Re-align your restaurant functioning with contactless at the core.","give_us_call":"Give us a Call","send_us_message":"Send us a Message","visit_our_location":"Visit our Location","crafted_with_love":"Crafted with <i class="fa fa-heart text-danger"></i> by","benefits_contactless_menu":"Benefits Of A Contactless Menu","safer_to_use":"Safer To Use","safer_to_use_sub_title":"Germ-free, greener, quicker and safer than the traditional menu.","covid_compliant":"COVID Compliant","covid_compliant_sub_title":"COVID compliance without single use paper menus or disinfectant.","easy_to_update":"Easy To Update","easy_to_update_sub_title":"Use the menu builder to instantly change your menu. No re-prints!","see_a_demo_menu":"See A Demo Online Menu","see_a_demo_menu_point1":"Use the phone camera or QR Application to scan the code.","see_a_demo_menu_point2":"Scroll around the menu and make your order.","see_a_demo_menu_point3":"Your order is instantly received, and it\\u2019s coming!","call_the_waiter":"Call the Waiter","search_products":"Search for Products...","menu_categories":"Categories","menu_promo":"Promos for you","menu_recommend":"Recommend for you","menu_custom":"CUSTOM","menu_name":"Name","menu_phone_number":"Phone Number","menu_comment":"Comment","select_your_table":"Select Your Table","enter_your_table_code":"Enter Your Table Code","table_code_error_message":"INVALID TABLE CODE\\/PLEASE ENTER A VALID CODE","menu_subtotal":"Subtotal","menu_service_charge":"Service Charge","menu_tax":"Give us a Call","menu_total_cost":"Total Cost","menu_confirm_order":"Confirm your order.","menu":"Menu","cart":"Cart","my_order":"My Order","customizable":"CUSTOMIZABLE","available":"AVAILABLE","not_available":"NOT AVAILABLE","recommended":"RECOMMENDED","menu_close":"Close","menu_save_changes":"Save Changes","menu_add_to_cart":"Add to Cart","item_add_to_cart":"Item Added To Cart","menu_rec":"REC","menu_order_successmsg":"Order Placed Successfully.","menu_check_orderstatus":"Check Order Status","menu_cart_empty":"Your Cart is empty.","back_to_menu":"Back to Menu","menu_mrp":"MRP","cooking_time":"Cooking Time","cooking_time_unit":"Cooking Time","menu_product_details":"Product Details","menu_maybe_you_likethis":"Maybe You Like this.","menu_category_items":"Items","menu_customizations_text":"Customization","menu_search_order":"Search Order","menu_current_order":"Current Order","menu_completed_order":"Completed Order","menu_order_id":"Order ID","menu_store":"Store","menu_bill_amount":"Bill Amount","call_the_waite_now":"Call Now","calling_waiter_msg":"calling waiter ....","order_status_pending":"Pending","order_status_accepted":"Accepted","order_status_ready":"Ready to Serve","order_status_completed":"Completed"}',
            'is_rlt' => 0,
            'is_active' => 1,
            'is_default' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
