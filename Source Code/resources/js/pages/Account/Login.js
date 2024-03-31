import React from "react";
import ReactDOM from "react-dom";
import ROUTE from "../../config/route";
import domain from "../../config/api/domain";
import { connect } from "react-redux";
import {
    loginCustomer,
    loginCustomerWithCallback,
    loginCustomerUsingSocial,
    sendOtpToEmail,
    verifyOtpEmail,
    registerCustomer,
    fetchAllergens,
    addAllergens
} from "../../actions/authAction";

import countries from "../../helpers/geo/countries.json";
import NotificationSystem from "react-notification-system";
import axios from "axios";
import api from "../../config/api";

import firebase from "firebase/app";
import "firebase/auth";
import libphonenumber from "google-libphonenumber";

import PhoneInput from "react-phone-input-2";
import "react-phone-input-2/lib/material.css";
import IsLoading from "../Containers/IsLoading";
import RoundButton from "../Containers/RoundButton";

class Login extends React.Component {
    notificationSystem = React.createRef();
    constructor(props) {
        super(props);
        this.state = {
            phoneNumber: "",
            phoneCountry: {},
            otpNumber: "",

            isOtp: true,
            otpSent: false,

            is_loading: false,
            customerFirstName: "",
            customerLastName: "",

            first_step: false,
            second_step: false,
            termsCheck: false,

            errors: []
        };
    }

    handleChange = e => {
        this.setState({
            [e.target.id]: e.target.value
        });
    };

    componentWillMount() {
        $(document.body).removeClass("modal-open");
        $(".modal-backdrop").remove();
    }

    componentDidMount() {
        if (this.state.is_loading) return;

        let urlCode = new URLSearchParams(window.location.search).get(
            "oobCode"
        );

        //  If Email Login
        if (urlCode != null) {
            this.setState({ is_loading: true });
            this.initializeFirebase(false);
            var that = this;
            var callBackLink = api.customer.auth.loginFacebookCallbackUrl.path;
            // Confirm the link is a sign-in with email link.
            if (firebase.auth().isSignInWithEmailLink(window.location.href)) {
                // Additional state parameters can also be passed via URL.
                // This can be used to continue the user's intended action before triggering
                // the sign-in operation.
                // Get the email if available. This should be available if the user completes
                // the flow on the same device where they started it.
                var email = window.localStorage.getItem("emailForSignIn");
                if (!email) {
                    // User opened the link on a different device. To prevent session fixation
                    // attacks, ask the user to provide the associated email again. For example:
                    email = window.prompt(
                        "Please provide your email for confirmation"
                    );
                }
                // The client SDK will parse the code from the link for you.
                firebase
                    .auth()
                    .signInWithEmailLink(email, window.location.href)
                    .then(function(result) {
                        window.localStorage.setItem("emailForSignIn", email);

                        result.user.getIdToken().then(function(result) {
                            var socialLoginTokenId = result;

                            let data = {
                                socialLoginTokenId: socialLoginTokenId
                            };

                            that.props.loginCustomerWithCallback(
                                data,
                                callBackLink,
                                that.props.history
                            );
                        });
                    })
                    .catch(function(error) {
                        // do error handling
                        console.log(error);
                        that.setState({ is_loading: false });
                    });
                that.setState({ is_loading: false });
            }
        }
    }

    checkStatus = async response => {
        if (response.status >= 200 && response.status < 300)
            return await response.json();

        throw await response.json();
    };

    submitOtp = () => {
        if (this.state.is_loading) return;
        this.setState({ is_loading: true });

        const { phoneNumber, phoneCountry } = this.state;

        const notification = this.notificationSystem.current;

        if (!phoneNumber) {
            notification.addNotification({
                message: "Please enter a Phone Number",
                level: "error"
            });
            return;
        }

        const url = api.customer.auth.checkCustomerByPhone.path;
        const data = {
            phone_number: "+" + phoneNumber,
            phone_country: phoneCountry?.countryCode
        };

        if (phoneNumber) {
            fetch(url, {
                method: "POST",
                body: JSON.stringify(data)
            })
                .then(response => this.checkStatus(response))
                .then(res => {
                    // Veryify the customer using OTP
                    this.setState({
                        customerFirstName: res.first_name,
                        is_loading: false
                    });
                    this.initializeFirebase();
                    this.sendOtp();
                })
                .catch(err => {
                    // New customer only ask for allergens and names
                    this.setState({
                        isOtp: false,
                        otpSent: false,
                        first_step: true,
                        is_loading: false
                    });
                    // console.log(`new customer`);
                });

            return;
        }
    };

    resendOtp() {
        if (this.state.is_loading) return;
        this.setState({ is_loading: true });

        const notification = this.notificationSystem.current;
        const phoneNumber = "+" + this.state.phoneNumber;
        const phoneCountry = this.state.phoneCountry?.countryCode;
        const PNF = libphonenumber.PhoneNumberFormat;
        const phoneUtil = libphonenumber.PhoneNumberUtil.getInstance();

        let number = phoneUtil.parse(phoneNumber, phoneCountry);
        const validPhoneNumber = phoneUtil.format(number, PNF.E164);

        if (phoneUtil.isValidNumber(number)) {
            firebase
                .auth()
                .signInWithPhoneNumber(
                    validPhoneNumber,
                    window.recaptchaVerifier
                )
                .then(confirmationResult => {
                    // SMS sent. On you mobile SMS will automatically sent via Firebase
                    // save with confirmationResult.confirm(code).
                    window.confirmationResult = confirmationResult;
                    // console.log(
                    //     `confirmationResult`,
                    //     window.confirmationResult
                    // );
                    this.setState({
                        otpSent: true,
                        is_loading: false
                    });
                })
                .catch(error => {
                    // Error; SMS will not sent
                    console.log("error", error);
                    var msg =
                        error.code == "auth/too-many-requests"
                            ? "Too many requests."
                            : error.message;

                    notification.addNotification({
                        message: msg,
                        level: "error"
                    });
                    this.setState({
                        is_loading: false
                    });
                    return false;
                });
        }
    }

    sendOtp() {
        if (this.state.is_loading) return;

        this.setState({ is_loading: true });

        const notification = this.notificationSystem.current;
        const phoneNumber = "+" + this.state.phoneNumber;
        const phoneCountry = this.state.phoneCountry?.countryCode;
        const PNF = libphonenumber.PhoneNumberFormat;
        const phoneUtil = libphonenumber.PhoneNumberUtil.getInstance();

        let number;

        try {
            number = phoneUtil.parse(phoneNumber, phoneCountry);
        } catch (error) {
            const msg = { error }.error.message;
            console.log(`error`, msg);
            notification.addNotification({
                message: msg,
                level: "error"
            });
            this.setState({
                is_loading: false
            });
            return;
        }

        if (phoneUtil.isValidNumber(number)) {
            const validPhoneNumber = phoneUtil.format(number, PNF.E164);

            firebase
                .auth()
                .signInWithPhoneNumber(
                    validPhoneNumber,
                    window.recaptchaVerifier
                )
                .then(confirmationResult => {
                    // SMS sent. On you mobile SMS will automatically sent via Firebase
                    // save with confirmationResult.confirm(code).
                    window.confirmationResult = confirmationResult;
                    // console.log(
                    //     `confirmationResult`,
                    //     window.confirmationResult
                    // );
                    this.setState({
                        otpSent: true,
                        is_loading: false
                    });
                })
                .catch(error => {
                    // Error; SMS will not sent
                    console.log("error", error);
                    var msg =
                        error.code == "auth/too-many-requests"
                            ? "Too many requests."
                            : error.message;

                    notification.addNotification({
                        message: msg,
                        level: "error"
                    });
                    this.setState({
                        is_loading: false
                    });
                    return false;
                });
        } else {
            notification.addNotification({
                message: "The phone number is not valid.",
                level: "error"
            });
        }
    }

    verifyOtp() {
        if (this.state.is_loading) return;
        this.setState({ is_loading: true });
        const notification = this.notificationSystem.current;
        window.confirmationResult
            .confirm(this.state.otpNumber)
            .then(result => {
                const phoneNumber = "+" + this.state.phoneNumber;

                let data = {
                    phone_number: phoneNumber
                };

                this.props.loginCustomer(data, this.props.history);
                this.setState({ is_loading: false });
                return;
            })
            .catch(error => {
                var msg =
                    error.code == "auth/invalid-verification-code"
                        ? "The SMS verification code is invalid. Please resend the verification code."
                        : error.message;

                notification.addNotification({
                    message: msg,
                    level: "error"
                });
                this.setState({ is_loading: false });
                return false;
            });
    }

    initializeFirebase(recaptchaContainer = true) {
        if (this.state.is_loading) return;
        if (!firebase.apps.length) {
            firebase.initializeApp(firebaseConfig);
            if (recaptchaContainer) {
                $("#recaptcha-container").addClass("has_child");
                window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier(
                    "recaptcha-container",
                    {
                        size: "invisible",
                        callback: function(response) {
                            $("#recaptcha-container").removeClass("has_child");
                            console.log("response :>> ", response);
                        }
                    }
                );
            }
        }
    }

    componentWillReceiveProps(nextProps) {
        const notification = this.notificationSystem.current;
        const { fetch } = nextProps;

        if (
            fetch?.status == "error" &&
            fetch?.status !== this.props.fetch?.status
        ) {
            this.setState({
                errors: fetch.errors,
                is_loading: false
            });

            if (
                fetch?.message?.length > 0 &&
                fetch?.message !== this.props.fetch?.message
            ) {
                notification.addNotification({
                    message: fetch.message,
                    level: "error"
                });
            }

            if (
                fetch.errors?.phone?.length > 0 &&
                fetch?.errors !== this.props.fetch?.errors
            ) {
                this.setState({
                    isOtp: true,
                    otpSent: false,
                    first_step: false,
                    second_step: false,
                    is_loading: false
                });
            }
        }
    }

    loginWithSocial(provider) {
        this.initializeFirebase();
        var facebookProvider = new firebase.auth.FacebookAuthProvider();
        var googleProvider = new firebase.auth.GoogleAuthProvider();
        var facebookCallbackLink =
            api.customer.auth.loginFacebookCallbackUrl.path;
        var googleCallbackLink = api.customer.auth.loginGoogleCallbackUrl.path;

        var socialProvider = null;
        var callBackLink = null;
        if (provider == "facebook") {
            socialProvider = facebookProvider;
            callBackLink = facebookCallbackLink;
        } else if (provider == "google") {
            socialProvider = googleProvider;
            callBackLink = facebookCallbackLink;
            googleCallbackLink;
        } else {
            return;
        }

        var that = this;

        firebase
            .auth()
            .signInWithPopup(socialProvider)
            .then(function(result) {
                result.user.getIdToken().then(function(result) {
                    var socialLoginTokenId = result;

                    let data = {
                        socialLoginTokenId: socialLoginTokenId
                    };

                    that.props.loginCustomerWithCallback(
                        data,
                        callBackLink,
                        that.props.history
                    );
                });
            })
            .catch(function(error) {
                // do error handling
                console.log(error);
            });
    }

    firstStepDone() {
        let { termsCheck } = this.state;

        if (!termsCheck) {
            const notification = this.notificationSystem.current;

            notification.addNotification({
                message: "Please check our terms and conditions.",
                level: "error"
            });
            return;
        }

        let data = {
            phone: this.state.phoneNumber,
            phoneCountry: this.state.phoneCountry?.countryCode,
            first_name: this.state.customerFirstName,
            last_name: this.state.customerLastName
        };

        this.props.registerCustomer(data);

        this.setState({
            first_step: false,
            second_step: true
        });
    }

    secondStepDone() {
        const phoneNumber = "+" + this.state.phoneNumber;

        let data = {
            phone_number: phoneNumber
        };

        this.props.loginCustomer(data, this.props.history);
    }

    addUserAllergens(id) {
        let { customer_allergens } = this.props;

        let find = customer_allergens.find(data => data.id == id.id);
        const newData = [];
        if (find) {
            const newData = customer_allergens.filter(
                data => data.id != find.id
            );
            console.log(`newData`, newData);

            let postData = {
                allergens: newData
            };
            this.props.addAllergens(postData);
        } else {
            const newData = [...customer_allergens, id];
            console.log(`newData`, newData);

            let postData = {
                allergens: newData
            };
            this.props.addAllergens(postData);
        }
    }

    addNoAllergens() {
        let { customer_allergens } = this.props;

        let find = customer_allergens.filter(data => data.type == 2);

        let postData = {
            allergens: find
        };
        this.props.addAllergens(postData);
    }

    renderAllergens() {
        let { allergens, customer_allergens } = this.props;

        return (
            <>
                {allergens.length > 0 && (
                    <>
                        <div className="col-12 mt-2 text-center">
                            <h5>Select Diet Preferences</h5>
                        </div>
                        <div className="col-12 mt-1">
                            <div className="bg-white clearfix edit-item">
                                <div className="row justify-content-center py-2">
                                    {allergens.map(
                                        data =>
                                            data.type == 2 && (
                                                <div
                                                    className="col-4"
                                                    onClick={() => {
                                                        this.addUserAllergens(
                                                            data
                                                        );
                                                    }}
                                                >
                                                    <div className="text-center">
                                                        <img
                                                            src={`/${
                                                                customer_allergens.find(
                                                                    allergen =>
                                                                        allergen.id ==
                                                                        data.id
                                                                )
                                                                    ? data.active_image_url
                                                                    : data.image_url
                                                            }`}
                                                            alt=""
                                                            className="img-fluid"
                                                            style={{
                                                                height: 40,
                                                                width: 40,
                                                                display:
                                                                    "block",
                                                                margin: "auto"
                                                            }}
                                                        />
                                                        {data.name}
                                                    </div>
                                                </div>
                                            )
                                    )}
                                </div>
                            </div>
                        </div>

                        <div className="col-12 mt-3 text-center">
                            <h5>Select Your Allergens</h5>
                        </div>
                        <div className="col-12 mt-1">
                            <div className="bg-white clearfix edit-item">
                                <div className="row justify-content-center py-2">
                                    {allergens.map(
                                        data =>
                                            data.type == 1 && (
                                                <div
                                                    className="col-4"
                                                    onClick={() => {
                                                        this.addUserAllergens(
                                                            data
                                                        );
                                                    }}
                                                >
                                                    <div className="text-center">
                                                        <img
                                                            src={`/${
                                                                customer_allergens.find(
                                                                    allergen =>
                                                                        allergen.id ==
                                                                        data.id
                                                                )
                                                                    ? data.active_image_url
                                                                    : data.image_url
                                                            }`}
                                                            alt=""
                                                            className="img-fluid"
                                                            style={{
                                                                height: 40,
                                                                width: 40,
                                                                display:
                                                                    "block",
                                                                margin: "auto"
                                                            }}
                                                        />
                                                        {data.name}
                                                    </div>
                                                </div>
                                            )
                                    )}
                                    <div
                                        className="col-4"
                                        onClick={() => {
                                            this.addNoAllergens();
                                        }}
                                    >
                                        <div className="text-center">
                                            <img
                                                src={`${
                                                    customer_allergens.find(
                                                        allergen =>
                                                            allergen.type == 1
                                                    )
                                                        ? "/images/icons/no_allergens.png"
                                                        : "/images/icons/no_allergens_active.png"
                                                }`}
                                                alt=""
                                                className="img-fluid"
                                                style={{
                                                    height: 40,
                                                    width: 40,
                                                    display: "block",
                                                    margin: "auto"
                                                }}
                                            />
                                            No Allergens
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="col-12 mt-3">
                            <a
                                onClick={() => this.secondStepDone()}
                                disabled={
                                    this.state.is_loading ? "disabled" : null
                                }
                                className="text-decoration-none"
                            >
                                <RoundButton text={"REGISTER"} />
                            </a>
                        </div>
                    </>
                )}
            </>
        );
    }

    render() {
        let {
            isOtp,
            errors,
            otpSent,
            is_loading,
            customerFirstName,
            customerLastName,
            phoneNumber,
            first_step,
            second_step
        } = this.state;
        return (
            <>
                <div id="recaptcha-container"></div>
                <IsLoading is_loading={is_loading} />
                <NotificationSystem ref={this.notificationSystem} />

                <div
                    className="osahan-signin container px-5"
                    style={{
                        position: "fixed",
                        top: "50%",
                        left: "50%",
                        transform: "translate(-50%, -50%)"
                    }}
                >
                    {isOtp && !otpSent && (
                        <div className="row">
                            <div className="col-12">
                                <h3
                                    className="my-0 text-center"
                                    style={{ color: "red" }}
                                >
                                    LET'S GET STARTED
                                </h3>
                                <p
                                    className="text-center"
                                    style={{
                                        fontWeight: "500",
                                        fontSize: "16px"
                                    }}
                                >
                                    Enter your number
                                </p>
                            </div>
                            <div className="col-12 mb-4">
                                <PhoneInput
                                    country={"us"}
                                    value={this.state.phoneNumber}
                                    enableSearch={true}
                                    onChange={(phoneNumber, phoneCountry) =>
                                        this.setState({
                                            phoneNumber,
                                            phoneCountry
                                        })
                                    }
                                />

                                {errors?.phone && (
                                    <div class="text-danger mt-2">
                                        {errors?.phone[0]}
                                    </div>
                                )}
                            </div>
                            <div
                                className="col-12"
                                onClick={() => this.submitOtp()}
                            >
                                <RoundButton text={"SIGN IN"} arrow={false} />
                            </div>

                            <div
                                className="col my-4"
                                style={{
                                    height: 1,
                                    backgroundColor: "#bfbfbf"
                                }}
                            ></div>
                            <div
                                className="col-1 my-3 p-0"
                                style={{
                                    fontWeight: "lighter",
                                    color: "#bfbfbf",
                                    textAlign: "center"
                                }}
                            >
                                OR
                            </div>
                            <div
                                className="col my-4"
                                style={{
                                    height: 1,
                                    backgroundColor: "#bfbfbf"
                                }}
                            ></div>

                            <div className="col-12">
                                <a
                                    onClick={() => {
                                        this.loginWithSocial("facebook");
                                    }}
                                    className="btn btn-lg rounded mt-2 mr-2 social-signin-btn"
                                >
                                    <div className="icon">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 64 64"
                                            width="64px"
                                            height="64px"
                                        >
                                            <radialGradient
                                                id="nT5WH7nXAOiS46rXmee3Oa"
                                                cx="33.34"
                                                cy="27.936"
                                                r="43.888"
                                                gradientTransform="matrix(1 0 0 -1 0 66)"
                                                gradientUnits="userSpaceOnUse"
                                            >
                                                <stop
                                                    offset="0"
                                                    stop-color="#f4e9c3"
                                                />
                                                <stop
                                                    offset=".219"
                                                    stop-color="#f8eecd"
                                                />
                                                <stop
                                                    offset=".644"
                                                    stop-color="#fdf4dc"
                                                />
                                                <stop
                                                    offset="1"
                                                    stop-color="#fff6e1"
                                                />
                                            </radialGradient>
                                            <path
                                                fill="url(#nT5WH7nXAOiS46rXmee3Oa)"
                                                d="M51.03,37.34c0.16,0.98,1.08,1.66,2.08,1.66h5.39c2.63,0,4.75,2.28,4.48,4.96	C62.74,46.3,60.64,48,58.29,48H49c-1.22,0-2.18,1.08-1.97,2.34c0.16,0.98,1.08,1.66,2.08,1.66h8.39c1.24,0,2.37,0.5,3.18,1.32	C61.5,54.13,62,55.26,62,56.5c0,2.49-2.01,4.5-4.5,4.5h-49c-1.52,0-2.9-0.62-3.89-1.61C3.62,58.4,3,57.02,3,55.5	C3,52.46,5.46,50,8.5,50H14c1.22,0,2.18-1.08,1.97-2.34C15.81,46.68,14.89,44,13.89,44H5.5c-2.63,0-4.75-2.28-4.48-4.96	C1.26,36.7,3.36,35,5.71,35H8c1.71,0,3.09-1.43,3-3.16C10.91,30.22,9.45,29,7.83,29H4.5c-2.63,0-4.75-2.28-4.48-4.96	C0.26,21.7,2.37,20,4.71,20H20c0.83,0,1.58-0.34,2.12-0.88C22.66,18.58,23,17.83,23,17c0-1.66-1.34-3-3-3h-1.18	c-0.62-0.09-1.43,0-2.32,0h-9c-1.52,0-2.9-0.62-3.89-1.61S2,10.02,2,8.5C2,5.46,4.46,3,7.5,3h49c3.21,0,5.8,2.79,5.47,6.06	C61.68,11.92,60.11,14,57.24,14H52c-2.76,0-5,2.24-5,5c0,1.38,0.56,2.63,1.46,3.54C49.37,23.44,50.62,24,52,24h6.5	c3.21,0,5.8,2.79,5.47,6.06C63.68,32.92,61.11,35,58.24,35H53C51.78,35,50.82,36.08,51.03,37.34z"
                                            />
                                            <linearGradient
                                                id="nT5WH7nXAOiS46rXmee3Ob"
                                                x1="32"
                                                x2="32"
                                                y1="-3.34"
                                                y2="59.223"
                                                gradientTransform="matrix(1 0 0 -1 0 66)"
                                                gradientUnits="userSpaceOnUse"
                                            >
                                                <stop
                                                    offset="0"
                                                    stop-color="#155cde"
                                                />
                                                <stop
                                                    offset=".278"
                                                    stop-color="#1f7fe5"
                                                />
                                                <stop
                                                    offset=".569"
                                                    stop-color="#279ceb"
                                                />
                                                <stop
                                                    offset=".82"
                                                    stop-color="#2cafef"
                                                />
                                                <stop
                                                    offset="1"
                                                    stop-color="#2eb5f0"
                                                />
                                            </linearGradient>
                                            <path
                                                fill="url(#nT5WH7nXAOiS46rXmee3Ob)"
                                                d="M58,32c0,13.35-10.05,24.34-23,25.83C34.02,57.94,33.01,58,32,58c-1.71,0-3.38-0.17-5-0.49	C15.03,55.19,6,44.65,6,32C6,17.64,17.64,6,32,6S58,17.64,58,32z"
                                            />
                                            <path
                                                fill="#fff"
                                                d="M42.8,36.05l-0.76,2C41.6,39.22,40.46,40,39.19,40H35v17.83C34.02,57.94,33.01,58,32,58	c-1.71,0-3.38-0.17-5-0.49V40h-2.95C22.36,40,21,38.66,21,37v-2c0-1.66,1.36-3,3.05-3H27v-6c0-5.51,4.49-10,10-10h3	c2.21,0,4,1.79,4,4s-1.79,4-4,4h-3c-1.1,0-2,0.9-2,2v6h4.95C42.08,32,43.55,34.09,42.8,36.05z"
                                            />
                                        </svg>
                                    </div>
                                    <div className="text">
                                        Sign in with Facebook
                                    </div>
                                </a>
                                <a
                                    onClick={() => {
                                        this.loginWithSocial("google");
                                    }}
                                    className="btn btn-lg rounded mt-2 mr-2 social-signin-btn"
                                >
                                    <div className="icon">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 64 64"
                                            width="64px"
                                            height="64px"
                                        >
                                            <radialGradient
                                                id="95yY7w43Oj6n2vH63j6HJa"
                                                cx="31.998"
                                                cy="34.5"
                                                r="30.776"
                                                gradientTransform="matrix(1 0 0 -1 0 66)"
                                                gradientUnits="userSpaceOnUse"
                                            >
                                                <stop
                                                    offset="0"
                                                    stop-color="#f4e9c3"
                                                />
                                                <stop
                                                    offset=".219"
                                                    stop-color="#f8eecd"
                                                />
                                                <stop
                                                    offset=".644"
                                                    stop-color="#fdf4dc"
                                                />
                                                <stop
                                                    offset="1"
                                                    stop-color="#fff6e1"
                                                />
                                            </radialGradient>
                                            <path
                                                fill="url(#95yY7w43Oj6n2vH63j6HJa)"
                                                d="M63.97,30.06C63.68,32.92,61.11,35,58.24,35H53c-1.22,0-2.18,1.08-1.97,2.34	c0.16,0.98,1.08,1.66,2.08,1.66h3.39c2.63,0,4.75,2.28,4.48,4.96C60.74,46.3,58.64,48,56.29,48H51c-1.22,0-2.18,1.08-1.97,2.34	c0.16,0.98,1.08,1.66,2.08,1.66h3.39c1.24,0,2.37,0.5,3.18,1.32C58.5,54.13,59,55.26,59,56.5c0,2.49-2.01,4.5-4.5,4.5h-44	c-1.52,0-2.9-0.62-3.89-1.61C5.62,58.4,5,57.02,5,55.5c0-3.04,2.46-5.5,5.5-5.5H14c1.22,0,2.18-1.08,1.97-2.34	C15.81,46.68,14.89,46,13.89,46H5.5c-2.63,0-4.75-2.28-4.48-4.96C1.26,38.7,3.36,37,5.71,37H13c1.71,0,3.09-1.43,3-3.16	C15.91,32.22,14.45,31,12.83,31H4.5c-2.63,0-4.75-2.28-4.48-4.96C0.26,23.7,2.37,22,4.71,22h9.79c1.24,0,2.37-0.5,3.18-1.32	C18.5,19.87,19,18.74,19,17.5c0-2.49-2.01-4.5-4.5-4.5h-6c-1.52,0-2.9-0.62-3.89-1.61S3,9.02,3,7.5C3,4.46,5.46,2,8.5,2h48	c3.21,0,5.8,2.79,5.47,6.06C61.68,10.92,60.11,13,57.24,13H55.5c-3.04,0-5.5,2.46-5.5,5.5c0,1.52,0.62,2.9,1.61,3.89	C52.6,23.38,53.98,24,55.5,24h3C61.71,24,64.3,26.79,63.97,30.06z"
                                            />
                                            <linearGradient
                                                id="95yY7w43Oj6n2vH63j6HJb"
                                                x1="29.401"
                                                x2="29.401"
                                                y1="4.064"
                                                y2="106.734"
                                                gradientTransform="matrix(1 0 0 -1 0 66)"
                                                gradientUnits="userSpaceOnUse"
                                            >
                                                <stop
                                                    offset="0"
                                                    stop-color="#ff5840"
                                                />
                                                <stop
                                                    offset=".007"
                                                    stop-color="#ff5840"
                                                />
                                                <stop
                                                    offset=".989"
                                                    stop-color="#fa528c"
                                                />
                                                <stop
                                                    offset="1"
                                                    stop-color="#fa528c"
                                                />
                                            </linearGradient>
                                            <path
                                                fill="url(#95yY7w43Oj6n2vH63j6HJb)"
                                                d="M47.46,15.5l-1.37,1.48c-1.34,1.44-3.5,1.67-5.15,0.6c-2.71-1.75-6.43-3.13-11-2.37	c-4.94,0.83-9.17,3.85-11.64,7.97l-8.03-6.08C14.99,9.82,23.2,5,32.5,5c5,0,9.94,1.56,14.27,4.46	C48.81,10.83,49.13,13.71,47.46,15.5z"
                                            />
                                            <linearGradient
                                                id="95yY7w43Oj6n2vH63j6HJc"
                                                x1="12.148"
                                                x2="12.148"
                                                y1=".872"
                                                y2="47.812"
                                                gradientTransform="matrix(1 0 0 -1 0 66)"
                                                gradientUnits="userSpaceOnUse"
                                            >
                                                <stop
                                                    offset="0"
                                                    stop-color="#feaa53"
                                                />
                                                <stop
                                                    offset=".612"
                                                    stop-color="#ffcd49"
                                                />
                                                <stop
                                                    offset="1"
                                                    stop-color="#ffde44"
                                                />
                                            </linearGradient>
                                            <path
                                                fill="url(#95yY7w43Oj6n2vH63j6HJc)"
                                                d="M16.01,30.91c-0.09,2.47,0.37,4.83,1.27,6.96l-8.21,6.05c-1.35-2.51-2.3-5.28-2.75-8.22	c-1.06-6.88,0.54-13.38,3.95-18.6l8.03,6.08C16.93,25.47,16.1,28.11,16.01,30.91z"
                                            />
                                            <linearGradient
                                                id="95yY7w43Oj6n2vH63j6HJd"
                                                x1="29.76"
                                                x2="29.76"
                                                y1="32.149"
                                                y2="-6.939"
                                                gradientTransform="matrix(1 0 0 -1 0 66)"
                                                gradientUnits="userSpaceOnUse"
                                            >
                                                <stop
                                                    offset="0"
                                                    stop-color="#42d778"
                                                />
                                                <stop
                                                    offset=".428"
                                                    stop-color="#3dca76"
                                                />
                                                <stop
                                                    offset="1"
                                                    stop-color="#34b171"
                                                />
                                            </linearGradient>
                                            <path
                                                fill="url(#95yY7w43Oj6n2vH63j6HJd)"
                                                d="M50.45,51.28c-4.55,4.07-10.61,6.57-17.36,6.71C22.91,58.2,13.66,52.53,9.07,43.92l8.21-6.05	C19.78,43.81,25.67,48,32.5,48c3.94,0,7.52-1.28,10.33-3.44L50.45,51.28z"
                                            />
                                            <linearGradient
                                                id="95yY7w43Oj6n2vH63j6HJe"
                                                x1="46"
                                                x2="46"
                                                y1="3.638"
                                                y2="35.593"
                                                gradientTransform="matrix(1 0 0 -1 0 66)"
                                                gradientUnits="userSpaceOnUse"
                                            >
                                                <stop
                                                    offset="0"
                                                    stop-color="#155cde"
                                                />
                                                <stop
                                                    offset=".278"
                                                    stop-color="#1f7fe5"
                                                />
                                                <stop
                                                    offset=".569"
                                                    stop-color="#279ceb"
                                                />
                                                <stop
                                                    offset=".82"
                                                    stop-color="#2cafef"
                                                />
                                                <stop
                                                    offset="1"
                                                    stop-color="#2eb5f0"
                                                />
                                            </linearGradient>
                                            <path
                                                fill="url(#95yY7w43Oj6n2vH63j6HJe)"
                                                d="M59,31.97c0.01,7.73-3.26,14.58-8.55,19.31l-7.62-6.72c2.1-1.61,3.77-3.71,4.84-6.15	c0.29-0.66-0.2-1.41-0.92-1.41H37c-2.21,0-4-1.79-4-4v-2c0-2.21,1.79-4,4-4h17C56.75,27,59,29.22,59,31.97z"
                                            />
                                        </svg>
                                    </div>
                                    <div className="text">
                                        Sign in with Google
                                    </div>
                                </a>
                                <a
                                    onClick={() => {
                                        this.props.history.goBack();
                                    }}
                                    className="mt-5 d-block"
                                >
                                    <RoundButton
                                        text={"Go Back"}
                                        arrow={true}
                                        arrowPosition={"left"}
                                        color={"#d7d700"}
                                    />
                                </a>
                            </div>
                        </div>
                    )}

                    {isOtp && otpSent && (
                        <>
                            <div className="text-center row">
                                <div className="col-12 text-center">
                                    <h6>Welcome back {customerFirstName}!</h6>
                                    <br />
                                    <p className="h6 font-weight-normal text-secondary">
                                        A sms code was sent to
                                    </p>
                                    <h6>{`${"+" + phoneNumber}`}</h6>
                                    <p className="h6 font-weight-normal text-danger">
                                        Please enter the 6 digit code below
                                    </p>
                                    <h6
                                        className="text-danger"
                                        onClick={() => this.resendOtp()}
                                    >
                                        Resend Code
                                    </h6>
                                </div>

                                <div className="col-12">
                                    <br />
                                    <br />
                                </div>

                                <div className="col-12">
                                    <div className="form-group custom-text-box-1 mb-0">
                                        <span>6 Digit Code</span>
                                        <input
                                            type="number"
                                            className="form-control d-inline-flex"
                                            name="otpNumber"
                                            id="otpNumber"
                                            value={this.state.otpNumber}
                                            onChange={this.handleChange}
                                        />
                                    </div>
                                    <br />
                                    <br />
                                    <br />
                                    <br />
                                    <a
                                        onClick={() => this.verifyOtp()}
                                        disabled={
                                            this.state.is_loading
                                                ? "disabled"
                                                : null
                                        }
                                        className="text-decoration-none"
                                    >
                                        <RoundButton text={"CONTINUE"} />
                                    </a>
                                </div>
                            </div>
                        </>
                    )}

                    {!isOtp && !otpSent && (
                        <>
                            {first_step && (
                                <>
                                    <div className="row">
                                        <div className="col-12 text-center">
                                            <h2 className="text-danger">
                                                Let's get to know each other!
                                            </h2>
                                            <h6>Let us know your name</h6>
                                            <br />
                                        </div>
                                        <div className="col-12">
                                            <div className="form-group custom-text-box-1 mb-0">
                                                <span>First Name</span>
                                                <input
                                                    type="text"
                                                    className="form-control d-inline-flex"
                                                    name="customerFirstName"
                                                    id="customerFirstName"
                                                    value={customerFirstName}
                                                    onChange={this.handleChange}
                                                />
                                            </div>
                                            <br />
                                            <div className="form-group custom-text-box-1 mb-0">
                                                <span>Last Name</span>
                                                <input
                                                    type="text"
                                                    className="form-control d-inline-flex"
                                                    name="customerLastName"
                                                    id="customerLastName"
                                                    value={customerLastName}
                                                    onChange={this.handleChange}
                                                />
                                            </div>
                                            <br />
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    value=""
                                                    id="termsCheck"
                                                    value={
                                                        this.state.termsCheck
                                                    }
                                                    onChange={() => {
                                                        this.setState(
                                                            prevState => ({
                                                                termsCheck: !prevState.termsCheck
                                                            })
                                                        );
                                                    }}
                                                />
                                                <label
                                                    class="form-check-label"
                                                    for="termsCheck"
                                                >
                                                    I have read and agree to the
                                                    Appetizr Terms and
                                                    Conditions & Privacy Policy
                                                </label>
                                            </div>

                                            <br />
                                            <br />
                                            <a
                                                onClick={() =>
                                                    this.firstStepDone()
                                                }
                                                disabled={
                                                    this.state.is_loading
                                                        ? "disabled"
                                                        : null
                                                }
                                                className="text-decoration-none"
                                            >
                                                <RoundButton text={"NEXT"} />
                                            </a>
                                        </div>
                                    </div>
                                </>
                            )}

                            {second_step && (
                                <>
                                    <div className="row">
                                        {this.renderAllergens()}
                                    </div>
                                </>
                            )}
                        </>
                    )}
                </div>
            </>
        );
    }
}

const mapSateToProps = state => ({
    fetch: state.fetch,
    allergens: state.allergens?.allergens,
    customer_allergens: state.auth?.allergens
});

const mapDispatchToProps = dispatch => {
    return {
        sendOtpToEmail: (creds, props) =>
            dispatch(sendOtpToEmail(creds, props)),
        verifyOtpEmail: (creds, props) =>
            dispatch(verifyOtpEmail(creds, props)),
        loginCustomer: (creds, props) => dispatch(loginCustomer(creds, props)),
        loginCustomerUsingSocial: (creds, props) =>
            dispatch(loginCustomerUsingSocial(creds, props)),
        loginCustomerWithCallback: (creds, props) =>
            dispatch(loginCustomerWithCallback(creds, props)),
        registerCustomer: (creds, props) =>
            dispatch(registerCustomer(creds, props)),
        fetchAllergens: (creds, props) =>
            dispatch(fetchAllergens(creds, props)),
        addAllergens: (creds, props) => dispatch(addAllergens(creds, props))
    };
};

export default connect(mapSateToProps, mapDispatchToProps)(Login);
