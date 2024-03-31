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

    submitCallWaiter = quick_help => {
        const notification = this.notificationSystem.current;
        const { total, button_disabled, is_loading, comments } = this.state;

        const { tables, translation, name, phone, tableId } = this.props;

        if (tableId) {
            let data = tables.filter(data => data.table_number == tableId);

            if (data.length < 1) {
                // alert("INVALID TABLE ID. PLEASE SCAN THE QR CODE AGAIN");
                notification.addNotification({
                    message: "INVALID TABLE. PLEASE SCAN THE QR CODE AGAIN",
                    level: "error"
                });
                return;
            } else {
                var table_no = data[0]?.id;
            }
        }

        let body = {
            customer_name: name,
            customer_phone: phone,
            table_name: table_no,
            comment: comments,
            store_id: this.props.store_id,
            quick_help: quick_help
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
                                    {"Waiter Call"}
                                </h5>
                            </div>
                            <div class="modal-body">
                                <div class="form">
                                    <div className="p-3">
                                        {tableId ? (
                                            <div className="form-group">
                                                {/* <p>{`Would you like to call the waiter to table: ${tableId}?`}</p>
                                                <label htmlFor="exampleInputNEWPassword1">
                                                    {translation?.menu_comment ||
                                                        "Comment"}{" "}
                                                </label> */}
                                                <div
                                                    className="reqeust"
                                                    onClick={() =>
                                                        this.submitCallWaiter(1)
                                                    }
                                                >
                                                    <h6 className="request-title">
                                                        Help with the Menu
                                                    </h6>
                                                </div>

                                                <div
                                                    className="reqeust"
                                                    onClick={() =>
                                                        this.submitCallWaiter(2)
                                                    }
                                                >
                                                    <h6 className="request-title">
                                                        Problem with My Order
                                                    </h6>
                                                </div>

                                                <div
                                                    className="reqeust"
                                                    onClick={() =>
                                                        this.submitCallWaiter(3)
                                                    }
                                                >
                                                    <h6 className="request-title">
                                                        Problem with the system
                                                    </h6>
                                                </div>

                                                <input
                                                    type="text"
                                                    placeholder={
                                                        "Type your request..."
                                                    }
                                                    className="form-control type-request"
                                                    name="comments"
                                                    value={this.state.comments}
                                                    onChange={this.onChange}
                                                />
                                            </div>
                                        ) : (
                                            <div className="form-group">
                                                <div className="reqeust">
                                                    <h6 className="request-title">
                                                        Please scan a qr code
                                                        first.
                                                    </h6>
                                                </div>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer p-0 border-0">
                                <div class="col-6 m-0 p-0">
                                    <img
                                        src={`/images/icons/store/waiter_red.png`}
                                        // onClick={() => this.submitCallWaiter()}
                                        style={{ maxHeight: 40 }}
                                        class="m-auto"
                                    />
                                    {/* <button
                                        type="button"
                                        onClick={() => this.submitCallWaiter()}
                                        class="btn red-bg text-white btn-lg btn-block"
                                        disabled={!tableId}
                                    >
                                        {" "}
                                        {translation?.call_the_waite_now ||
                                            "Call Now"}
                                    </button> */}
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
    name: state.auth.userName,
    phone: state.auth.userPhone
});

export default connect(mapSateToProps, { callTheWaiter })(CallTheWaiter);
