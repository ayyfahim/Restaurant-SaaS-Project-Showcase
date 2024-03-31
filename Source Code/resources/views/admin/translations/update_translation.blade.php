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

                            <form class="form-horizontal" method="post" action="{{route('update_translation',['id'=>$data->id])}}" enctype="multipart/form-data">
                                {{csrf_field()}}
                                @method('patch')
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">Name </label>
                                        <input type="text" name="language_name" class="form-control"
                                               placeholder="Language Name" value="{{$data->language_name}}" required>
                                    </div>
                                </div>

                                <div class="col-lg-3" style="margin-top: 20px;">
                                    <div class="form-group">

                                        <label class="form-control-label">Default : On/Off</label><br>
                                        <label class="custom-toggle">
                                            <input type="checkbox"  {{$data->is_default == 1 ? "checked":""}} {{$data->is_default == 1 ? "disabled":""}} name="is_default">
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-3" style="margin-top: 20px;">
                                    <div class="form-group">

                                        <label class="form-control-label">Enable : On/Off</label><br>
                                        <label class="custom-toggle">
                                            <input type="checkbox"  {{$data->is_active == 1 ? "checked":""}} name="is_active">
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
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
                                                <input type="text" name="home" class="form-control" value="{{$data->data['home'] ?? ''}}"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">How It
                                                    Works?</label>
                                                <input type="text" name="how_it_works" class="form-control"
                                                       value="{{$data->data['how_it_works'] ?? ''}}" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Service</label>
                                                <input type="text" name="service" class="form-control" value="{{$data->data['service'] ?? ''}}"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Pricing</label>
                                                <input type="text" name="pricing" class="form-control" value="{{$data->data['pricing'] ?? ''}}"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Privacy
                                                    Policy</label>
                                                <input type="text" name="privacy_policy" class="form-control"
                                                       value="{{$data->data['privacy_policy'] ?? ''}}" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Login</label>
                                                <input type="text" name="login" class="form-control" value="{{$data->data['login'] ?? ''}}"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Register</label>
                                                <input type="text" name="register" class="form-control" value="{{$data->data['register'] ?? ''}}"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Home First
                                                    Tittle </label>
                                                <input type="text" name="home_first_title" class="form-control"
                                                       value="{{$data->data['home_first_title'] ?? ''}}" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-email">Home First
                                                    Sub-Tittle</label>
                                                <textarea name="home_first_sub_title" class="form-control" rows="3"
                                                          required>{{$data->data['home_first_sub_title'] ?? ''}}</textarea>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">How It Works? </label>
                                                <input type="text" name="how_it_works" class="form-control"
                                                       value="{{$data->data['how_it_works'] ?? ''}}" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Create Account to get started.</label>
                                                <input type="text" name="register_footer_subtitle" class="form-control"
                                                       value="{{$data->data['register_footer_subtitle'] ?? ''}}" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Create Menu</label>
                                                <input type="text" name="create_menu" class="form-control"
                                                       value="{{$data->data['create_menu'] ?? ''}}" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Create Menu (Subtitle)</label>
                                                <input type="text" name="create_menu_footer_subtitle" class="form-control"
                                                       value="{{$data->data['create_menu_footer_subtitle'] ?? ''}}" required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Print QR Code</label>
                                                <input type="text" name="print_qr_code" class="form-control"
                                                       value="{{$data->data['print_qr_code'] ?? ''}}" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Print QR Code (Subtitle)</label>
                                                <input type="text" name="print_qr_code_footer_subtitle" class="form-control"
                                                       value="{{$data->data['print_qr_code_footer_subtitle'] ?? ''}}" required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Receive Orders</label>
                                                <input type="text" name="receive_orders" class="form-control"
                                                       value="{{$data->data['receive_orders'] ?? ''}}" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Receive Orders (Subtitle)</label>
                                                <input type="text" name="receive_orders_footer_subtitle" class="form-control"
                                                       value="{{$data->data['receive_orders_footer_subtitle'] ?? ''}}" required>
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
                                                       value="{{$data->data['why_contactless_menu'] ?? ''}}" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Safety First (heading)</label>
                                                <input type="text" name="safety_first" class="form-control"
                                                       value="{{$data->data['safety_first'] ?? ''}}" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-email">Safety First (Sub-Tittle)</label>
                                                <textarea name="safety_first_sub_title" class="form-control" rows="3"
                                                          required>{{$data->data['safety_first_sub_title'] ?? ''}}</textarea>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">No App Download Required (heading)</label>
                                                <input type="text" name="no_app_download_required" class="form-control"
                                                       value="{{$data->data['no_app_download_required'] ?? ''}}" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-email">No App Download Required (Sub-Tittle)</label>
                                                <textarea name="no_app_download_required_sub_title" class="form-control" rows="2"
                                                          required>{{$data->data['no_app_download_required_sub_title'] ?? ''}}</textarea>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Easy To Build & Update (heading)</label>
                                                <input type="text" name="easy_to_build_update" class="form-control"
                                                       value="{{$data->data['easy_to_build_update'] ?? ''}}" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-email">Easy To Build & Update (Sub-Tittle)</label>
                                                <textarea name="easy_to_build_update_sub_title" class="form-control" rows="3"
                                                          required>{{$data->data['easy_to_build_update_sub_title'] ?? ''}}</textarea>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Inspires The Confidence To Step Out (heading)</label>
                                                <input type="text" name="inspires_the_confidence" class="form-control"
                                                       value="{{$data->data['inspires_the_confidence'] ?? ''}}" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-email">Inspires The Confidence To Step Out (Sub-Tittle)</label>
                                                <textarea name="inspires_the_confidence_sub_title" class="form-control" rows="2"
                                                          required>{{$data->data['inspires_the_confidence_sub_title'] ?? ''}}</textarea>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Give us a Call (Footer)</label>
                                                <input type="text" name="give_us_call" class="form-control"
                                                       value="{{$data->data['give_us_call'] ?? ''}}" required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Send us a Message (Footer)</label>
                                                <input type="text" name="send_us_message" class="form-control"
                                                       value="{{$data->data['send_us_message'] ?? ''}}" required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Visit our Location (Footer)</label>
                                                <input type="text" name="visit_our_location" class="form-control"
                                                       value="{{$data->data['visit_our_location'] ?? ''}}" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Crafted with <i class="fa fa-heart text-danger"></i> by</label>
                                                <input type="text" name="crafted_with_love" class="form-control"
                                                       value="{{$data->data['crafted_with_love'] ?? ''}}" required>
                                            </div>






                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card">

                                        <div class="card-body">

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Benefits Of A Contactless Menu</label>
                                                <input type="text" name="benefits_contactless_menu" class="form-control" value="{{$data->data['benefits_contactless_menu'] ?? ''}}"
                                                       required>
                                            </div>

                                            {{--                                            code end--}}

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Safer To Use (Heading)</label>
                                                <input type="text" name="safer_to_use" class="form-control" value="{{$data->data['safer_to_use'] ?? ''}}"
                                                       required>
                                            </div>


                                            {{--                                            code end--}}

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Safer To Use (Sub-Tittle)</label>
                                                <input type="text" name="safer_to_use_sub_title" class="form-control" value="{{$data->data['safer_to_use_sub_title'] ?? ''}}"
                                                       required>
                                            </div>
                                            {{--                                            code end--}}

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">COVID Compliant (Heading)</label>
                                                <input type="text" name="covid_compliant" class="form-control" value="{{$data->data['covid_compliant'] ?? ''}}"
                                                       required>
                                            </div>
                                            {{--                                            code end--}}
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">COVID Compliant (Sub-Tittle)</label>
                                                <textarea rows="2" name="covid_compliant_sub_title" class="form-control"
                                                          required>{{$data->data['covid_compliant_sub_title'] ?? ''}}</textarea>
                                            </div>
                                            {{--                                            code end--}}

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Easy To Update (Heading)</label>
                                                <input type="text" name="easy_to_update" class="form-control" value="{{$data->data['easy_to_update'] ?? ''}}"
                                                       required>
                                            </div>

                                            {{--                                            code end--}}

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Easy To Update (Sub-Tittle)</label>
                                                <textarea name="easy_to_update_sub_title" class="form-control" required>{{$data->data['easy_to_update_sub_title'] ?? ''}}</textarea>
                                            </div>
                                            {{--                                            code end--}}
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">See A Demo Online Menu (Title)</label>
                                                <input type="text" name="see_a_demo_menu" class="form-control" value="{{$data->data['see_a_demo_menu'] ?? ''}}"
                                                       required>
                                            </div>

                                            {{--                                            code end--}}

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">See A Demo Online Menu (Point 1)</label>
                                                <textarea name="see_a_demo_menu_point1" class="form-control"
                                                          required>{{$data->data['see_a_demo_menu_point1'] ?? ''}}</textarea>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">See A Demo Online Menu (Point 2)</label>
                                                <textarea name="see_a_demo_menu_point2" class="form-control"
                                                          required>{{$data->data['see_a_demo_menu_point2'] ?? ''}}</textarea>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">See A Demo Online Menu (Point 3)</label>
                                                <textarea name="see_a_demo_menu_point3" class="form-control"
                                                          required>{{$data->data['see_a_demo_menu_point3'] ?? ''}}</textarea>
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
                                                <input type="text" name="call_the_waiter" class="form-control" value="{{$data->data['call_the_waiter'] ?? ''}}"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Search for Products...</label>
                                                <input type="text" name="search_products" class="form-control" value="{{$data->data['search_products'] ?? ''}}"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Categories</label>
                                                <input type="text" name="menu_categories" class="form-control" value="{{$data->data['menu_categories'] ?? ''}}"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Promos for you</label>
                                                <input type="text" name="menu_promo" class="form-control" value="{{$data->data['menu_promo'] ?? ''}}"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Recommend for you</label>
                                                <input type="text" name="menu_recommend" class="form-control" value="{{$data->data['menu_recommend'] ?? ''}}"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">CUSTOM</label>
                                                <input type="text" name="menu_custom" class="form-control" value="{{$data->data['menu_custom'] ?? ''}}"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Name</label>
                                                <input type="text" name="menu_name" class="form-control" value="{{$data->data['menu_name'] ?? ''}}"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Phone Number</label>
                                                <input type="text" name="menu_phone_number" class="form-control" value="{{$data->data['menu_phone_number'] ?? ''}}"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Comment</label>
                                                <input type="text" name="menu_comment" class="form-control" value="{{$data->data['menu_comment'] ?? ''}}"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Select Your Table</label>
                                                <input type="text" name="select_your_table" class="form-control" value="{{$data->data['select_your_table'] ?? ''}}"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Enter Your Table Code</label>
                                                <input type="text" name="enter_your_table_code" class="form-control" value="{{$data->data['enter_your_table_code'] ?? ''}}"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Table Code Error Message</label>
                                                <input type="text" name="table_code_error_message" class="form-control" value="{{$data->data['table_code_error_message'] ?? ''}}"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Subtotal</label>
                                                <input type="text" name="menu_subtotal" class="form-control" value="{{$data->data['menu_subtotal'] ?? ''}}"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Service Charge</label>
                                                <input type="text" name="menu_service_charge" class="form-control" value="{{$data->data['menu_service_charge'] ?? ''}}"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Tax</label>
                                                <input type="text" name="menu_tax" class="form-control" value="{{$data->data['give_us_call'] ?? ''}}"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Total Cost</label>
                                                <input type="text" name="menu_total_cost" class="form-control" value="{{$data->data['menu_total_cost'] ?? ''}}"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Confirm your order.</label>
                                                <input type="text" name="menu_confirm_order" class="form-control" value="{{$data->data['menu_confirm_order'] ?? ''}}"
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
                                                <input type="text" name="menu" class="form-control" value="{{$data->data['menu'] ?? ''}}"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Cart</label>
                                                <input type="text" name="cart" class="form-control" value="{{$data->data['cart'] ?? ''}}"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">My Order</label>
                                                <input type="text" name="my_order" class="form-control" value="{{$data->data['my_order'] ?? ''}}"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Customizable</label>
                                                <input type="text" name="customizable" class="form-control" value="{{$data->data['customizable'] ?? ''}}"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Available</label>
                                                <input type="text" name="available" class="form-control" value="{{$data->data['available'] ?? ''}}"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Not Available</label>
                                                <input type="text" name="not_available" class="form-control" value="{{$data->data['not_available'] ?? ''}}"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Recommended</label>
                                                <input type="text" name="recommended" class="form-control" value="{{$data->data['recommended'] ?? ''}}"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Close</label>
                                                <input type="text" name="menu_close" class="form-control" value="{{$data->data['menu_close'] ?? ''}}"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Save Changes</label>
                                                <input type="text" name="menu_save_changes" class="form-control" value="{{$data->data['menu_save_changes'] ?? ''}}"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Add to Cart</label>
                                                <input type="text" name="menu_add_to_cart" class="form-control" value="{{$data->data['menu_add_to_cart'] ?? ''}}"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Item Add To Cart Message</label>
                                                <input type="text" name="item_add_to_cart" class="form-control" value="{{$data->data['item_add_to_cart'] ?? ''}}"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">REC</label>
                                                <input type="text" name="menu_rec" class="form-control" value="{{$data->data['menu_rec'] ?? ''}}"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Order Placed Successfully.</label>
                                                <input type="text" name="menu_order_successmsg" class="form-control" value="{{$data->data['menu_order_successmsg'] ?? ''}}"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Check Order Status</label>
                                                <input type="text" name="menu_check_orderstatus" class="form-control" value="{{$data->data['menu_check_orderstatus'] ?? ''}}"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Your Cart is empty.</label>
                                                <input type="text" name="menu_cart_empty" class="form-control" value="{{$data->data['menu_cart_empty'] ?? ''}}"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Back to Menu</label>
                                                <input type="text" name="back_to_menu" class="form-control" value="{{$data->data['back_to_menu'] ?? ''}}"
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
                                                <input type="text" name="menu_mrp" class="form-control" value="{{$data->data['menu_mrp'] ?? ''}}"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Cooking Time</label>
                                                <input type="text" name="cooking_time" class="form-control" value="{{$data->data['cooking_time'] ?? ''}}"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Minute</label>
                                                <input type="text" name="cooking_time_unit" class="form-control" value="{{$data->data['cooking_time_unit'] ?? ''}}"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Product Details</label>
                                                <input type="text" name="menu_product_details" class="form-control" value="{{$data->data['menu_product_details'] ?? ''}}"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Maybe You Like this.</label>
                                                <input type="text" name="menu_maybe_you_likethis" class="form-control" value="{{$data->data['menu_maybe_you_likethis'] ?? ''}}"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Category Item Text</label>
                                                <input type="text" name="menu_category_items" class="form-control" value="{{$data->data['menu_category_items'] ?? ''}}"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Customization</label>
                                                <input type="text" name="menu_customizations_text" class="form-control" value="{{$data->data['menu_customizations_text'] ?? ''}}"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Search Order</label>
                                                <input type="text" name="menu_search_order" class="form-control" value="{{$data->data['menu_search_order'] ?? ''}}"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Current Order</label>
                                                <input type="text" name="menu_current_order" class="form-control" value="{{$data->data['menu_current_order'] ?? ''}}"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Completed Order</label>
                                                <input type="text" name="menu_completed_order" class="form-control" value="{{$data->data['menu_completed_order'] ?? ''}}"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Order ID</label>
                                                <input type="text" name="menu_order_id" class="form-control" value="{{$data->data['menu_order_id'] ?? ''}}"
                                                       required>
                                            </div>


                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Store</label>
                                                <input type="text" name="menu_store" class="form-control" value="{{$data->data['menu_store'] ?? ''}}"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Bill Amount</label>
                                                <input type="text" name="menu_bill_amount" class="form-control" value="{{$data->data['menu_bill_amount'] ?? ''}}"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Call the Waiter</label>
                                                <input type="text" name="call_the_waiter" class="form-control" value="{{$data->data['call_the_waiter'] ?? ''}}"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Call Now</label>
                                                <input type="text" name="call_the_waite_now" class="form-control" value="{{$data->data['call_the_waite_now'] ?? ''}}"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Calling Waiter Message</label>
                                                <input type="text" name="calling_waiter_msg" class="form-control" value="{{$data->data['calling_waiter_msg'] ?? ''}}"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Oder Status Text: Pending</label>
                                                <input type="text" name="order_status_pending" class="form-control" value="{{$data->data['order_status_pending'] ?? ''}}"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Oder Status Text: Accepted</label>
                                                <input type="text" name="order_status_accepted" class="form-control" value="{{$data->data['order_status_accepted'] ?? ''}}"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Oder Status Text: Ready to Serve</label>
                                                <input type="text" name="order_status_ready" class="form-control" value="{{$data->data['order_status_ready'] ?? ''}}"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Oder Status Text: Completed</label>
                                                <input type="text" name="order_status_completed" class="form-control" value="{{$data->data['order_status_completed'] ?? ''}}"
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
                                        Update
                                    </button>
                                </div>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>





@endsection








