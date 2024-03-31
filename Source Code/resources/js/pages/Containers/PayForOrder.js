import React, { useEffect, useRef, useState } from "react";
import ReactDOM from "react-dom";
import domain from "../../config/api/domain";
import { connect } from "react-redux";
import ROUTE from "../../config/route";
import { selectOrderCheckout } from "../../actions/checkoutAction";
import NotificationSystem from "react-notification-system";
import api from "../../config/api/";
import { Route } from "react-router";

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

class PayForOrder extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            isWholeOrder: false,
            isPercantage: false,
            percantageAmount: "100",
            tips: null,
            addTips: false
        };
        this.onChange = this.onChange.bind(this);
    }
    notificationSystem = React.createRef();

    onChange(e) {
        this.setState({ [e.target.name]: e.target.value });
    }

    payNow = () => {
        const notification = this.notificationSystem.current;
        let { isWholeOrder, isPercantage, tips, addTips } = this.state;
        const selectedOrder = this.props.selectedOrder[0];

        if (!isWholeOrder && !isPercantage) {
            notification.addNotification({
                message: "Please select a option",
                level: "error"
            });
        }

        if (isWholeOrder && isPercantage) {
            notification.addNotification({
                message: "Please select only one option",
                level: "error"
            });
        }

        if (isWholeOrder && !isPercantage) {
            const paid_amount = Number(selectedOrder.paid_amount);
            const total = Number(selectedOrder.total);

            if (paid_amount >= total) {
                notification.addNotification({
                    message: "Order already paid",
                    level: "error"
                });
                return;
            }

            if (paid_amount < total) {
                selectedOrder.total =
                    selectedOrder.total - selectedOrder.paid_amount;
            }

            if (addTips) {
                selectedOrder.tips = tips;
            }

            let data = {
                selectedOrder: selectedOrder
            };

            this.props.selectOrderCheckout(data);

            window.location.href = ROUTE.STORE.Checkout.PAGES.Limonetik.PATH;
        }

        if (isPercantage && !isWholeOrder) {
            const paid_amount = Number(selectedOrder.paid_amount);
            const total = Number(selectedOrder.total);

            const percantageAmount = this.state.percantageAmount
                ? Number(this.state.percantageAmount)
                : "100";

            const percantageTotal =
                (selectedOrder.total / 100) * percantageAmount;

            if (paid_amount >= total) {
                notification.addNotification({
                    message: "Order already paid",
                    level: "error"
                });
                return;
            }

            if (paid_amount < total) {
                if (percantageTotal > total - paid_amount) {
                    notification.addNotification({
                        message: "Please select a smaller ammount",
                        level: "error"
                    });
                    return;
                }
                if (percantageTotal <= total - paid_amount) {
                    selectedOrder.total =
                        (selectedOrder.total / 100) * percantageAmount;
                }
            }

            if (addTips) {
                selectedOrder.tips = tips;
            }

            let data = {
                selectedOrder: selectedOrder
            };

            this.props.selectOrderCheckout(data);

            window.location.href = ROUTE.STORE.Checkout.PAGES.Limonetik.PATH;
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
                    id={`pay-for-order`}
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
                                                id="isWholeOrder"
                                                name="isWholeOrder"
                                                checked={
                                                    this.state.isWholeOrder
                                                }
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
                                                htmlFor="isWholeOrder"
                                                class="form-check-label"
                                            >
                                                {"Pay for the whole order"}
                                            </label>

                                            <br />
                                            <br />
                                            <br />

                                            <input
                                                type="checkbox"
                                                className="form-check-input"
                                                id="isPercantage"
                                                name="isPercantage"
                                                checked={
                                                    this.state.isPercantage
                                                }
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
                                            <label htmlFor="isPercantage">
                                                {"Pay a percantage"}
                                            </label>

                                            {this.state.isPercantage &&
                                            !this.state.isWholeOrder ? (
                                                <>
                                                    <input
                                                        type="text"
                                                        placeholder={
                                                            "Enter your percantage amount %"
                                                        }
                                                        id="percantageAmount"
                                                        className="form-control"
                                                        name="percantageAmount"
                                                        value={
                                                            this.state
                                                                .percantageAmount
                                                        }
                                                        onChange={this.onChange}
                                                    />
                                                    {this.state
                                                        .percantageAmount && (
                                                        <p className="mt-3">
                                                            {`If you pay ${
                                                                this.state
                                                                    .percantageAmount
                                                            }%, the cost will be: ${(this
                                                                .props
                                                                .selectedOrder[0]
                                                                .total /
                                                                100) *
                                                                this.state
                                                                    .percantageAmount}`}
                                                        </p>
                                                    )}
                                                </>
                                            ) : null}

                                            <br />
                                            <br />
                                            <p
                                                className={`text-white py-1 px-2 mt-5 mb-4 text-center rounded small bg-${
                                                    this.state.addTips
                                                        ? "warning"
                                                        : "info"
                                                }`}
                                                onClick={e => {
                                                    this.setState(
                                                        prevState => ({
                                                            addTips: !prevState.addTips
                                                        })
                                                    );
                                                }}
                                            >
                                                {`${
                                                    this.state.addTips
                                                        ? "No I don't want add tips."
                                                        : "I want to add tips."
                                                }`}
                                            </p>

                                            {this.state.addTips && (
                                                <>
                                                    <p>
                                                        Enter your tips amount:
                                                    </p>
                                                    <input
                                                        type="number"
                                                        className="form-control"
                                                        placeholder="0.00"
                                                        value={this.state.tips}
                                                        name="tips"
                                                        onChange={this.onChange}
                                                    />
                                                </>
                                            )}
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
                        id={`#pay-for-order`}
                        class="btn btn-outline-success btn-sm ml-auto"
                        data-toggle="modal"
                        data-target={`#pay-for-order`}
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
    auth: state.auth
});

export default connect(mapSateToProps, {
    selectOrderCheckout
})(PayForOrder);

// export default PayForOrder;

// export default connect(mapSateToProps, { callTheWaiter })(CallTheWaiter);
