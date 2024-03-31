import React from "react";
import ReactDOM from "react-dom";
import Header from "../../components/Header";
import SideBar from "../../components/SideBar";
import { NavLink, Route } from "react-router-dom";
import { connect } from "react-redux";
import domain from "../../config/api/domain";
import ROUTE from "../../config/route";
import FooterBar from "../Containers/FooterBar";
import SimpleHeader from "../Containers/SimpleHeader";
import NotificationSystem from "react-notification-system";
import { updateCustomer, refreshUser } from "../../actions/authAction";
import IsLoading from "../Containers/IsLoading";
import RoundButton from "../Containers/RoundButton";
import api from "../../config/api";
import miscVariables from "../../helpers/misc";

let storeId = null;

class Edit extends React.Component {
    notificationSystem = React.createRef();
    constructor(props) {
        super(props);
        this.state = {
            is_loading: false,

            show_add_card: false,
            show_warning: false,
            cardName: null
        };
        this.onChange = this.onChange.bind(this);
    }

    componentWillMount() {}

    onChange(e) {
        this.setState({ [e.target.name]: e.target.value });
    }

    toggleWarningBox = () => {
        this.setState(prevState => ({
            show_warning: !prevState.show_warning
        }));
    };

    addCardForm = () => {
        // if (this.state.is_loading) return;

        this.callServerPost();
    };

    // componentWillReceiveProps(nextProps) {
    //     const notification = this.notificationSystem.current;
    //     const { fetch } = nextProps;

    //     if (fetch.status == "error" && fetch.message != null) {
    //         this.setState(
    //             {
    //                 errors: fetch.errors,
    //                 is_loading: false,
    //                 fetch_message: fetch.message
    //             },
    //             () => {
    //                 notification.addNotification({
    //                     message: this.state.fetch_message,
    //                     level: "error"
    //                 });
    //             }
    //         );
    //     }

    //     if (fetch.status == "success" && fetch.message != null) {
    //         this.setState(
    //             {
    //                 errors: [],
    //                 is_loading: false,
    //                 fetch_message: fetch.message,

    //                 showEditBox: false,
    //                 editName: false,
    //                 editEmail: false,
    //                 editPhone: false
    //             },
    //             () => {
    //                 notification.addNotification({
    //                     message: this.state.fetch_message,
    //                     level: "success"
    //                 });
    //             }
    //         );
    //     }
    // }

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

    deleteCard = id => {
        let url = api.store.Card.delete.path;

        let data = {
            id: id
        };

        const res = fetch(url, {
            method: "POST",
            body: JSON.stringify(data),
            headers: {
                Authorization: "Bearer " + this.props.auth.token
            }
        })
            .then(response => response.json())
            .then(res => {
                this.setState(
                    {
                        modalText: "Card Deleted Successfully",
                        is_loading: false
                    },
                    () => {
                        let { modalText } = this.state;
                        var notification = this.notificationSystem.current;
                        notification.addNotification({
                            message: modalText,
                            level: "success"
                        });

                        this.props.refreshUser();
                    }
                );
            })
            .catch(error => {
                this.setState({ is_loading: false });
                var notification = this.notificationSystem.current;
                notification.addNotification({
                    message: "An error occurred.",
                    level: "error"
                });
            });
    };

    render() {
        let { translation } = this.props;
        const { show_add_card, show_warning, is_loading } = this.state;
        return (
            <div>
                <NotificationSystem ref={this.notificationSystem} />
                <IsLoading is_loading={is_loading} />
                <div className="fixed-bottom-padding bg-light-gray-2 h-100-vh">
                    <SimpleHeader text={"Cards"} />

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

                    {!show_add_card && (
                        <div className="container">
                            <div className="row justify-content-center mt-5">
                                <>
                                    <div className="col-12">
                                        <h5>Your Payment Cards</h5>
                                    </div>

                                    {this.props.auth.cards.map(card => (
                                        <div className="col-12">
                                            <div
                                                style={{
                                                    backgroundColor: "#fff",
                                                    padding: 15,
                                                    display: "flex",
                                                    marginBottom: "20px",
                                                    borderRadius: "7px"
                                                }}
                                                className="align-items-center"
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
                                                        // padding: "10px 0"
                                                    }}
                                                >
                                                    {card.card_name}
                                                </p>
                                                <div
                                                    style={{
                                                        marginLeft: "auto"
                                                    }}
                                                    className="text-danger"
                                                    onClick={() =>
                                                        this.setState(
                                                            {
                                                                is_loading: true
                                                            },
                                                            () =>
                                                                this.deleteCard(
                                                                    card.id
                                                                )
                                                        )
                                                    }
                                                >
                                                    <i
                                                        class="icofont-bin"
                                                        style={{
                                                            fontSize: 20
                                                        }}
                                                    ></i>
                                                </div>
                                            </div>
                                        </div>
                                    ))}

                                    <div className="col-9 py-3 text-secondary d-flex">
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
                                                marginBottom: "0",
                                                fontSize: "15px"
                                            }}
                                        >
                                            Please add a Payment Card to your
                                            account.
                                        </p>
                                    </div>

                                    <div className="col-12 mt-4">
                                        <a
                                            href="#"
                                            onClick={() =>
                                                this.toggleWarningBox()
                                            }
                                        >
                                            <RoundButton
                                                text={"Add New Card"}
                                            />
                                        </a>
                                    </div>

                                    <div className="col-12 mt-3 px-3">
                                        <div
                                            style={{
                                                borderBottom:
                                                    "1px solid #e2e2e2"
                                            }}
                                        ></div>
                                    </div>
                                </>
                            </div>
                        </div>
                    )}

                    <div
                        style={{
                            position: "relative",
                            overflow: "hidden",
                            minHeight: "1000px"
                        }}
                    >
                        {show_add_card && (
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
                        )}
                    </div>

                    <FooterBar translation={translation} />
                </div>
            </div>
        );
    }
}

const mapSateToProps = state => ({
    auth: state.auth
});

export default connect(mapSateToProps, { refreshUser })(Edit);
