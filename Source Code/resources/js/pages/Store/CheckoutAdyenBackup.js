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
import SuccessfullModal from "../Containers/SuccessfullModal";
import ErrorModal from "../Containers/ErrorModal";
import domain from "../../config/api/domain";
import axios from "axios";

const clientKey = document.getElementById("clientKey").innerHTML;
const type = "dropin";

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
    }

    componentDidMount() {
        this.callServerPost();
        // this.updateIframe();
    }

    updateIframe() {
        var frame = document.getElementById("LmkFrame");
        frame.addEventListener("load", () => {
            {
                var currentSrcIframe =
                    frame.ownerDocument.defaultView.window[0].location.href;
                if (currentSrcIframe.includes("payment.limonetik") == false) {
                    // document.location.href = currentSrcIframe;
                    this.setState(
                        {
                            modalText: "Payment Received Successfully"
                        },
                        () => {
                            let { modalText } = this.state;
                            var notification = this.notificationSystem.current;

                            notification.addNotification({
                                message: modalText,
                                level: "success"
                            });

                            document
                                .getElementById("#successfullModal")
                                .click();

                            return;

                            setTimeout(() => {
                                window.location.href = `/store/${localStorage.getItem(
                                    "storeId"
                                )}`;
                            }, 10000);
                        }
                    );
                }
            }
        });
    }

    paymentReceived = () => {
        this.setState(
            {
                modalText: "Payment Received Successfully"
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
                }, 500000);
            }
        );
    };

    // Calls your server endpoints
    async callServerPost() {
        let data = this.props.order;
        let url = api.store.Checkout.limonetikCreateOrder.path;

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

        const res = await fetch(url, {
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
                console.log(`res`, res);
                console.log(
                    `res`,
                    JSON.parse(res.payload.data.replace(/^"|"$/g, ""))
                );
                var resData = JSON.parse(
                    res.payload.data.replace(/^"|"$/g, "")
                );
                var frame = document.getElementById("LmkFrame");
                frame.setAttribute("src", resData.PaymentPageUrl);
                // this.updateIframe();
            });
    }

    render() {
        let { translation } = this.props;
        let { modalText } = this.state;
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
                            <div className="p-3" id="dropin"></div>
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

export default connect(mapSateToProps)(Checkout);

// export default Checkout;
