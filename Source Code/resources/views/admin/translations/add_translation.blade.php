@extends("admin.adminlayout")

@section("admin_content")


    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12 col-md-6">
                <div class="card card-static-2 mb-30">
                    <div class="card-body">
                        @if(session()->has("MSG"))
                            <div class="alert alert-{{session()->get("TYPE")}}">
                                <strong> <a>{{session()->get("MSG")}}</a></strong>
                            </div>
                        @endif
                        @if($errors->any()) @include('admin.admin_layout.form_error') @endif

                        <form class="form-horizontal" method="post" action="{{route('add_translations')}}"
                              enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">Name </label>
                                        <input type="text" name="language_name" class="form-control"
                                               placeholder="Language Name" required>
                                    </div>
                                </div>
                                {{--                                <div class="col-lg-3">--}}
                                {{--                                    <div class="form-group">--}}

                                {{--                                        <label class="form-control-label">RLT : On/Off</label><br>--}}
                                {{--                                        <label class="custom-toggle">--}}
                                {{--                                            <input type="checkbox"  name="is_rlt">--}}
                                {{--                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>--}}
                                {{--                                        </label>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                <div class="col-lg-3" style="margin-top: 20px;">
                                    <div class="form-group">

                                        <label class="form-control-label">Default : On/Off</label><br>
                                        <label class="custom-toggle">
                                            <input type="checkbox" name="is_default">
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No"
                                                  data-label-on="Yes"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-3" style="margin-top: 20px;">
                                    <div class="form-group">

                                        <label class="form-control-label">Enable : On/Off</label><br>
                                        <label class="custom-toggle">
                                            <input type="checkbox" checked name="is_active">
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No"
                                                  data-label-on="Yes"></span>
                                        </label>
                                    </div>
                                </div>

                            </div>
                            <div class="card-header bg-gradient-gray-dark text-white">Home Page</div>


                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card">

                                        <div class="card-body">

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Home</label>
                                                <input type="text" name="home" class="form-control" value="Home"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">How It
                                                    Works?</label>
                                                <input type="text" name="how_it_works" class="form-control"
                                                       value="How It Works?" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Service</label>
                                                <input type="text" name="service" class="form-control" value="Service"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Pricing</label>
                                                <input type="text" name="pricing" class="form-control" value="Pricing"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Privacy
                                                    Policy</label>
                                                <input type="text" name="privacy_policy" class="form-control"
                                                       value="Privacy Policy" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Login</label>
                                                <input type="text" name="login" class="form-control" value="Login"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Register</label>
                                                <input type="text" name="register" class="form-control" value="Register"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Home First
                                                    Tittle </label>
                                                <input type="text" name="home_first_title" class="form-control"
                                                       value="Re-open your restaurants" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-email">Home First
                                                    Sub-Tittle</label>
                                                <textarea name="home_first_sub_title" class="form-control" rows="3"
                                                          required>With a contactless CHEF <b>MENU</b>.<br>Make your restaurant a safe place to eat or grab-and-go by deploying a touch-free QR Code menu.</textarea>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">How It Works? </label>
                                                <input type="text" name="how_it_works" class="form-control"
                                                       value="How It Works?" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Create Account to get started.</label>
                                                <input type="text" name="register_footer_subtitle" class="form-control"
                                                       value="Create Account to get started." required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Create Menu</label>
                                                <input type="text" name="create_menu" class="form-control"
                                                       value="Create Menu" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Create Menu (Subtitle)</label>
                                                <input type="text" name="create_menu_footer_subtitle" class="form-control"
                                                       value="Create your menu visible for your customers." required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Print QR Code</label>
                                                <input type="text" name="print_qr_code" class="form-control"
                                                       value="Print QR Code" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Print QR Code (Subtitle)</label>
                                                <input type="text" name="print_qr_code_footer_subtitle" class="form-control"
                                                       value="Put the printed tags on your tables." required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Receive Orders</label>
                                                <input type="text" name="receive_orders" class="form-control"
                                                       value="Receive Orders" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Receive Orders (Subtitle)</label>
                                                <input type="text" name="receive_orders_footer_subtitle" class="form-control"
                                                       value="When they order something, you get notified instantly!" required>
                                            </div>



                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="card">

                                        <div class="card-body">

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Why Contactless Menu?</label>
                                                <input type="text" name="why_contactless_menu" class="form-control"
                                                       value="Why Contactless Menu?" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Safety First (heading)</label>
                                                <input type="text" name="safety_first" class="form-control"
                                                       value="Safety First" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-email">Safety First (Sub-Tittle)</label>
                                                <textarea name="safety_first_sub_title" class="form-control" rows="3"
                                                          required>Limiting the use of physical menus and promoting touchless QR Code menus reduces the risk of virus transmission, and keeps your customers and employees safe.</textarea>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">No App Download Required (heading)</label>
                                                <input type="text" name="no_app_download_required" class="form-control"
                                                       value="No App Download Required" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-email">No App Download Required (Sub-Tittle)</label>
                                                <textarea name="no_app_download_required_sub_title" class="form-control" rows="2"
                                                          required>Your diners can scan the QR Code using their phone's camera</textarea>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Easy To Build & Update (heading)</label>
                                                <input type="text" name="easy_to_build_update" class="form-control"
                                                       value="Easy To Build & Update" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-email">Easy To Build & Update (Sub-Tittle)</label>
                                                <textarea name="easy_to_build_update_sub_title" class="form-control" rows="3"
                                                          required>Create contactless menu QR Codes under 3 minutes. Later, upload & save a new menu to the same QR Code.</textarea>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Inspires The Confidence To Step Out (heading)</label>
                                                <input type="text" name="inspires_the_confidence" class="form-control"
                                                       value="Inspires The Confidence To Step Out" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-email">Inspires The Confidence To Step Out (Sub-Tittle)</label>
                                                <textarea name="inspires_the_confidence_sub_title" class="form-control" rows="2"
                                                          required>Re-align your restaurant functioning with contactless at the core.</textarea>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Give us a Call (Footer)</label>
                                                <input type="text" name="give_us_call" class="form-control"
                                                       value="Give us a Call" required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Send us a Message (Footer)</label>
                                                <input type="text" name="send_us_message" class="form-control"
                                                       value="Send us a Message" required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Visit our Location (Footer)</label>
                                                <input type="text" name="visit_our_location" class="form-control"
                                                       value="Visit our Location" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Crafted with <i class="fa fa-heart text-danger"></i> by</label>
                                                <input type="text" name="crafted_with_love" class="form-control"
                                                       value="Crafted with <3 by " required>
                                            </div>






                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card">

                                        <div class="card-body">

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Benefits Of A Contactless Menu</label>
                                                <input type="text" name="benefits_contactless_menu" class="form-control" value="Benefits Of A Contactless Menu"
                                                       required>
                                            </div>

{{--                                            code end--}}

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Safer To Use (Heading)</label>
                                                <input type="text" name="safer_to_use" class="form-control" value="Safer To Use"
                                                       required>
                                            </div>


{{--                                            code end--}}

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Safer To Use (Sub-Tittle)</label>
                                                <input type="text" name="safer_to_use_sub_title" class="form-control" value="Germ-free, greener, quicker and safer than the traditional menu."
                                                       required>
                                            </div>
{{--                                            code end--}}

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">COVID Compliant (Heading)</label>
                                                <input type="text" name="covid_compliant" class="form-control" value="COVID Compliant"
                                                       required>
                                            </div>
{{--                                            code end--}}
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">COVID Compliant (Sub-Tittle)</label>
                                                <textarea rows="2" name="covid_compliant_sub_title" class="form-control"
                                                          required>COVID compliance without single use paper menus or disinfectant.</textarea>
                                            </div>
{{--                                            code end--}}

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Easy To Update (Heading)</label>
                                                <input type="text" name="easy_to_update" class="form-control" value="Easy To Update"
                                                       required>
                                            </div>

{{--                                            code end--}}

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Easy To Update (Sub-Tittle)</label>
                                                <textarea name="easy_to_update_sub_title" class="form-control" required>Use the menu builder to instantly change your menu. No re-prints!</textarea>
                                            </div>
{{--                                            code end--}}
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">See A Demo Online Menu (Title)</label>
                                                <input type="text" name="see_a_demo_menu" class="form-control" value="See A Demo Online Menu"
                                                       required>
                                            </div>

{{--                                            code end--}}

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">See A Demo Online Menu (Point 1)</label>
                                                <textarea name="see_a_demo_menu_point1" class="form-control"
                                                          required>Use the phone camera or QR Application to scan the code.</textarea>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">See A Demo Online Menu (Point 2)</label>
                                                <textarea name="see_a_demo_menu_point2" class="form-control"
                                                          required>Scroll around the menu and make your order.</textarea>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">See A Demo Online Menu (Point 3)</label>
                                                <textarea name="see_a_demo_menu_point3" class="form-control"
                                                          required>Your order is instantly received, and it’s coming!</textarea>
                                            </div>


                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="card-header bg-gradient-gray-dark text-white">Customer Side</div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card">

                                        <div class="card-body">
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Call the waiter</label>
                                                <input type="text" name="call_the_waiter" class="form-control" value="Call the waiter"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Search for Products...</label>
                                                <input type="text" name="search_products" class="form-control" value="Search for Products..."
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Categories</label>
                                                <input type="text" name="menu_categories" class="form-control" value="Categories"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Promos for you</label>
                                                <input type="text" name="menu_promo" class="form-control" value="Promos for you"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Recommend for you</label>
                                                <input type="text" name="menu_recommend" class="form-control" value="Recommend for you"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">CUSTOM</label>
                                                <input type="text" name="menu_custom" class="form-control" value="CUSTOM"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Name</label>
                                                <input type="text" name="menu_name" class="form-control" value="Name"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Phone Number</label>
                                                <input type="text" name="menu_phone_number" class="form-control" value="Phone Number"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Comment</label>
                                                <input type="text" name="menu_comment" class="form-control" value="Comment"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Select Your Table</label>
                                                <input type="text" name="select_your_table" class="form-control" value="Select Your Table"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Enter Your Table Code</label>
                                                <input type="text" name="enter_your_table_code" class="form-control" value="Enter Your Table Code"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Table Code Error Message</label>
                                                <input type="text" name="table_code_error_message" class="form-control" value="INVALID TABLE CODE/PLEASE ENTER A VALID CODE"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Subtotal</label>
                                                <input type="text" name="menu_subtotal" class="form-control" value="Subtotal"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Service Charge</label>
                                                <input type="text" name="menu_service_charge" class="form-control" value="Service Charge"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Tax</label>
                                                <input type="text" name="menu_tax" class="form-control" value="Tax"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Total Cost</label>
                                                <input type="text" name="menu_total_cost" class="form-control" value="Total Cost"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Confirm your order.</label>
                                                <input type="text" name="menu_confirm_order" class="form-control" value="Confirm your order."
                                                       required>
                                            </div>







                                        </div>
                                    </div>
                                </div>



                                <div class="col-md-4">
                                    <div class="card">

                                        <div class="card-body">
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Menu</label>
                                                <input type="text" name="menu" class="form-control" value="Menu"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Cart</label>
                                                <input type="text" name="cart" class="form-control" value="Cart"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">My Order</label>
                                                <input type="text" name="my_order" class="form-control" value="My Order"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Customizable</label>
                                                <input type="text" name="customizable" class="form-control" value="CUSTOMIZABLE"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Available</label>
                                                <input type="text" name="available" class="form-control" value="AVAILABLE"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Not Available</label>
                                                <input type="text" name="not_available" class="form-control" value="NOT AVAILABLE"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Recommended</label>
                                                <input type="text" name="recommended" class="form-control" value="RECOMMENDED"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Close</label>
                                                <input type="text" name="menu_close" class="form-control" value="Close"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Save Changes</label>
                                                <input type="text" name="menu_save_changes" class="form-control" value="Save Changes"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Add to Cart</label>
                                                <input type="text" name="menu_add_to_cart" class="form-control" value="Add to Cart"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Item Add To Cart Message</label>
                                                <input type="text" name="item_add_to_cart" class="form-control" value="Item Added To Cart"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">REC</label>
                                                <input type="text" name="menu_rec" class="form-control" value="REC"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Order Placed Successfully.</label>
                                                <input type="text" name="menu_order_successmsg" class="form-control" value="Order Placed Successfully."
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Check Order Status</label>
                                                <input type="text" name="menu_check_orderstatus" class="form-control" value="Check Order Status"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Your Cart is empty.</label>
                                                <input type="text" name="menu_cart_empty" class="form-control" value="Your Cart is empty."
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Back to Menu</label>
                                                <input type="text" name="back_to_menu" class="form-control" value="Back to Menu"
                                                       required>
                                            </div>







                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="card">

                                        <div class="card-body">
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">MRP</label>
                                                <input type="text" name="menu_mrp" class="form-control" value="MRP"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Cooking Time</label>
                                                <input type="text" name="cooking_time" class="form-control" value="Cooking Time"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Minute</label>
                                                <input type="text" name="cooking_time_unit" class="form-control" value="Cooking Time"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Product Details</label>
                                                <input type="text" name="menu_product_details" class="form-control" value="Product Details"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Maybe You Like this.</label>
                                                <input type="text" name="menu_maybe_you_likethis" class="form-control" value="Maybe You Like this."
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Category Item Text</label>
                                                <input type="text" name="menu_category_items" class="form-control" value="Items"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Customization</label>
                                                <input type="text" name="menu_customizations_text" class="form-control" value="Customization"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Search Order</label>
                                                <input type="text" name="menu_search_order" class="form-control" value="Search Order"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Current Order</label>
                                                <input type="text" name="menu_current_order" class="form-control" value="Current Order"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Completed Order</label>
                                                <input type="text" name="menu_completed_order" class="form-control" value="Completed Order"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Order ID</label>
                                                <input type="text" name="menu_order_id" class="form-control" value="Order ID"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Store</label>
                                                <input type="text" name="menu_store" class="form-control" value="Store"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Bill Amount</label>
                                                <input type="text" name="menu_bill_amount" class="form-control" value="Bill Amount"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Call the Waiter</label>
                                                <input type="text" name="call_the_waiter" class="form-control" value="Call the Waiter"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Call Now</label>
                                                <input type="text" name="call_the_waite_now" class="form-control" value="Call Now"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Calling Waiter Message</label>
                                                <input type="text" name="calling_waiter_msg" class="form-control" value="calling waiter ...."
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Oder Status Text: Pending</label>
                                                <input type="text" name="order_status_pending" class="form-control" value="Pending"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Oder Status Text: Pending</label>
                                                <input type="text" name="order_status_pending" class="form-control" value="Pending"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Oder Status Text: Accepted</label>
                                                <input type="text" name="order_status_accepted" class="form-control" value="Accepted"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Oder Status Text: Ready to Serve</label>
                                                <input type="text" name="order_status_ready" class="form-control" value="Ready to Serve"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Oder Status Text: Completed</label>
                                                <input type="text" name="order_status_completed" class="form-control" value="Completed"
                                                       required>
                                            </div>






                                        </div>
                                    </div>
                                </div>


                            </div>


                            <br>






                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit"
                                            class="btn btn-default btn-flat m-b-30 m-l-5 bg-primary border-none m-r-5 -btn">
                                        Save
                                    </button>
                                </div>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>





@endsection
