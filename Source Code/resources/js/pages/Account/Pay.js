import React from "react";
import ReactDOM from "react-dom";
import Moment from "moment";
import Header from "../../components/Header";
import SideBar from "../../components/SideBar";
import { NavLink, Route } from "react-router-dom";
import ROUTE from "../../config/route";
import { connect } from "react-redux";
import { fetchOrders, fetchTableOrders } from "../../actions/orderAction";
import {
    selectOrdersForCheckout,
    addTipsForCheckout,
    addCardForCheckout
} from "../../actions/checkoutAction";
import { refreshUser } from "../../actions/authAction";
import FooterBar from "../Containers/FooterBar";
import NotificationSystem from "react-notification-system";
import SimpleHeader from "../Containers/SimpleHeader";
import PriceRender from "../Containers/PriceRender";
import IsLoading from "../Containers/IsLoading";
import getSymbolFromCurrency from "currency-symbol-map";
import miscVariables from "../../helpers/misc";
import api from "../../config/api";

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

class Pay extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            // selectedOrder: null
            sub_total: 0.0,
            total: 0.0,
            tips: 0.0,
            customTip: false,
            tipPercantAmount: 0,
            selectedCardId: null,

            show_add_card: false,
            show_warning: false,
            cardName: null
        };
        this.onChange = this.onChange.bind(this);
    }
    notificationSystem = React.createRef();

    componentWillMount() {
        var data = {
            table_no: this.props.selectedTable?.id
        };

        this.props.fetchOrders(data);
        this.props.refreshUser();
    }

    componentDidMount() {
        let {
            orders,
            selectOrdersForCheckout,
            addTipsForCheckout
        } = this.props;

        let newData = {};

        newData = orders?.filter(
            order =>
                order.is_paid !== 1 &&
                Number(order.paid_amount) <= Number(order.total)
        );

        selectOrdersForCheckout({ selectedOrders: newData });
        addTipsForCheckout({ tips: this.calculateTotalTips() });

        if (this.props.auth.cards.length > 0) {
            this.setState({
                selectedCardId: this.props.auth.cards[0].id
            });
        }
    }

    toggleWarningBox = () => {
        this.setState(prevState => ({
            show_warning: !prevState.show_warning
        }));
    };

    calculateSubTotal() {
        let { checkoutOrders } = this.props;
        let sum = 0;

        checkoutOrders?.map(item => {
            if (item.status !== 3) {
                sum = sum + Number(item.total);
            }
        });

        return Number(sum);
    }

    calculateTotalTips() {
        let { tipPercantAmount, customTip, tips } = this.state;
        let sum = 0;
        let subTotal = this.calculateSubTotal();

        if (!customTip) {
            sum = (subTotal * tipPercantAmount) / 100;
        } else {
            sum = tips;
        }

        return Number(sum);
    }

    calculateTotal() {
        let sum = 0;

        sum = this.calculateSubTotal() + this.calculateTotalTips();

        return Number(sum);
    }

    toggleCheckoutOrder(orderId) {
        let { checkoutOrders, orders, selectOrdersForCheckout } = this.props;
        let { is_loading } = this.state;

        if (is_loading) {
            return;
        }

        this.setState({ is_loading: true });

        let find = checkoutOrders?.find(
            order =>
                order.is_paid !== 1 &&
                Number(order.paid_amount) <= Number(order.total) &&
                order.id == orderId
        );

        let newData = {};

        if (find) {
            // console.log(`found`);
            // Remove that item from checkout orders
            newData = checkoutOrders?.filter(order => order.id != orderId);
            selectOrdersForCheckout({
                selectedOrders: newData
            });
            // console.log(`newData`, newData);
        } else {
            // console.log(`not found`);
            // add that item to checkout orders
            newData = orders?.filter(order => order.id == orderId);

            // console.log(`newData`, newData[0]);

            selectOrdersForCheckout({
                selectedOrders: [...checkoutOrders, ...newData]
            });
        }

        this.setState({ is_loading: false });

        return find;
    }

    onChange(e) {
        this.setState({ [e.target.name]: e.target.value });
    }

    isOrderPaid(data) {
        return Number(data.paid_amount) >= Number(data.total);
    }

    secondaryHeader(text) {
        return (
            <p
                className="mt-3 text-secondary"
                style={{
                    fontWeight: "bold",
                    textTransform: "uppercase",
                    fontSize: "15px"
                }}
            >
                {text ?? "payment"}
            </p>
        );
    }

    setTipPercantAmount(amount) {
        if (this.state.is_loading) return;

        this.setState({ is_loading: true });

        switch (amount) {
            case 5:
                this.setState({ tipPercantAmount: 5, customTip: false });
                break;

            case 10:
                this.setState({ tipPercantAmount: 10, customTip: false });
                break;
            case 20:
                this.setState({ tipPercantAmount: 20, customTip: false });
                break;

            default:
                this.setState(prevState => ({
                    customTip: !prevState.customTip,
                    tipPercantAmount: 0
                }));
                break;
        }

        this.setState({ is_loading: false });
    }

    submitForm() {
        if (this.state.is_loading) return;

        const notification = this.notificationSystem.current;
        if (this.props.auth.cards.length < 1) {
            notification.addNotification({
                message: "Please add a card first.",
                level: "error"
            });

            // Show dialog box
            this.toggleWarningBox();
            // Send the card details.

            // setTimeout(() => {
            //     window.location.href = ROUTE.ACCOUNT.CARD.PAGES.VIEW.PATH;
            // }, miscVariables.setTimeoutTimer);

            return;
        }

        this.setState({ is_loading: true });

        let { addTipsForCheckout, addCardForCheckout } = this.props;
        addTipsForCheckout({ tips: this.calculateTotalTips() });
        addCardForCheckout({ card: this.state.selectedCardId });

        // return;

        this.setState({ is_loading: false }, () => {
            window.location.href = ROUTE.STORE.Checkout.PAGES.Limonetik.PATH;
        });
        return;
    }

    addCardForm = () => {
        // if (this.state.is_loading) return;

        this.callServerPost();
    };

    checkStatus = async response => {
        if (response.status >= 200 && response.status < 300)
            return await response.json();

        throw await response.json();
    };

    // Calls your server endpoints
    callServerPost = async (retries = 5) => {
        let notification = this.notificationSystem.current;

        let url = api.store.Checkout.limonetikCreateOrderForCard.path;

        const res = fetch(url, {
            method: "POST",
            headers: {
                Authorization: "Bearer " + this.props.auth.token
            }
        })
            .then(response => this.checkStatus(response))
            .then(res => {
                const resData = res.payload.data;

                this.setState(
                    {
                        is_loading: false,
                        show_add_card: true,
                        paymentOrderId: resData.PaymentOrderId,
                        paymentPageUrl: resData.PaymentPageUrl
                    },
                    () => {
                        this.updateIframe();
                    }
                );
            })
            .catch(err => {
                if (err?.payload?.data?.ReturnCode == 9500) {
                    if (retries > 0) {
                        notification.addNotification({
                            message: `Please wait ${(miscVariables.retryFetch *
                                retries) /
                                1000} seconds.`,
                            level: "error"
                        });

                        setTimeout(() => {
                            this.callServerPost(retries - 1);
                        }, miscVariables.retryFetch * retries);
                        return;
                    }

                    notification.addNotification({
                        message: `Please try again later.`,
                        level: "error"
                    });
                    return;
                }
            });

        return res;
    };

    updateIframe() {
        var frame = document.getElementById("LmkFrame");
        frame.setAttribute("src", this.state.paymentPageUrl);
        var currentSrcIframe =
            frame.ownerDocument.defaultView.window[0].location.href;
        frame.addEventListener("load", () => {
            {
                if (currentSrcIframe.includes("payment.limonetik") == false) {
                    this.paymentReceived();
                }
            }
        });
    }

    paymentReceived = () => {
        this.setState({ is_loading: true });
        let data = {
            PaymentOrderId: this.state.paymentOrderId
        };
        let url = api.store.Checkout.limonetikGetOrder.path;

        const res = fetch(url, {
            method: "POST",
            body: JSON.stringify(data),
            headers: {
                Authorization: "Bearer " + this.props?.auth?.token
            }
        })
            .then(response => response.json())
            .then(res => {
                this.setState({ is_loading: false });

                const resData = res.payload.data;

                if (resData.PaymentOrder.Status == "Authorized") {
                    this.chargePayment(resData.PaymentOrder);
                }
            });
    };

    chargePayment = PaymentOrder => {
        let url = api.store.Checkout.limonetikChargeOrderForCard.path;

        let chargePaymentData = {
            PaymentOrderId: PaymentOrder.Id,
            ChargeAmount: PaymentOrder.Amount,
            Currency: "EUR",

            customer_id: this.props.auth.userId,
            amount: PaymentOrder.Amount,
            currency: PaymentOrder.Currency,
            cardName: this.state.cardName
        };

        const res = fetch(url, {
            method: "POST",
            body: JSON.stringify(chargePaymentData),
            headers: {
                Authorization: "Bearer " + this.props.auth.token
            }
        })
            .then(response => response.json())
            .then(res => {
                if (res.success == false) {
                    this.errorOccured();
                }

                const resData = res.payload.data;

                this.setState(
                    {
                        modalText: "Payment Received Successfully",
                        is_loading: false,
                        show_add_card: false,
                        show_warning: false,
                        cardName: null
                    },
                    () => {
                        let { modalText } = this.state;
                        var notification = this.notificationSystem.current;
                        notification.addNotification({
                            message: modalText,
                            level: "success"
                        });

                        window.scrollTo({ top: 0, behavior: "smooth" });

                        this.props.refreshUser();
                    }
                );
            })
            .catch(error => {
                // this.errorOccured();
            });
    };

    render() {
        let { orders, account_info, translation, checkoutOrders } = this.props;
        let {
            tips,
            tipPercantAmount,
            customTip,
            selectedCardId,
            show_warning,
            show_add_card
        } = this.state;
        let currency = account_info ? account_info.currency_symbol : "USD";

        if (show_add_card) {
            return (
                <div
                    style={{
                        position: "relative",
                        overflow: "hidden",
                        minHeight: "1000px"
                    }}
                >
                    <iframe
                        id="LmkFrame"
                        scrolling="no"
                        style={{
                            position: "absolute",
                            width: "100%",
                            height: "100%",
                            top: "0",
                            left: "0"
                        }}
                        height="500px"
                        width="700px"
                    ></iframe>
                </div>
            );
        }

        return (
            <div>
                <NotificationSystem
                    ref={this.notificationSystem}
                    style={style}
                />
                <IsLoading is_loading={this.state.is_loading} />
                <div className="fixed-bottom-padding">
                    <SimpleHeader
                        text={"Pay Now"}
                        goBack={this.props.history.goBack}
                    />

                    <div
                        id="edit-box"
                        className={`shadow-lg ${show_warning ? "shown" : ""}`}
                    >
                        <div className="container">
                            <div className="row">
                                <div
                                    className="heading-module col-12"
                                    onClick={this.toggleWarningBox}
                                >
                                    <i class="icofont-close"></i>
                                </div>
                                <div className="change-box col-12">
                                    <div className="change-title">
                                        <p className="m-0 text-secondary text-center">
                                            We Take and fully refund 1 EURO to
                                            verify the card
                                        </p>
                                    </div>

                                    <div className="row mt-4">
                                        <div className="col-5">
                                            <div className="change-title">
                                                <h6 className="m-0">
                                                    Card Name
                                                </h6>
                                            </div>
                                        </div>
                                        <div className="col-7">
                                            <input
                                                class={`form-control`}
                                                type="text"
                                                placeholder="Card Name"
                                                name="cardName"
                                                onChange={this.onChange}
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div className="col-12 my-3">
                                    <button
                                        type="button"
                                        class="btn btn-primary btn-primary-appetizr submit-btn"
                                        onClick={() =>
                                            this.setState(
                                                {
                                                    is_loading: true,
                                                    show_warning: false
                                                },
                                                () => {
                                                    this.addCardForm();
                                                }
                                            )
                                        }
                                    >
                                        Next
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {checkoutOrders?.length > 0 && !show_add_card ? (
                        <main className="px-3 py-4" id="payment_orders">
                            {this.secondaryHeader("Orders:")}
                            {orders.map(
                                data =>
                                    data.is_paid !== 1 &&
                                    data.status !== 3 &&
                                    Number(data.paid_amount) <=
                                        Number(data.total) && (
                                        <div
                                            className={`p-3 mb-4 each_order ${
                                                checkoutOrders?.find(
                                                    order =>
                                                        order.is_paid !== 1 &&
                                                        Number(
                                                            order.paid_amount
                                                        ) <=
                                                            Number(
                                                                order.total
                                                            ) &&
                                                        order.id == data.id
                                                )
                                                    ? "active"
                                                    : ""
                                            }`}
                                            style={{
                                                border: "2px solid",
                                                borderStyle: "dashed",
                                                borderRadius: 20
                                            }}
                                            onClick={() =>
                                                this.toggleCheckoutOrder(
                                                    data.id
                                                )
                                            }
                                        >
                                            <div className="row">
                                                <div className="col-12">
                                                    <p>
                                                        {data.order_unique_id}
                                                    </p>
                                                </div>
                                            </div>
                                            {data?.order_details?.map(
                                                order_detail => (
                                                    <div className="row no-gutters">
                                                        <div className="col">
                                                            <h6>
                                                                {`${order_detail.name} (x${order_detail.quantity})`}
                                                            </h6>
                                                            {order_detail
                                                                ?.order_details_extra_addon
                                                                ?.length >
                                                                0 && (
                                                                <h6
                                                                    className="text-secondary"
                                                                    style={{
                                                                        fontSize:
                                                                            "0.9rem",
                                                                        paddingLeft: 5
                                                                    }}
                                                                >
                                                                    {order_detail.order_details_extra_addon.map(
                                                                        (
                                                                            extra,
                                                                            key
                                                                        ) => (
                                                                            <>
                                                                                Name:
                                                                                <strong>
                                                                                    {`${extra.addon_name} ( ${extra.addon_price})`}
                                                                                </strong>

                                                                                x
                                                                                <strong>
                                                                                    {" "}
                                                                                    {
                                                                                        extra.addon_count
                                                                                    }
                                                                                </strong>{" "}
                                                                                =
                                                                                <strong>
                                                                                    {" "}
                                                                                    {`$${extra.addon_count *
                                                                                        extra.addon_price}`}
                                                                                </strong>
                                                                                <br />
                                                                            </>
                                                                        )
                                                                    )}
                                                                </h6>
                                                            )}
                                                        </div>
                                                        <div className="col-3 text-right">
                                                            <PriceRender
                                                                currency={
                                                                    currency
                                                                }
                                                                price={
                                                                    order_detail.price
                                                                }
                                                                style={{
                                                                    fontWeight:
                                                                        "bold",
                                                                    fontSize: 14
                                                                }}
                                                            />
                                                        </div>
                                                    </div>
                                                )
                                            )}

                                            <div className="row">
                                                <div className="col-12">
                                                    <div
                                                        style={{
                                                            height: 3
                                                        }}
                                                        className="order_divider"
                                                    ></div>
                                                </div>
                                            </div>

                                            <div className="row py-2">
                                                <div className="col-2">
                                                    <div
                                                        style={{
                                                            width: 20,
                                                            height: 20,
                                                            borderRadius: "50%",
                                                            border:
                                                                "2px solid ",
                                                            position: "relative"
                                                        }}
                                                        class="order_circle"
                                                    >
                                                        <div
                                                            style={{
                                                                width: 10,
                                                                height: 10,
                                                                borderRadius:
                                                                    "50%",
                                                                position:
                                                                    "absolute",
                                                                top: "50%",
                                                                left: "50%",
                                                                transform:
                                                                    "translate(-50%, -50%)"
                                                            }}
                                                        ></div>
                                                    </div>
                                                </div>
                                                <div className="col-3 offset-7 text-right">
                                                    <PriceRender
                                                        currency={currency}
                                                        price={data.total}
                                                        style={{
                                                            fontWeight: "bold",
                                                            fontSize: 14
                                                        }}
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    )
                            )}

                            {this.secondaryHeader("Add Tips:")}

                            <div className="tip-box">
                                <ul>
                                    <li
                                        className={`${
                                            tipPercantAmount == 5
                                                ? "active"
                                                : ""
                                        }`}
                                        onClick={() =>
                                            this.setTipPercantAmount(5)
                                        }
                                    >
                                        5%
                                    </li>
                                    <li
                                        className={`${
                                            tipPercantAmount == 10
                                                ? "active"
                                                : ""
                                        }`}
                                        onClick={() =>
                                            this.setTipPercantAmount(10)
                                        }
                                    >
                                        10%
                                    </li>
                                    <li
                                        className={`${
                                            tipPercantAmount == 20
                                                ? "active"
                                                : ""
                                        }`}
                                        onClick={() =>
                                            this.setTipPercantAmount(20)
                                        }
                                    >
                                        20%
                                    </li>
                                    <li
                                        className={`${
                                            customTip ? "active" : ""
                                        }`}
                                        onClick={() =>
                                            this.setTipPercantAmount("other")
                                        }
                                    >
                                        Other
                                    </li>
                                </ul>

                                {customTip && (
                                    <div className="mt-3">
                                        <div className="form-group custom-text-box-1">
                                            <span>
                                                Tips Amount (
                                                {getSymbolFromCurrency(
                                                    currency
                                                )}
                                                )
                                            </span>
                                            <input
                                                type="number"
                                                className="form-control"
                                                name="tips"
                                                value={this.state.tips}
                                                onChange={this.onChange}
                                            />
                                        </div>
                                    </div>
                                )}
                            </div>

                            <div id="totalPay">
                                <div className="container shadow-sm my-5">
                                    <div className="row no-gutters">
                                        <div className="col-6">
                                            <h6>Sub Total</h6>
                                        </div>
                                        <div className="col-6 text-right">
                                            <h6>
                                                <PriceRender
                                                    currency={currency}
                                                    price={this.calculateSubTotal()}
                                                    style={{
                                                        fontWeight: "bold",
                                                        fontSize: 14
                                                    }}
                                                />
                                            </h6>
                                        </div>
                                        <div className="col-6">
                                            <h6 className="m-0">Tips</h6>
                                        </div>
                                        <div className="col-6 text-right">
                                            <h6 className="m-0">
                                                <PriceRender
                                                    currency={currency}
                                                    price={this.calculateTotalTips()}
                                                    style={{
                                                        fontWeight: "bold",
                                                        fontSize: 14
                                                    }}
                                                />
                                            </h6>
                                        </div>
                                        <div className="col-12 my-3">
                                            <div
                                                style={{
                                                    borderBottom:
                                                        "1px solid #e2e2e2"
                                                }}
                                            ></div>
                                        </div>
                                        <div className="col-6">
                                            <h5
                                                style={{
                                                    fontWeight: 600
                                                }}
                                                className="m-0"
                                            >
                                                Total
                                            </h5>
                                        </div>
                                        <div className="col-6 text-right">
                                            <h5 className="m-0">
                                                <PriceRender
                                                    currency={currency}
                                                    price={this.calculateTotal()}
                                                    style={{
                                                        fontWeight: "bold",
                                                        fontSize: 14
                                                    }}
                                                />
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {this.props.auth?.cards?.length > 0 && (
                                <>
                                    {this.secondaryHeader(
                                        "Pay via saved cards:"
                                    )}

                                    {this.props.auth.cards.map(card => (
                                        <div
                                            className=""
                                            onClick={() => {
                                                if (selectedCardId == card.id) {
                                                    this.setState({
                                                        selectedCardId: null
                                                    });
                                                    return;
                                                }

                                                this.setState({
                                                    selectedCardId: card.id
                                                });
                                            }}
                                        >
                                            <div
                                                style={{
                                                    backgroundColor: "#fff",
                                                    padding: 15,
                                                    display: "flex",
                                                    marginBottom: "20px",
                                                    borderRadius: "7px",
                                                    border: "1px solid"
                                                }}
                                                className={`align-items-center ${
                                                    selectedCardId == card.id
                                                        ? "border-danger"
                                                        : "border-secondary"
                                                }`}
                                            >
                                                <div className="mr-4">
                                                    <i
                                                        class="icofont-card"
                                                        style={{
                                                            fontSize: 40
                                                        }}
                                                    ></i>
                                                </div>
                                                <p
                                                    style={{
                                                        marginBottom: "0"
                                                    }}
                                                >
                                                    {card.card_name}
                                                </p>
                                                <div
                                                    style={{
                                                        marginLeft: "auto"
                                                    }}
                                                    className={`${
                                                        selectedCardId ==
                                                        card.id
                                                            ? "text-danger"
                                                            : ""
                                                    }`}
                                                >
                                                    <div
                                                        style={{
                                                            width: 20,
                                                            height: 20,
                                                            borderRadius: "50%",
                                                            border:
                                                                "2px solid ",
                                                            position: "relative"
                                                        }}
                                                        className={`${
                                                            selectedCardId ==
                                                            card.id
                                                                ? "active"
                                                                : ""
                                                        }`}
                                                    >
                                                        <div
                                                            style={{
                                                                width: 10,
                                                                height: 10,
                                                                borderRadius:
                                                                    "50%",
                                                                position:
                                                                    "absolute",
                                                                top: "50%",
                                                                left: "50%",
                                                                transform:
                                                                    "translate(-50%, -50%)",
                                                                backgroundColor:
                                                                    selectedCardId ==
                                                                    card.id
                                                                        ? "#d30000"
                                                                        : "inherit"
                                                            }}
                                                        ></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                                </>
                            )}

                            <a
                                onClick={() => this.submitForm()}
                                disabled={
                                    this.state.button_disabled ||
                                    this.state.is_loading
                                        ? "disabled"
                                        : null
                                }
                                className="text-decoration-none"
                            >
                                <div
                                    className="shadow d-flex align-items-center p-3 text-white text-center"
                                    style={{
                                        backgroundColor: "red",
                                        borderRadius: 35
                                    }}
                                >
                                    <div className="more w-100">
                                        <h6 className="m-0">Pay</h6>
                                    </div>
                                    <div className="ml-auto">
                                        <i className="icofont-simple-right"></i>
                                    </div>
                                </div>
                            </a>

                            <FooterBar
                                translation={translation}
                                active="orders"
                            />
                        </main>
                    ) : (
                        <div
                            style={{
                                textAlign: "center",
                                padding: 50
                            }}
                        >
                            <h2>No orders found...</h2>
                        </div>
                    )}
                </div>
            </div>
        );
    }
}

const mapSateToProps = state => ({
    orders: state.orders.Orders,
    selectedTable: state.orders.selectedTable,
    selectedTableOrders: state.orders.selectedTableOrders,
    account_info: state.store.account_info,
    translation: state.translation?.active?.data,
    checkoutOrders: state.checkout?.orders,
    isLogin: state.auth?.isLogin,
    auth: state.auth
});

export default connect(mapSateToProps, {
    fetchOrders,
    fetchTableOrders,
    selectOrdersForCheckout,
    addTipsForCheckout,
    refreshUser,
    addCardForCheckout
})(Pay);
