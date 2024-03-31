import React, { useEffect, useRef, useState } from "react";
import ReactDOM from "react-dom";
import domain from "../../config/api/domain";
import { connect } from "react-redux";
import useDynamicRefs from "use-dynamic-refs";
import NotificationSystem from "react-notification-system";
import { callTheWaiter } from "../../actions/storeAction";
var style = {
    NotificationItem: {
        // Override the notification item
        DefaultStyle: {
            // Applied to every notification, regardless of the notification level
            margin: "10px 5px 2px 1px",
            background: "#28a745"
        },
        success: {
            color: "white"
        }
    }
};
class CallTheWaiter extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            name: "",
            phone: null,
            comments: "",
            table_no: null,
            table_code: null,
            total: 0,
            button_disabled: false,
            is_loading: false,
            is_completed: false
        };
        this.onChange = this.onChange.bind(this);
    }
    notificationSystem = React.createRef();
    onChange(e) {
        this.setState({ [e.target.name]: e.target.value });
    }
    renderTableCode = table_name => {
        const { tables, translation } = this.props;
        if (tables) {
            let data = tables.filter(data => data.table_name == table_name);

            if (data[0]?.table_code) {
                return (
                    <div className="form-group">
                        <label htmlFor="exampleInputNEWPassword1">
                            {translation?.enter_your_table_code ||
                                "Enter Your Code"}
                        </label>
                        <input
                            type="text"
                            className="form-control"
                            name="table_code"
                            placeholder={
                                translation?.enter_your_table_code ||
                                "Enter Your Code"
                            }
                            value={this.state.table_code}
                            onChange={this.onChange}
                        />
                    </div>
                );
            }
        }
        return null;
    };

    submitCallWaiter = () => {
        const notification = this.notificationSystem.current;
        const {
            // name,
            // phone,
            // table_no,
            // table_code,
            total,
            button_disabled,
            is_loading,
            comments
        } = this.state;

        const { tables, translation, name, phone, tableId } = this.props;

        // if (!(name && phone) && is_loading == false) return;

        // if (table_no) {
        //     let data = tables.filter(data => data.table_name == table_no);
        //     if (data[0]?.table_code) {
        //         if (!(data[0]?.table_code == table_code)) {
        //             alert(
        //                 translation?.table_code_error_message ||
        //                     "INVALID TABLE CODE/PLEASE ENTER A VALID CODE"
        //             );
        //             return;
        //         }
        //     }
        // }

        if (tableId) {
            let data = tables.filter(data => data.id == tableId);
            var table_no = data[0]?.table_name;
            if (data.length < 1) {
                // alert("INVALID TABLE ID. PLEASE SCAN THE QR CODE AGAIN");
                notification.addNotification({
                    message: "INVALID TABLE ID. PLEASE SCAN THE QR CODE AGAIN",
                    level: "error"
                });
                return;
            }
        }

        let body = {
            customer_name: name,
            customer_phone: phone,
            table_name: table_no,
            comment: comments,
            store_id: this.props.store_id
        };

        notification.addNotification({
            message: translation?.calling_waiter_msg || "calling waiter ....",
            level: "success"
        });

        console.log(body);
        this.props.callTheWaiter(body);
        document.getElementById("#call-the-waiter").click();
    };
    render() {
        const { translation, tableId } = this.props;
        return (
            <div>
                <NotificationSystem
                    ref={this.notificationSystem}
                    style={style}
                />
                <div
                    class="modal fade"
                    id={`call-the-waiter`}
                    tabindex="-1"
                    role="dialog"
                    aria-labelledby="exampleModalLabel"
                    aria-hidden="true"
                >
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">
                                    {translation?.call_the_waiter ||
                                        "Call The Waiter"}
                                </h5>
                                <button
                                    type="button"
                                    class="close"
                                    data-dismiss="modal"
                                    aria-label="Close"
                                >
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form">
                                    <div className="p-3">
                                        {tableId ? (
                                            <div className="form-group">
                                                <p>{`Would you like to call the waiter to table: ${tableId}?`}</p>
                                                <label htmlFor="exampleInputNEWPassword1">
                                                    {translation?.menu_comment ||
                                                        "Comment"}{" "}
                                                </label>
                                                <input
                                                    type="text"
                                                    placeholder={
                                                        translation?.menu_comment ||
                                                        "Comment"
                                                    }
                                                    className="form-control"
                                                    npm
                                                    run
                                                    production
                                                    name="comments"
                                                    value={this.state.comments}
                                                    onChange={this.onChange}
                                                />
                                            </div>
                                        ) : (
                                            <div className="form-group">
                                                <label htmlFor="emnei">
                                                    Please scan a qr code first.
                                                </label>
                                            </div>
                                        )}
                                        {/* 
                                        <div className="form-group">
                                            <label htmlFor="exampleInputOLDPassword1">
                                                {" "}
                                                {translation?.menu_name ||
                                                    "Name"}{" "}
                                                *
                                            </label>
                                            <input
                                                type="text"
                                                placeholder={
                                                    translation?.menu_name ||
                                                    "Name"
                                                }
                                                name="name"
                                                class="form-control"
                                                value={this.state.name}
                                                onChange={this.onChange}
                                            />
                                        </div>

                                        <div className="form-group">
                                            <label htmlFor="exampleInputNEWPassword1">
                                                {translation?.menu_phone_number ||
                                                    "Phone Number"}{" "}
                                                *
                                            </label>
                                            <input
                                                type="number"
                                                placeholder={
                                                    translation?.menu_phone_number ||
                                                    "Phone Number"
                                                }
                                                className="form-control"
                                                name="phone"
                                                value={this.state.phone}
                                                onChange={this.onChange}
                                            />
                                        </div> 

                                        <div className="form-group">
                                            <label htmlFor="exampleInputNEWPassword1">
                                                {translation?.menu_comment ||
                                                    "Comment"}{" "}
                                            </label>
                                            <input
                                                type="text"
                                                placeholder={
                                                    translation?.menu_comment ||
                                                    "Comment"
                                                }
                                                className="form-control"
                                                npm
                                                run
                                                production
                                                name="comments"
                                                value={this.state.comments}
                                                onChange={this.onChange}
                                            />
                                        </div>

                                        
                                        {this.props?.tables &&
                                        this.props?.tables ? (
                                            <div className="form-group">
                                                <label htmlFor="exampleInputNEWPassword1">
                                                    {translation?.select_your_table ||
                                                        "Select Your Table"}
                                                </label>
                                                <select
                                                    type="text"
                                                    className="form-control"
                                                    name="table_no"
                                                    placeholder={
                                                        translation?.select_your_table ||
                                                        "Select Your Table"
                                                    }
                                                    value={this.state.table_no}
                                                    onChange={this.onChange}
                                                >
                                                    <option value="">
                                                        {" "}
                                                        {translation?.select_your_table ||
                                                            "Select Your Table"}
                                                    </option>
                                                    {this.props?.tables &&
                                                        this.props?.tables.map(
                                                            data => (
                                                                <option>
                                                                    {
                                                                        data.table_name
                                                                    }
                                                                </option>
                                                            )
                                                        )}
                                                </select>
                                            </div>
                                        ) : null}
                                        {this.renderTableCode(
                                            this.state.table_no
                                        )} 
                                        */}
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer p-0 border-0 fixed-bottom">
                                <div class="col-6 m-0 p-0">
                                    <button
                                        type="button"
                                        id="call-waiter-close"
                                        class="btn btn-dark btn-lg btn-block"
                                        data-dismiss="modal"
                                    >
                                        {translation?.menu_close || "Close"}{" "}
                                    </button>
                                </div>
                                <div class="col-6 m-0 p-0">
                                    <button
                                        type="button"
                                        onClick={() => this.submitCallWaiter()}
                                        class="btn red-bg text-white btn-lg btn-block"
                                        disabled={!tableId}
                                    >
                                        {" "}
                                        {translation?.call_the_waite_now ||
                                            "Call Now"}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button
                        style={{ visibility: "hidden" }}
                        type="button"
                        id={`#call-the-waiter`}
                        class="btn btn-outline-success btn-sm ml-auto"
                        data-toggle="modal"
                        data-target={`#call-the-waiter`}
                    >
                        Add
                    </button>
                </div>
            </div>
        );
    }
}
const mapSateToProps = state => ({
    // store_name: state.store.store_name,
    // description: state.store.description,
    // sliders: state.store.sliders,
    // recommendedItems: state.store.recommendedItems,
    // account_info: state.store.account_info,
    // categories: state.store.categories,
    // products: state.store.products,
    // cart: state.cart.Items,
    // orders: state.orders.Orders,
    // tables: state.store.tables,
    // addons: state.store.addons,
    name: state.auth.userName,
    phone: state.auth.userPhone
});

export default connect(mapSateToProps, { callTheWaiter })(CallTheWaiter);
