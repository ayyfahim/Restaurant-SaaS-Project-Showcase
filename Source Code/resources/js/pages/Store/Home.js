import React from "react";
import ReactDOM from "react-dom";
import NotificationSystem from "react-notification-system";

import { connect } from "react-redux";
import ROUTE from "../../config/route";
import FooterBar from "../Containers/FooterBar";
import RoundButton from "../Containers/RoundButton";
import api from "../../config/api";
import IsLoading from "../Containers/IsLoading";

// import html5QrCode from "html5-qrcode";
// import "html5-qrcode";
// var qrCode = require("html5-qrcode");
// import Html5Qrcode from "html5-qrcode";

let storeId = null;
let tableId = null;

var style = {
    NotificationItem: {
        // Override the notification item
        DefaultStyle: {
            // Applied to every notification, regardless of the notification level
            margin: "10px 5px 2px 1px",
            background: "#fff"
        },
        success: {
            color: "black"
        }
    }
};

class Store extends React.Component {
    state = {
        showCamera: false,
        is_loading: false
    };

    notificationSystem = React.createRef();

    isValidUrl(_string) {
        let url_string;
        try {
            url_string = new URL(_string);
        } catch (_) {
            return false;
        }
        return (
            url_string.protocol === "http:" || url_string.protocol === "https:"
        );
    }

    getStoreId(url) {
        const page = url.split("/");
        return page[4];
    }

    getStoreTableId(url) {
        const page = url.split("/");
        return page[5];
    }

    startCamera() {
        if (this.state.is_loading) return;

        const notification = this.notificationSystem.current;

        // const url =
        //     "https://chef-premium.test/store/459aac7f40f317cd6586c4f465580f1b05ff0f6a/70";

        const html5QrCode = new Html5Qrcode("reader");

        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            if (this.state.is_loading) return;

            this.setState({ is_loading: true });

            const url = decodedText;

            const fetchUrl = api.store.Check.fetch.path;

            let postData = {};

            try {
                postData = {
                    store_id: this.getStoreId(url),
                    table_id: this.getStoreTableId(url)
                };
            } catch (error) {
                this.setState({ showCamera: false, is_loading: false }, () => {
                    const notification = this.notificationSystem.current;
                    notification.addNotification({
                        message: "Not valid url.",
                        level: "error"
                    });
                });
                return;
            }

            fetch(fetchUrl, {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(postData)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.store_exist) {
                        window.location.href = url;
                    } else {
                        this.setState({ is_loading: false });
                        notification.addNotification({
                            message: "Wrong QR code. Store doesn't exist.",
                            level: "error"
                        });
                    }
                })
                .catch(error => {
                    this.setState({ is_loading: false });
                    notification.addNotification({
                        message: "An error occured please try again later.",
                        level: "error"
                    });
                });
        };

        const config = { fps: 10, qrbox: 250 };

        // If you want to prefer back camera

        html5QrCode
            .start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
            .catch(err => {
                this.setState({ showCamera: false }, () => {
                    const notification = this.notificationSystem.current;
                    notification.addNotification({
                        message: err,
                        level: "error"
                    });
                });
            });
    }

    componentDidCatch(error, errorInfo) {
        console.log(`error`, error);
    }

    render() {
        let { translation } = this.props;
        let { showCamera, is_loading } = this.state;

        return (
            <div
                class=""
                style={{
                    position: "fixed",
                    top: "0",
                    left: "0",
                    width: "100%",
                    height: "100%"
                }}
            >
                <NotificationSystem ref={this.notificationSystem} />
                <IsLoading is_loading={is_loading} />
                {!showCamera && (
                    <div
                        id="get_started"
                        style={{
                            position: "fixed",
                            top: "50%",
                            left: "50%",
                            transform: "translate(-50%, -50%)",
                            textAlign: "center",
                            width: "100%",
                            padding: "0px 30px"
                        }}
                    >
                        <div className="container">
                            <div className="row">
                                <div className="col-12 mb-3">
                                    <h1>Good Evening!</h1>
                                    <br />
                                    <p>Scan the QR code to beign</p>
                                    <br />
                                    <a
                                        onClick={() =>
                                            this.setState(
                                                { showCamera: true },
                                                () => {
                                                    this.startCamera();
                                                }
                                            )
                                        }
                                        className="text-decoration-none"
                                    >
                                        <RoundButton text={"LET'S GO"} />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                )}

                {showCamera && (
                    <div
                        id="camera_container"
                        style={{
                            position: "relative",
                            top: "0",
                            left: "0",
                            width: "100%",
                            height: "100%",
                            maxHeight: "100vh",
                            background: "#0000009e"
                        }}
                    >
                        <div
                            id="reader"
                            style={{
                                position: "absolute",
                                top: "50%",
                                left: "50%",
                                transform: "translate(-50%, -50%)",
                                width: "600px"
                            }}
                        />
                    </div>
                )}

                <FooterBar translation={translation} />
            </div>
        );
    }
}

const mapSateToProps = state => ({
    // store_name: state.store.store_name,
    // store_logo: state.store.store_logo,
    // store_logo_wide: state.store.store_logo_wide,
    // description: state.store.description,
    // sliders: state.store.sliders,
    // recommendedItems: state.store.recommendedItems,
    // account_info: state.store.account_info,
    // food_menus: state.store.food_menus,
    // categories: state.store.categories,
    // products: state.store.products,
    // cart: state.cart.Items,
    // is_accept_order: state.store.is_accept_order,
    // tables: state.store.tables,
    translation: state.translation?.active?.data
    // all_Translation: state.translation?.languages,
    // active_language_id: state.translation?.active?.id,
    // isLogin: state.auth?.isLogin,
    // customer_allergens: state.auth?.allergens
});

export default connect(mapSateToProps, {
    // fetchStoreItems,
    // addToCart,
    // setCart,
    // fetchTranslation,
    // fetchTable,
    // fetchAllTranslation
})(Store);
