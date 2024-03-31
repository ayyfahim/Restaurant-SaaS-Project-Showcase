import React from "react";
import ReactDOM from "react-dom";
import Header from "../../components/Header";
import SideBar from "../../components/SideBar";
import { NavLink, Route } from "react-router-dom";
import { connect } from "react-redux";
import api from "../../config/api";
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

    componentDidMount() {
        this.callServerPost();
    }

    checkStatus = async response => {
        if (response.status >= 200 && response.status < 300)
            return await response.json();

        throw await response.json();
    };

    // Calls your server endpoints
    callServerPost = async (retries = 5) => {
        // let retries = 5;

        let notification = this.notificationSystem.current;
        let tips = Number(this?.props?.order?.tips ?? 0);
        let data = {
            orderId: this?.props?.order?.id,
            total: (Number(this?.props?.order?.total) + tips).toFixed(2),
            tips: tips.toFixed(2)
        };
        let url = api.store.Checkout.limonetikCreateOrder.path;

        if (data == null) {
            this.errorOccured();
            return;
        }

        this.setState({ is_loading: true });

        const res = fetch(url, {
            method: "POST",
            body: JSON.stringify(data),
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
                    }
                    return;
                }
                this.errorOccured();
            });

        return res;
    };

    fetchUntilSucceeded = async (method, retry = 5) => {
        let success = false;
        let retried = 0;
        while (!success && retried <= retry) {
            try {
                switch (method) {
                    case "createPayment":
                        let result = await this.callServerPost();
                        break;

                    default:
                        return;
                        break;
                }

                success = true;
                //do your stuff with your result here
            } catch {
                //do your catch stuff here
                retried++;
            }
        }
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
                        is_loading: false
                    },
                    () => {
                        let { modalText } = this.state;
                        var notification = this.notificationSystem.current;
                        notification.addNotification({
                            message: modalText,
                            level: "success"
                        });

                        document.getElementById("#successfullModal").click();

                        setTimeout(() => {
                            window.location.href = `/store/${localStorage.getItem(
                                "storeId"
                            )}`;
                        }, miscVariables.setTimeoutTimer);
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

        setTimeout(() => {
            window.location.href = `/store/${localStorage.getItem("storeId")}`;
        }, miscVariables.setTimeoutTimer);

        return;
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
