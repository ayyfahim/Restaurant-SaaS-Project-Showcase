import React, { useEffect, useRef, useState } from "react";
import ReactDOM from "react-dom";
import domain from "../../config/api/domain";
import { connect } from "react-redux";
import ROUTE from "../../config/route";
import { selectOrderCheckout } from "../../actions/checkoutAction";
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

class PayAtRestaurant extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            isCard: false,
            isCash: false
        };
        this.onChange = this.onChange.bind(this);
    }
    notificationSystem = React.createRef();

    onChange(e) {
        this.setState({ [e.target.name]: e.target.value });
    }

    payNow = () => {
        const notification = this.notificationSystem.current;
        let { isCard, isCash } = this.state;

        if (!isCard && !isCash) {
            notification.addNotification({
                message: "Please select a option",
                level: "error"
            });
            return;
        }

        if (isCard && isCash) {
            notification.addNotification({
                message: "Please select only one option",
                level: "error"
            });
            return;
        }

        if (isCard || isCash) {
            var selectedOrder = this.props.selectedOrder[0];
            const paid_amount = Number(selectedOrder.paid_amount);
            const total = Number(selectedOrder.total);

            if (paid_amount >= total) {
                notification.addNotification({
                    message: "Order already paid",
                    level: "error"
                });
                return;
            } else {
                let body = {
                    order_id: selectedOrder.id,
                    type: 1,
                    method: isCard ? "isCard" : "isCash"
                };

                this.props.callTheWaiter(body);

                document.getElementById("#pay-at-restaurant").click();
                return;
            }

            // const notification = this.notificationSystem.current;

            // if (paid_amount < total) {
            //     selectedOrder.total =
            //         selectedOrder.total - selectedOrder.paid_amount;
            // }

            // let data = {
            //     selectedOrder: selectedOrder
            // };

            // this.props.selectOrderCheckout(data);

            // window.location.href = "/checkout/ayden";
        }
    };

    render() {
        // const { translation, tableId } = this.props;
        return (
            <div>
                <NotificationSystem
                    ref={this.notificationSystem}
                    style={style}
                />
                <div
                    class="modal fade"
                    id={`pay-at-restaurant`}
                    tabindex="-1"
                    role="dialog"
                    aria-labelledby="exampleModalLabel"
                    aria-hidden="true"
                >
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">
                                    {"Pay for the order"}
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
                                        <div className="form-group">
                                            <p>
                                                Please select your preferred
                                                method:
                                            </p>
                                            <input
                                                type="checkbox"
                                                className="form-check-input"
                                                id="isCard"
                                                name="isCard"
                                                checked={this.state.isCard}
                                                onChange={e => {
                                                    this.onChange({
                                                        target: {
                                                            name: e.target.name,
                                                            value:
                                                                e.target.checked
                                                        }
                                                    });
                                                }}
                                            />
                                            <label
                                                htmlFor="isCard"
                                                class="form-check-label"
                                            >
                                                {"Pay with card"}
                                            </label>

                                            <br />
                                            <br />
                                            <br />

                                            <input
                                                type="checkbox"
                                                className="form-check-input"
                                                id="isCash"
                                                name="isCash"
                                                checked={this.state.isCash}
                                                onChange={e => {
                                                    this.onChange({
                                                        target: {
                                                            name: e.target.name,
                                                            value:
                                                                e.target.checked
                                                        }
                                                    });
                                                }}
                                            />
                                            <label htmlFor="isCash">
                                                {"Pay with Cash"}
                                            </label>
                                        </div>
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
                                        {"Close"}
                                    </button>
                                </div>
                                <div class="col-6 m-0 p-0">
                                    <a
                                        onClick={this.payNow}
                                        className={
                                            "btn red-bg text-white btn-lg btn-block"
                                        }
                                    >
                                        {"Pay Now"}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button
                        style={{ visibility: "hidden" }}
                        type="button"
                        id={`#pay-at-restaurant`}
                        class="btn btn-outline-success btn-sm ml-auto"
                        data-toggle="modal"
                        data-target={`#pay-at-restaurant`}
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
    // name: state.auth.userName,
    // phone: state.auth.userPhone
});

export default connect(mapSateToProps, {
    selectOrderCheckout,
    callTheWaiter
})(PayAtRestaurant);

// export default PayForOrder;

// export default connect(mapSateToProps, { callTheWaiter })(CallTheWaiter);
