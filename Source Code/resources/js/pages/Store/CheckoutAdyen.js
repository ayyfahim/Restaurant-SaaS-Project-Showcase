import React from "react";
import ReactDOM from "react-dom";
import Header from "../../components/Header";
import SideBar from "../../components/SideBar";
import { NavLink, Route } from "react-router-dom";
import { connect } from "react-redux";
import api from "../../config/api/";
import ROUTE from "../../config/route";
import FooterBar from "../Containers/FooterBar";
import NotificationSystem from "react-notification-system";
import { updateCustomer } from "../../actions/authAction";
import { createPayment } from "../../actions/checkoutAction";
import SuccessfullModal from "../Containers/SuccessfullModal";
import ErrorModal from "../Containers/ErrorModal";
import domain from "../../config/api/domain";
import axios from "axios";
import { PAYMENT_DONE } from "../../actions/types";
import miscVariables from "../../helpers/misc";

const clientKey = document.getElementById("clientKey").innerHTML;
const type = "dropin";
var PaymentPageUrl = "";
var PaymentOrderId = "";
var errorModalText = "An error occured. Please try again later.";

class Checkout extends React.Component {
    notificationSystem = React.createRef();
    constructor(props) {
        super(props);
        this.state = {
            name: "",
            phone: null,
            email: null,
            userId: null,
            is_loading: false,
            modalText: ""
        };
        this.updateIframe = this.updateIframe.bind(this);
    }

    componentWillMount() {
        // console.log("object", clientKey);
        // this.initCheckout();
        // this.updateIframe();
        // this.callServerPost();
    }

    componentDidMount() {
        // this.updateIframe();
        this.callServerPost();
    }

    // Calls your server endpoints
    callServerPost() {
        let data = this.props.order;
        let url = api.store.Checkout.limonetikCreateOrder.path;

        if (data == null) {
            this.errorOccured();
            return;
        }

        this.setState({ is_loading: true });

        let postData = {
            PaymentOrder: {
                MerchantId: "sandbox-mktpl-vendor2-fr",
                PaymentPageId: "creditcard",
                Amount: data.total,
                Currency: "EUR",
                MerchantUrls: {
                    ReturnUrl: `${domain.url}${ROUTE.ACCOUNT.ORDERS.PAGES.VIEW.PATH}`,
                    AbortedUrl: `${domain.url}${ROUTE.ACCOUNT.ORDERS.PAGES.VIEW.PATH}`,
                    ErrorUrl: `${domain.url}${ROUTE.ACCOUNT.ORDERS.PAGES.VIEW.PATH}`,
                    ServerNotificationUrl: `${domain.url}${ROUTE.ACCOUNT.ORDERS.PAGES.VIEW.PATH}`
                },
                MerchantOrder: {
                    Id: data.order_unique_id,
                    Customer: {
                        Email: this.props.auth.userEmail
                    }
                }
            }
        };

        const res = fetch(url, {
            method: "POST",
            body: JSON.stringify(data),
            headers: {
                Authorization:
                    "Bearer " +
                    JSON.parse(localStorage.getItem("state")).auth.token
            }
        })
            .then(response => response.json())
            .then(res => {
                console.log(`createPayment`, res);

                if (res.success == false) {
                    this.errorOccured();
                    return;
                }

                try {
                    var resData = JSON.parse(
                        res.payload.data.replace(/^"|"$/g, "")
                    );
                    console.log(
                        `createPaymentJsonParsed`,
                        JSON.parse(res.payload.data.replace(/^"|"$/g, ""))
                    );
                } catch (error) {
                    this.errorOccured();
                }

                PaymentPageUrl = resData.PaymentPageUrl;
                PaymentOrderId = resData.PaymentOrderId;

                this.setState({ is_loading: false }, () => {
                    this.updateIframe();
                });

                // var frame = document.getElementById("LmkFrame");
                // frame.setAttribute("src", resData.PaymentPageUrl);
            })
            .catch(error => {
                this.errorOccured();
                return;
            });
    }

    updateIframe() {
        // let resData = this.state.resData;
        var frame = document.getElementById("LmkFrame");
        frame.setAttribute("src", PaymentPageUrl);
        var currentSrcIframe =
            frame.ownerDocument.defaultView.window[0].location.href;
        console.log(`frame loaded`, frame);
        // console.log(`currentSrcIframe`, frame.ownerDocument.defaultView.window);
        frame.addEventListener("load", () => {
            {
                // var currentSrcIframe =
                //     frame.ownerDocument.defaultView.window[0].location.href;
                if (currentSrcIframe.includes("payment.limonetik") == false) {
                    // document.location.href = currentSrcIframe;
                    this.paymentReceived();
                    // this.setState(
                    //     {
                    //         modalText: "Payment Received Successfully"
                    //     },
                    //     () => {
                    //         this.paymentReceived();
                    //     }
                    // );
                }
            }
        });
    }

    chargePayment = PaymentOrder => {
        let url = api.store.Checkout.limonetikChargeOrder.path;

        let chargePaymentData = {
            PaymentOrderId: PaymentOrder.Id,
            ChargeAmount: PaymentOrder.Amount,
            Currency: "EUR",
            store_id: this.props.order.store_id,

            order_id: this.props.order.id,
            order_unique_id: this.props.order.order_unique_id,
            customer_id: this.props.auth.userId,
            amount: PaymentOrder.Amount,
            currency: PaymentOrder.Currency
            // status: PaymentOrder.Status
        };

        const res = fetch(url, {
            method: "POST",
            body: JSON.stringify(chargePaymentData),
            headers: {
                Authorization:
                    "Bearer " +
                    JSON.parse(localStorage.getItem("state")).auth.token
            }
        })
            .then(response => response.json())
            .then(res => {
                console.log(`chargePayment`, res);

                if (res.success == false) {
                    this.errorOccured();
                }

                try {
                    var resData = JSON.parse(
                        res.payload.data.replace(/^"|"$/g, "")
                    );
                    console.log(
                        `chargePaymentJsonParsed`,
                        JSON.parse(res.payload.data.replace(/^"|"$/g, ""))
                    );
                } catch (error) {
                    this.errorOccured();
                }

                this.setState(
                    {
                        modalText: "Payment Received Successfully",
                        is_loading: false
                    },
                    () => {
                        let { modalText } = this.state;
                        var notification = this.notificationSystem.current;

                        if (this.state.is_loading == false) {
                            notification.addNotification({
                                message: modalText,
                                level: "success"
                            });

                            document
                                .getElementById("#successfullModal")
                                .click();

                            // return;

                            let createPaymentData = {
                                limonetik_order_id: PaymentOrder.Id,
                                order_id: this.props.order.id,
                                order_unique_id: this.props.order
                                    .order_unique_id,
                                customer_id: this.props.auth.userId,
                                amount: PaymentOrder.Amount,
                                currency: PaymentOrder.Currency,
                                status: PaymentOrder.Status
                            };

                            this.props.createPayment(createPaymentData);

                            setTimeout(() => {
                                window.location.href = `/store/${localStorage.getItem(
                                    "storeId"
                                )}`;
                            }, miscVariables.setTimeoutTimer);
                        }
                    }
                );
            })
            .catch(error => {
                this.errorOccured();
            });
    };

    paymentReceived = () => {
        this.setState({ is_loading: true });
        let data = {
            PaymentOrderId: PaymentOrderId
        };
        let url = api.store.Checkout.limonetikGetOrder.path;

        const res = fetch(url, {
            method: "POST",
            body: JSON.stringify(data),
            headers: {
                Authorization:
                    "Bearer " +
                    JSON.parse(localStorage.getItem("state")).auth.token
            }
        })
            .then(response => response.json())
            .then(res => {
                this.setState({ is_loading: false });
                console.log(`paymentReceived`, res);
                console.log(
                    `paymentReceivedJsonParsed`,
                    JSON.parse(res.payload.data.replace(/^"|"$/g, ""))
                );

                try {
                    var resData = JSON.parse(
                        res.payload.data.replace(/^"|"$/g, "")
                    );
                } catch (error) {
                    this.errorOccured();
                }

                if (resData.PaymentOrder.Status == "Authorized") {
                    this.chargePayment(resData.PaymentOrder);
                }
            });
    };

    errorOccured = () => {
        this.setState(
            {
                is_loading: false,
                modalText: errorModalText
            },
            () => {
                let { modalText } = this.state;
                var notification = this.notificationSystem.current;

                notification.addNotification({
                    message: modalText,
                    level: "error"
                });

                document.getElementById("#errorModal").click();

                this.props.dispatchPaymentDone();
            }
        );

        // setTimeout(() => {
        //     window.location.href = `/store/${localStorage.getItem("storeId")}`;
        // }, miscVariables.setTimeoutTimer);

        return;
    };

    paymentReceivedOld = () => {
        this.setState(
            {
                modalText: "Payment Received Successfully"
            },
            () => {
                let { modalText } = this.state;
                var notification = this.notificationSystem.current;

                if (this.state.is_loading == false) {
                    notification.addNotification({
                        message: modalText,
                        level: "success"
                    });

                    document.getElementById("#successfullModal").click();

                    return;

                    setTimeout(() => {
                        window.location.href = `/store/${localStorage.getItem(
                            "storeId"
                        )}`;
                    }, miscVariables.setTimeoutTimer);
                }
            }
        );
    };

    render() {
        let { translation } = this.props;
        let { modalText, is_loading } = this.state;
        return (
            <div>
                <NotificationSystem ref={this.notificationSystem} />
                <div className="fixed-bottom-padding">
                    <div className="p-3 border-bottom shadow">
                        <div className="d-flex align-items-center">
                            <h5 className="font-weight-bold m-0">
                                {"Checkout"}
                            </h5>
                        </div>
                    </div>

                    <div className="osahan-body mt-5">
                        <div
                            className="cart-page1 shadow"
                            style={{
                                position: "relative",
                                overflow: "hidden",
                                minHeight: "1000px"
                            }}
                        >
                            {is_loading && (
                                <div
                                    style={{
                                        textAlign: "center",
                                        paddingTop: "50px",
                                        position: "absolute",
                                        width: "100%",
                                        height: "100%",
                                        top: "0",
                                        left: "0",
                                        zIndex: "100",
                                        backgroundColor: "#fff"
                                    }}
                                >
                                    <img
                                        src="/images/loading.gif"
                                        class="img-fluid mt-3"
                                    />
                                </div>
                            )}
                            <iframe
                                id="LmkFrame"
                                scrolling="no"
                                style={{
                                    // marginLeft: "33%",
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
                    </div>

                    <FooterBar translation={translation} />
                </div>
                <SuccessfullModal text={modalText} />
                <ErrorModal text={modalText} />
            </div>
        );
    }
}

const mapSateToProps = state => ({
    order: state.checkout.order,
    account_info: state.store.account_info,
    auth: state.auth
});

const mapDispatchToProps = dispatch => {
    return {
        createPayment: (creds, props) => dispatch(createPayment(creds, props)),
        dispatchPaymentDone: () =>
            dispatch({
                type: PAYMENT_DONE
            })
    };
};

export default connect(mapSateToProps, mapDispatchToProps)(Checkout);

// export default Checkout;
