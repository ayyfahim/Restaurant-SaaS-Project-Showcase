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
    verifyOtpEmail
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

class Login extends React.Component {
    notificationSystem = React.createRef();
    constructor(props) {
        super(props);
        this.state = {
            email: "",
            password: "",
            phoneNumber: "",
            otpNumber: "",
            phoneCountry: null,
            isOtp: true,
            otpSent: false,
            emailOtpSent: false,
            is_loading: false,
            countries: {},
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
        this.setState({ errors: [], countries: countries.countries });

        // const script = document.createElement("script");
        // script.src = "/firebase_configs.js";
        // script.async = true;
        // document.body.appendChild(script);
    }

    componentDidMount() {
        let urlCode = new URLSearchParams(window.location.search).get(
            "oobCode"
        );

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

    submitForm = () => {
        if (this.state.is_loading) return;
        event.preventDefault();
        const {
            email,
            password,
            isOtp,
            emailOtpSent,
            otpSent,
            otpNumber,
            // phoneNumber,
            phoneCountry
        } = this.state;

        const phoneNumber = "+" + this.state.phoneNumber;
        const notification = this.notificationSystem.current;

        this.setState({ is_loading: true });

        if (isOtp) {
            if (otpSent) {
                this.setState({
                    is_loading: false
                });

                window.confirmationResult
                    .confirm(otpNumber)
                    .then(result => {
                        const PNF = libphonenumber.PhoneNumberFormat;
                        const phoneUtil = libphonenumber.PhoneNumberUtil.getInstance();
                        // const number = phoneUtil.parse(phoneNumber, phoneCountry);
                        const number = phoneUtil.parse(phoneNumber);
                        const validPhoneNumber = phoneUtil.format(
                            number,
                            PNF.E164
                        );

                        let data = {
                            phone_number: validPhoneNumber
                        };

                        this.props.loginCustomer(data, this.props.history);
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
                    });

                return;
            }

            if (emailOtpSent) {
                let data = {
                    code: this.state.otpNumber,
                    email: this.state.email
                };

                this.props.verifyOtpEmail(data, this.props.history);
                return;
            }
        }

        let data = {
            email: this.state.email,
            password: this.state.password
        };

        this.props.loginCustomer(data, this.props.history);
    };

    submitOtp = () => {
        event.preventDefault();

        const { email, phoneNumber } = this.state;

        const notification = this.notificationSystem.current;

        if (!email && !phoneNumber) {
            notification.addNotification({
                message: "Please either use a Email or Phone Number",
                level: "error"
            });
            return;
        }

        if (phoneNumber) {
            this.initializeFirebase();
            this.sendOtp();
            return;
        }

        if (email) {
            let data = {
                email: this.state.email
            };

            this.setState({ is_loading: true }, () => {
                this.props.sendOtpToEmail(data, this.props.history);
            });

            window.localStorage.setItem("emailForSignIn", this.state.email);
        }
    };

    sendOtp() {
        if (this.state.is_loading) return;

        this.setState({ is_loading: true });

        const notification = this.notificationSystem.current;
        const { phoneCountry } = this.state;
        const phoneNumber = "+" + this.state.phoneNumber;
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
                    console.log(
                        `confirmationResult`,
                        window.confirmationResult
                    );
                    this.setState({
                        otpSent: true,
                        emailOtpSent: false,
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
        const notification = this.notificationSystem.current;
        window.confirmationResult
            .confirm(this.state.otpNumber)
            .then(result => {
                return result;
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
                return false;
            });
    }

    initializeFirebase(recaptchaContainer = true) {
        if (this.state.is_loading) return;
        if (!firebase.apps.length) {
            firebase.initializeApp(firebaseConfig);
            if (recaptchaContainer) {
                window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier(
                    "recaptcha-container",
                    {
                        size: "invisible",
                        callback: function(response) {
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

        if (fetch.status == "error") {
            this.setState({
                errors: fetch.errors,
                is_loading: false
            });

            notification.addNotification({
                message: fetch.message,
                level: "error"
            });
        }

        if (fetch.status == "success") {
            this.setState(
                {
                    is_loading: false
                },
                () => {
                    if (fetch.message == "otp_sent_to_email") {
                        this.setState(
                            {
                                emailOtpSent: true,
                                otpSent: false
                            },
                            () => {
                                notification.addNotification({
                                    message:
                                        "OTP sent to your Email Successfully!",
                                    level: "success"
                                });
                            }
                        );
                    }
                }
            );
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

    render() {
        let {
            isOtp,
            countries,
            errors,
            otpSent,
            is_loading,
            emailOtpSent
        } = this.state;
        return (
            <div className="osahan-signin">
                <NotificationSystem ref={this.notificationSystem} />

                {is_loading && (
                    <div className="osahan-signup">
                        <div
                            className="p-3 py-5 position-fixed w-100 h-100 text-center"
                            style={{
                                zIndex: 1,
                                backgroundColor: "#fff"
                            }}
                        >
                            <img
                                src="/images/loading.gif"
                                class="img-fluid mt-3"
                            />
                            <h2 className="my-0">Loading...</h2>
                        </div>
                    </div>
                )}

                {isOtp ? (
                    <div className="p-3">
                        <h2 className="my-0">Welcome Back</h2>
                        <p className="small">Sign in to Continue.</p>
                        <form action="#">
                            {otpSent && (
                                <div className="form-group row mx-0">
                                    <input
                                        placeholder="Enter OTP"
                                        type="text"
                                        className={`form-control my-2 ${
                                            errors?.email ? "is-invalid" : ""
                                        }`}
                                        id="otpNumber"
                                        aria-describedby="emailHelp"
                                        onChange={this.handleChange}
                                    />
                                    <button
                                        type="submit"
                                        className="btn btn-success btn-lg rounded btn-block"
                                        onClick={() => this.submitForm()}
                                        disabled={is_loading}
                                    >
                                        Sign in
                                    </button>
                                </div>
                            )}

                            {!otpSent && (
                                <>
                                    <div className="form-group row mx-0">
                                        <label
                                            htmlFor="phoneNumber"
                                            className="col-12 pl-0"
                                        >
                                            Phone Number
                                        </label>
                                        {this.state.phoneNumber && (
                                            <label
                                                htmlFor="phoneNumber"
                                                className="col-1 pl-0 form-control text-center"
                                            >
                                                +
                                            </label>
                                        )}

                                        <PhoneInput
                                            country={"us"}
                                            value={this.state.phoneNumber}
                                            enableSearch={true}
                                            onChange={phoneNumber =>
                                                this.setState({ phoneNumber })
                                            }
                                        />

                                        <input
                                            placeholder="Enter Phone Number"
                                            type="tel"
                                            className={`form-control col-11 ${
                                                errors?.phone
                                                    ? "is-invalid"
                                                    : ""
                                            }`}
                                            id="phoneNumber"
                                            aria-describedby="emailHelp"
                                            onChange={this.handleChange}
                                        />
                                        {errors?.phone && (
                                            <div class="invalid-feedback">
                                                {errors?.phone[0]}
                                            </div>
                                        )}
                                    </div>
                                    <p className="text-muted text-center small m-0 py-3">
                                        or
                                    </p>
                                    <div className="form-group">
                                        <label htmlFor="email">Email</label>
                                        <input
                                            placeholder="Enter Email"
                                            type="email"
                                            className="form-control"
                                            id="email"
                                            aria-describedby="emailHelp"
                                            onChange={this.handleChange}
                                            className={`form-control my-2 ${
                                                errors?.email
                                                    ? "is-invalid"
                                                    : ""
                                            }`}
                                        />
                                        {errors?.email && (
                                            <div class="invalid-feedback">
                                                {errors?.email[0]}
                                            </div>
                                        )}
                                    </div>
                                    <button
                                        type="submit"
                                        className="btn btn-primary btn-lg rounded btn-block"
                                        onClick={() => this.submitOtp()}
                                        disabled={is_loading}
                                    >
                                        Send OTP
                                    </button>
                                </>
                            )}
                        </form>
                        <button
                            className="btn btn-secondary btn-block rounded btn-lg btn-apple mt-2"
                            onClick={() => {
                                this.setState({ isOtp: !this.state.isOtp });
                            }}
                        >
                            Sign in using Password
                        </button>
                        <a
                            // href={`${ROUTE.ACCOUNT.LOGINFACEBOOK.PAGES.VIEW.PATH}`}
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
                                        <stop offset="0" stop-color="#f4e9c3" />
                                        <stop
                                            offset=".219"
                                            stop-color="#f8eecd"
                                        />
                                        <stop
                                            offset=".644"
                                            stop-color="#fdf4dc"
                                        />
                                        <stop offset="1" stop-color="#fff6e1" />
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
                                        <stop offset="0" stop-color="#155cde" />
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
                                        <stop offset="1" stop-color="#2eb5f0" />
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
                            <div className="text">Facebook</div>
                        </a>
                        <a
                            // href={`${ROUTE.ACCOUNT.LOGINGOOGLE.PAGES.VIEW.PATH}`}
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
                                        <stop offset="0" stop-color="#f4e9c3" />
                                        <stop
                                            offset=".219"
                                            stop-color="#f8eecd"
                                        />
                                        <stop
                                            offset=".644"
                                            stop-color="#fdf4dc"
                                        />
                                        <stop offset="1" stop-color="#fff6e1" />
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
                                        <stop offset="0" stop-color="#ff5840" />
                                        <stop
                                            offset=".007"
                                            stop-color="#ff5840"
                                        />
                                        <stop
                                            offset=".989"
                                            stop-color="#fa528c"
                                        />
                                        <stop offset="1" stop-color="#fa528c" />
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
                                        <stop offset="0" stop-color="#feaa53" />
                                        <stop
                                            offset=".612"
                                            stop-color="#ffcd49"
                                        />
                                        <stop offset="1" stop-color="#ffde44" />
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
                                        <stop offset="0" stop-color="#42d778" />
                                        <stop
                                            offset=".428"
                                            stop-color="#3dca76"
                                        />
                                        <stop offset="1" stop-color="#34b171" />
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
                                        <stop offset="0" stop-color="#155cde" />
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
                                        <stop offset="1" stop-color="#2eb5f0" />
                                    </linearGradient>
                                    <path
                                        fill="url(#95yY7w43Oj6n2vH63j6HJe)"
                                        d="M59,31.97c0.01,7.73-3.26,14.58-8.55,19.31l-7.62-6.72c2.1-1.61,3.77-3.71,4.84-6.15	c0.29-0.66-0.2-1.41-0.92-1.41H37c-2.21,0-4-1.79-4-4v-2c0-2.21,1.79-4,4-4h17C56.75,27,59,29.22,59,31.97z"
                                    />
                                </svg>
                            </div>
                            <div className="text">Google</div>
                        </a>
                        <p className="text-muted text-center small m-0 py-3">
                            or
                        </p>
                        <a
                            href={`${ROUTE.ACCOUNT.REGISTER.PAGES.VIEW.PATH}`}
                            className="btn btn-dark btn-block rounded btn-lg btn-apple"
                        >
                            Sign up
                        </a>
                    </div>
                ) : (
                    <div className="p-3">
                        <h2 className="my-0">Welcome Back</h2>
                        <p className="small">Sign in to Continue.</p>
                        <form action="#">
                            <div className="form-group">
                                <label htmlFor="email">Email</label>
                                <input
                                    placeholder="Enter Email"
                                    type="email"
                                    id="email"
                                    aria-describedby="emailHelp"
                                    onChange={this.handleChange}
                                    className={`form-control ${
                                        errors?.email ? "is-invalid" : ""
                                    }`}
                                />
                                {errors?.email && (
                                    <div class="invalid-feedback">
                                        {errors?.email[0]}
                                    </div>
                                )}
                            </div>
                            <div className="form-group">
                                <label htmlFor="password">Password</label>
                                <input
                                    placeholder="Enter Password"
                                    type="password"
                                    id="password"
                                    onChange={this.handleChange}
                                    className={`form-control ${
                                        errors?.password ? "is-invalid" : ""
                                    }`}
                                />
                                {errors?.password && (
                                    <div class="invalid-feedback">
                                        {errors?.password[0]}
                                    </div>
                                )}
                            </div>
                            <button
                                type="submit"
                                className="btn btn-success btn-lg rounded btn-block"
                                onClick={() => this.submitForm()}
                            >
                                Sign in
                            </button>
                        </form>
                        <button
                            onClick={() => {
                                this.setState({ isOtp: !this.state.isOtp });
                            }}
                            className="btn btn-lg rounded mt-2 mr-2 social-signin-btn"
                        >
                            <div className="icon">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 100 100"
                                >
                                    <path
                                        fill="#c7ede6"
                                        d="M87.215,56.71C88.35,54.555,89,52.105,89,49.5c0-6.621-4.159-12.257-10.001-14.478 C78.999,35.015,79,35.008,79,35c0-11.598-9.402-21-21-21c-9.784,0-17.981,6.701-20.313,15.757C36.211,29.272,34.638,29,33,29 c-7.692,0-14.023,5.793-14.89,13.252C12.906,43.353,9,47.969,9,53.5C9,59.851,14.149,65,20.5,65c0.177,0,0.352-0.012,0.526-0.022 C21.022,65.153,21,65.324,21,65.5C21,76.822,30.178,86,41.5,86c6.437,0,12.175-2.972,15.934-7.614C59.612,80.611,62.64,82,66,82 c4.65,0,8.674-2.65,10.666-6.518C77.718,75.817,78.837,76,80,76c6.075,0,11-4.925,11-11C91,61.689,89.53,58.727,87.215,56.71z"
                                    />
                                    <path
                                        fill="#fff"
                                        d="M15.405,51H5.5C5.224,51,5,50.776,5,50.5S5.224,50,5.5,50h9.905c0.276,0,0.5,0.224,0.5,0.5 S15.682,51,15.405,51z"
                                    />
                                    <path
                                        fill="#fff"
                                        d="M18.5,51h-1c-0.276,0-0.5-0.224-0.5-0.5s0.224-0.5,0.5-0.5h1c0.276,0,0.5,0.224,0.5,0.5 S18.777,51,18.5,51z"
                                    />
                                    <path
                                        fill="#fff"
                                        d="M23.491,53H14.5c-0.276,0-0.5-0.224-0.5-0.5s0.224-0.5,0.5-0.5h8.991c0.276,0,0.5,0.224,0.5,0.5 S23.767,53,23.491,53z"
                                    />
                                    <path
                                        fill="#fff"
                                        d="M12.5,53h-1c-0.276,0-0.5-0.224-0.5-0.5s0.224-0.5,0.5-0.5h1c0.276,0,0.5,0.224,0.5,0.5 S12.777,53,12.5,53z"
                                    />
                                    <path
                                        fill="#fff"
                                        d="M9.5,53h-2C7.224,53,7,52.776,7,52.5S7.224,52,7.5,52h2c0.276,0,0.5,0.224,0.5,0.5S9.777,53,9.5,53 z"
                                    />
                                    <path
                                        fill="#fff"
                                        d="M15.5,55h-2c-0.276,0-0.5-0.224-0.5-0.5s0.224-0.5,0.5-0.5h2c0.276,0,0.5,0.224,0.5,0.5 S15.776,55,15.5,55z"
                                    />
                                    <path
                                        fill="#fff"
                                        d="M18.5,46c-0.177,0-0.823,0-1,0c-0.276,0-0.5,0.224-0.5,0.5c0,0.276,0.224,0.5,0.5,0.5 c0.177,0,0.823,0,1,0c0.276,0,0.5-0.224,0.5-0.5C19,46.224,18.776,46,18.5,46z"
                                    />
                                    <path
                                        fill="#fff"
                                        d="M18.5,48c-0.177,0-4.823,0-5,0c-0.276,0-0.5,0.224-0.5,0.5c0,0.276,0.224,0.5,0.5,0.5 c0.177,0,4.823,0,5,0c0.276,0,0.5-0.224,0.5-0.5C19,48.224,18.776,48,18.5,48z"
                                    />
                                    <path
                                        fill="#fff"
                                        d="M23.5,50c-0.177,0-2.823,0-3,0c-0.276,0-0.5,0.224-0.5,0.5c0,0.276,0.224,0.5,0.5,0.5 c0.177,0,2.823,0,3,0c0.276,0,0.5-0.224,0.5-0.5C24,50.224,23.776,50,23.5,50z"
                                    />
                                    <path
                                        fill="#fff"
                                        d="M71.5,26h-10c-0.276,0-0.5-0.224-0.5-0.5s0.224-0.5,0.5-0.5h10c0.276,0,0.5,0.224,0.5,0.5 S71.776,26,71.5,26z"
                                    />
                                    <path
                                        fill="#fff"
                                        d="M75.5,26h-2c-0.276,0-0.5-0.224-0.5-0.5s0.224-0.5,0.5-0.5h2c0.276,0,0.5,0.224,0.5,0.5 S75.776,26,75.5,26z"
                                    />
                                    <path
                                        fill="#fff"
                                        d="M80.5,28h-10c-0.276,0-0.5-0.224-0.5-0.5s0.224-0.5,0.5-0.5h10c0.276,0,0.5,0.224,0.5,0.5 S80.777,28,80.5,28z"
                                    />
                                    <path
                                        fill="#fff"
                                        d="M68.5,28h-1c-0.276,0-0.5-0.224-0.5-0.5s0.224-0.5,0.5-0.5h1c0.276,0,0.5,0.224,0.5,0.5 S68.776,28,68.5,28z"
                                    />
                                    <path
                                        fill="#fff"
                                        d="M65.375,28H63.5c-0.276,0-0.5-0.224-0.5-0.5s0.224-0.5,0.5-0.5h1.875c0.276,0,0.5,0.224,0.5,0.5 S65.651,28,65.375,28z"
                                    />
                                    <path
                                        fill="#fff"
                                        d="M74.5,24h-5c-0.276,0-0.5-0.224-0.5-0.5s0.224-0.5,0.5-0.5h5c0.276,0,0.5,0.224,0.5,0.5 S74.777,24,74.5,24z"
                                    />
                                    <path
                                        fill="#fff"
                                        d="M71.5,30h-2c-0.276,0-0.5-0.224-0.5-0.5s0.224-0.5,0.5-0.5h2c0.276,0,0.5,0.224,0.5,0.5 S71.776,30,71.5,30z"
                                    />
                                    <g>
                                        <path
                                            fill="#fdfcef"
                                            d="M30.815,77.5c0,0,11.691,0,11.762,0c2.7,0,4.888-2.189,4.888-4.889 c0-2.355-1.666-4.321-3.884-4.784c0.026-0.206,0.043-0.415,0.043-0.628c0-2.796-2.267-5.063-5.063-5.063 c-1.651,0-3.113,0.794-4.037,2.017c-0.236-3.113-3.017-5.514-6.27-5.116c-2.379,0.291-4.346,2.13-4.784,4.486 c-0.14,0.756-0.126,1.489,0.014,2.177c-0.638-0.687-1.546-1.119-2.557-1.119c-1.85,0-3.361,1.441-3.48,3.261 c-0.84-0.186-1.754-0.174-2.717,0.188c-1.84,0.691-3.15,2.423-3.227,4.387c-0.109,2.789,2.12,5.085,4.885,5.085 c0.21,0,0.948,0,1.118,0h10.151"
                                        />
                                        <path
                                            fill="#472b29"
                                            d="M42.576,78H30.815c-0.276,0-0.5-0.224-0.5-0.5s0.224-0.5,0.5-0.5h11.762 c2.42,0,4.389-1.969,4.389-4.389c0-2.067-1.466-3.873-3.486-4.295c-0.254-0.053-0.426-0.292-0.394-0.55 c0.022-0.186,0.039-0.375,0.039-0.567c0-2.516-2.047-4.563-4.563-4.563c-1.438,0-2.765,0.663-3.638,1.818 c-0.125,0.166-0.342,0.237-0.539,0.179c-0.2-0.059-0.342-0.235-0.358-0.442c-0.104-1.377-0.778-2.671-1.85-3.549 c-1.084-0.887-2.452-1.279-3.861-1.109c-2.165,0.265-3.955,1.943-4.353,4.081c-0.124,0.667-0.12,1.335,0.013,1.986 c0.044,0.22-0.063,0.442-0.262,0.544c-0.197,0.102-0.442,0.061-0.595-0.104c-0.574-0.619-1.353-0.959-2.19-0.959 c-1.568,0-2.878,1.227-2.98,2.793c-0.01,0.146-0.082,0.28-0.199,0.367c-0.116,0.087-0.268,0.119-0.407,0.088 c-0.844-0.186-1.64-0.131-2.434,0.167c-1.669,0.626-2.836,2.209-2.903,3.938c-0.047,1.207,0.387,2.35,1.222,3.218 C14.061,76.522,15.185,77,16.389,77h11.27c0.276,0,0.5,0.224,0.5,0.5s-0.224,0.5-0.5,0.5h-11.27c-1.479,0-2.858-0.587-3.884-1.653 s-1.559-2.469-1.501-3.951c0.084-2.126,1.511-4.069,3.552-4.836c0.8-0.3,1.628-0.4,2.468-0.298 c0.377-1.823,1.996-3.182,3.904-3.182c0.685,0,1.354,0.179,1.944,0.51c-0.001-0.386,0.035-0.773,0.107-1.159 c0.476-2.562,2.619-4.573,5.214-4.891c1.688-0.206,3.321,0.267,4.616,1.328c1.004,0.823,1.716,1.951,2.038,3.193 c1.012-0.916,2.318-1.425,3.713-1.425c3.067,0,5.563,2.496,5.563,5.563c0,0.083-0.002,0.166-0.007,0.249 c2.254,0.672,3.848,2.777,3.848,5.164C47.965,75.583,45.548,78,42.576,78z"
                                        />
                                        <path
                                            fill="#fdfcef"
                                            d="M27.982,66.731c-1.808-0.119-3.365,1.13-3.476,2.789c-0.014,0.206-0.005,0.409,0.025,0.606 c-0.349-0.394-0.865-0.661-1.458-0.7c-1.085-0.071-2.022,0.645-2.158,1.62c-0.197-0.054-0.403-0.09-0.616-0.104 c-1.582-0.104-2.944,0.989-3.042,2.441"
                                        />
                                        <path
                                            fill="#472b29"
                                            d="M17.257,73.632c-0.006,0-0.011,0-0.017,0c-0.138-0.009-0.242-0.128-0.232-0.266 c0.106-1.586,1.563-2.783,3.308-2.674c0.135,0.009,0.271,0.027,0.408,0.053c0.272-0.967,1.255-1.639,2.365-1.568 c0.426,0.028,0.824,0.161,1.163,0.382c0.001-0.019,0.002-0.037,0.004-0.055c0.12-1.794,1.8-3.146,3.742-3.022 c0.138,0.009,0.242,0.128,0.232,0.266c-0.008,0.137-0.106,0.245-0.266,0.233c-1.658-0.104-3.108,1.038-3.211,2.557 c-0.012,0.186-0.004,0.372,0.023,0.551c0.017,0.11-0.041,0.217-0.141,0.265c-0.102,0.046-0.221,0.022-0.294-0.061 c-0.317-0.358-0.786-0.583-1.287-0.616c-0.959-0.064-1.774,0.555-1.893,1.405c-0.011,0.071-0.051,0.134-0.11,0.174 s-0.135,0.052-0.203,0.033c-0.189-0.051-0.38-0.084-0.567-0.096c-1.452-0.099-2.687,0.896-2.776,2.208 C17.497,73.531,17.388,73.632,17.257,73.632z"
                                        />
                                        <g>
                                            <path
                                                fill="#fdfcef"
                                                d="M44.556,68.4c-1.699-0.801-3.664-0.234-4.389,1.267c-0.09,0.186-0.157,0.379-0.201,0.574"
                                            />
                                            <path
                                                fill="#472b29"
                                                d="M39.966,70.49c-0.019,0-0.037-0.002-0.057-0.006c-0.134-0.031-0.218-0.166-0.187-0.3 c0.05-0.216,0.123-0.427,0.219-0.625c0.783-1.622,2.9-2.243,4.721-1.384c0.125,0.059,0.179,0.208,0.12,0.333 c-0.06,0.125-0.21,0.177-0.333,0.12c-1.575-0.743-3.394-0.226-4.057,1.149c-0.08,0.165-0.142,0.34-0.184,0.521 C40.183,70.412,40.08,70.49,39.966,70.49z"
                                            />
                                        </g>
                                    </g>
                                    <path
                                        fill="#1fc648"
                                        d="M28.989,65.011V36.989c0-3.866,3.134-7,7-7h28.023c3.866,0,7,3.134,7,7v28.023c0,3.866-3.134,7-7,7H35.989C32.123,72.011,28.989,68.877,28.989,65.011z"
                                    />
                                    <path
                                        fill="#472b29"
                                        d="M64,30.4c3.639,0,6.6,2.961,6.6,6.6v28c0,3.639-2.961,6.6-6.6,6.6H36c-3.639,0-6.6-2.961-6.6-6.6V37c0-3.639,2.961-6.6,6.6-6.6H64 M64,29H36c-4.418,0-8,3.582-8,8v28c0,4.418,3.582,8,8,8h28c4.418,0,8-3.582,8-8V37C72,32.582,68.418,29,64,29L64,29z"
                                    />
                                    <path
                                        fill="#472b29"
                                        d="M64,69.375H36c-2.413,0-4.375-1.962-4.375-4.375V37c0-2.413,1.962-4.375,4.375-4.375h28c2.413,0,4.375,1.962,4.375,4.375v3.625C68.375,40.832,68.207,41,68,41s-0.375-0.168-0.375-0.375V37c0-1.999-1.626-3.625-3.625-3.625H36c-1.999,0-3.625,1.626-3.625,3.625v28c0,1.999,1.626,3.625,3.625,3.625h28c1.999,0,3.625-1.626,3.625-3.625V48.25c0-0.207,0.168-0.375,0.375-0.375s0.375,0.168,0.375,0.375V65C68.375,67.413,66.413,69.375,64,69.375z"
                                    />
                                    <path
                                        fill="#472b29"
                                        d="M68,46c-0.207,0-0.375-0.168-0.375-0.375v-3.25C67.625,42.168,67.793,42,68,42s0.375,0.168,0.375,0.375v3.25C68.375,45.832,68.207,46,68,46z"
                                    />
                                    <g>
                                        <path
                                            fill="#fdfcef"
                                            d="M80.248,76.5c1.883,0,3.517,0,3.54,0c2.11,0,3.821-1.674,3.821-3.739 c0-1.802-1.302-3.305-3.035-3.66c0.02-0.158,0.034-0.317,0.034-0.48c0-2.139-1.772-3.873-3.957-3.873 c-1.29,0-2.433,0.607-3.155,1.543c-0.185-2.381-2.358-4.218-4.9-3.913c-1.859,0.223-3.397,1.629-3.739,3.431 c-0.11,0.578-0.098,1.139,0.011,1.665c-0.498-0.525-1.208-0.856-1.998-0.856c-1.446,0-2.627,1.102-2.72,2.494 c-0.657-0.142-1.371-0.133-2.123,0.143c-1.438,0.528-2.462,1.853-2.522,3.356c-0.085,2.133,1.657,3.889,3.818,3.889 c0.164,0,0.741,0,0.874,0h7.934 M73.77,76.5h0.36"
                                        />
                                        <path
                                            fill="#472b29"
                                            d="M83.787,77h-3.54c-0.276,0-0.5-0.224-0.5-0.5s0.224-0.5,0.5-0.5h3.54 c1.831,0,3.321-1.453,3.321-3.239c0-1.524-1.108-2.857-2.637-3.17c-0.257-0.052-0.429-0.294-0.396-0.554 c0.018-0.137,0.03-0.275,0.03-0.416c0-1.86-1.551-3.373-3.457-3.373c-1.093,0-2.099,0.491-2.76,1.348 c-0.125,0.165-0.343,0.232-0.538,0.174c-0.198-0.059-0.34-0.234-0.355-0.44c-0.079-1.019-0.565-1.943-1.37-2.603 c-0.828-0.68-1.886-0.984-2.973-0.853c-1.646,0.197-3.006,1.442-3.307,3.028c-0.094,0.494-0.091,0.988,0.009,1.471 c0.046,0.219-0.06,0.441-0.258,0.544c-0.196,0.104-0.439,0.064-0.595-0.099c-0.428-0.451-1.008-0.7-1.635-0.7 c-1.17,0-2.146,0.891-2.221,2.027c-0.01,0.145-0.082,0.279-0.198,0.366c-0.115,0.088-0.263,0.12-0.406,0.089 c-0.639-0.139-1.241-0.097-1.847,0.124c-1.262,0.464-2.144,1.632-2.193,2.906c-0.035,0.875,0.282,1.708,0.895,2.345 C61.533,75.636,62.393,76,63.321,76h8.808c0.276,0,0.5,0.224,0.5,0.5s-0.224,0.5-0.5,0.5h-8.808 c-1.185,0-2.331-0.485-3.144-1.332c-0.803-0.835-1.219-1.928-1.174-3.078c0.066-1.674,1.212-3.203,2.849-3.805 c0.612-0.225,1.245-0.307,1.881-0.245c0.345-1.396,1.629-2.424,3.136-2.424c0.493,0,0.977,0.113,1.413,0.323 c0.01-0.242,0.037-0.484,0.083-0.726c0.381-2.009,2.096-3.585,4.17-3.834c1.364-0.16,2.686,0.218,3.727,1.073 c0.747,0.613,1.278,1.409,1.546,2.301c0.791-0.648,1.785-1.006,2.843-1.006c2.458,0,4.457,1.961,4.457,4.373 c0,0.034-0.001,0.068-0.002,0.103c1.765,0.555,3.004,2.188,3.004,4.038C88.109,75.098,86.17,77,83.787,77z M74.129,77H73.77 c-0.276,0-0.5-0.224-0.5-0.5s0.224-0.5,0.5-0.5h0.359c0.276,0,0.5,0.224,0.5,0.5S74.406,77,74.129,77z"
                                        />
                                        <path
                                            fill="#472b29"
                                            d="M82.08,71.197c-0.018,0-0.036-0.002-0.055-0.006c-0.135-0.03-0.22-0.164-0.189-0.299 c0.038-0.167,0.095-0.329,0.17-0.479c0.604-1.223,2.273-1.673,3.721-1.006c0.126,0.058,0.181,0.207,0.122,0.332 c-0.057,0.125-0.209,0.179-0.331,0.122c-1.204-0.556-2.579-0.21-3.063,0.774c-0.058,0.115-0.102,0.238-0.13,0.367 C82.298,71.118,82.195,71.197,82.08,71.197z"
                                        />
                                        <g>
                                            <path
                                                fill="#472b29"
                                                d="M76.921,77h-1.107c-0.276,0-0.5-0.224-0.5-0.5s0.224-0.5,0.5-0.5h1.107c0.276,0,0.5,0.224,0.5,0.5 S77.198,77,76.921,77z"
                                            />
                                        </g>
                                    </g>
                                    <g>
                                        <path
                                            fill="#472b29"
                                            d="M58.013,72.281c-0.027,0-0.056-0.007-0.08-0.023c-0.07-0.044-0.091-0.137-0.047-0.207l1.962-3.106 c0.044-0.07,0.138-0.091,0.207-0.047c0.07,0.044,0.091,0.137,0.047,0.207l-1.962,3.106C58.112,72.257,58.063,72.281,58.013,72.281z"
                                        />
                                        <path
                                            fill="#472b29"
                                            d="M57.013,72.281c-0.027,0-0.056-0.007-0.08-0.023c-0.07-0.044-0.091-0.137-0.047-0.207l1.962-3.106 c0.044-0.07,0.139-0.091,0.207-0.047c0.07,0.044,0.091,0.137,0.047,0.207l-1.962,3.106C57.112,72.257,57.063,72.281,57.013,72.281z"
                                        />
                                        <path
                                            fill="#472b29"
                                            d="M56.013,72.281c-0.027,0-0.056-0.007-0.08-0.023c-0.07-0.044-0.091-0.137-0.047-0.207l1.962-3.106 c0.044-0.07,0.139-0.091,0.207-0.047c0.07,0.044,0.091,0.137,0.047,0.207l-1.962,3.106C56.112,72.257,56.063,72.281,56.013,72.281z"
                                        />
                                        <path
                                            fill="#472b29"
                                            d="M55.013,72.281c-0.027,0-0.056-0.007-0.08-0.023c-0.07-0.044-0.091-0.137-0.047-0.207l1.962-3.106 c0.044-0.07,0.139-0.091,0.207-0.047c0.07,0.044,0.091,0.137,0.047,0.207l-1.962,3.106C55.112,72.257,55.063,72.281,55.013,72.281z"
                                        />
                                        <path
                                            fill="#472b29"
                                            d="M54.013,72.281c-0.027,0-0.056-0.007-0.08-0.023c-0.07-0.044-0.091-0.137-0.047-0.207l1.962-3.106 c0.044-0.07,0.139-0.091,0.207-0.047c0.07,0.044,0.091,0.137,0.047,0.207l-1.962,3.106C54.112,72.257,54.063,72.281,54.013,72.281z"
                                        />
                                        <path
                                            fill="#472b29"
                                            d="M53.013,72.281c-0.027,0-0.056-0.007-0.08-0.023c-0.07-0.044-0.091-0.137-0.047-0.207l1.962-3.106 c0.044-0.07,0.138-0.091,0.207-0.047c0.07,0.044,0.091,0.137,0.047,0.207l-1.962,3.106C53.112,72.257,53.063,72.281,53.013,72.281z"
                                        />
                                        <path
                                            fill="#472b29"
                                            d="M52.013,72.281c-0.027,0-0.056-0.007-0.08-0.023c-0.07-0.044-0.091-0.137-0.047-0.207l1.297-2.053 c0.044-0.07,0.138-0.091,0.207-0.047c0.07,0.044,0.091,0.137,0.047,0.207l-1.297,2.053C52.112,72.257,52.063,72.281,52.013,72.281z"
                                        />
                                        <path
                                            fill="#472b29"
                                            d="M51.013,72.281c-0.027,0-0.056-0.007-0.08-0.023c-0.07-0.044-0.091-0.137-0.047-0.207l0.813-1.289 c0.043-0.071,0.139-0.09,0.207-0.047c0.07,0.044,0.091,0.137,0.047,0.207l-0.813,1.289C51.112,72.257,51.063,72.281,51.013,72.281z"
                                        />
                                    </g>
                                    <path
                                        fill="#fff"
                                        d="M42.36,37.875c0.655,0.986,1.998,3.088,2.881,4.476c0.521,0.819,0.536,1.864,0.041,2.7l-0.2,0.338c-0.542,0.914-0.469,2.071,0.186,2.906c0.896,1.142,2.226,2.765,3.492,4.03c1.287,1.286,2.981,2.72,4.158,3.682c0.827,0.676,1.98,0.758,2.891,0.204l0.37-0.224c0.798-0.484,1.793-0.487,2.592-0.006c1.365,0.821,3.449,2.085,4.438,2.743c0.508,0.338,0.709,0.977,0.496,1.548c-0.637,1.714-1.678,3.561-4.974,3.561c-3.794,0-9.043-4.106-13.205-8.266l0,0c-0.001-0.001-0.003-0.003-0.005-0.005c-0.001-0.001-0.003-0.003-0.005-0.005l0,0c-4.16-4.162-8.281-9.198-8.281-13.085c0-3.536,1.862-4.457,3.577-5.095C41.383,37.166,42.022,37.367,42.36,37.875z"
                                    />
                                    <path
                                        fill="#472b29"
                                        d="M49,59.024c-0.057,0-0.114-0.02-0.161-0.059c-0.662-0.559-1.339-1.161-2.01-1.789c-0.101-0.094-0.106-0.252-0.012-0.354c0.094-0.102,0.254-0.105,0.354-0.012c0.665,0.622,1.335,1.219,1.99,1.772c0.105,0.089,0.119,0.247,0.03,0.352C49.142,58.994,49.071,59.024,49,59.024z"
                                    />
                                    <path
                                        fill="#472b29"
                                        d="M58.73,64.083c-2.088,0-4.739-1.184-7.88-3.518c-0.11-0.083-0.134-0.239-0.051-0.35c0.083-0.112,0.239-0.133,0.35-0.052c3.052,2.269,5.603,3.419,7.581,3.419c3.056,0,4.084-1.633,4.739-3.398c0.174-0.466,0.01-0.982-0.399-1.253c-0.959-0.638-2.938-1.841-4.429-2.737c-0.721-0.434-1.614-0.431-2.333,0.006l-0.37,0.224c-0.997,0.604-2.275,0.515-3.179-0.225c-1.192-0.975-2.885-2.408-4.177-3.699c-1.277-1.278-2.614-2.909-3.511-4.052c-0.714-0.909-0.797-2.19-0.205-3.188l0.2-0.337c0.45-0.76,0.436-1.694-0.037-2.438c-0.961-1.511-2.241-3.513-2.879-4.472c-0.271-0.409-0.786-0.575-1.253-0.4c-1.727,0.642-3.413,1.546-3.413,4.86c0,3.117,2.762,7.46,8.208,12.908c0.098,0.098,0.098,0.256,0,0.354s-0.256,0.098-0.354,0c-5.544-5.545-8.354-10.007-8.354-13.262c0-3.634,1.93-4.656,3.739-5.329c0.683-0.253,1.442-0.011,1.843,0.592c0.641,0.962,1.922,2.967,2.885,4.48c0.574,0.904,0.592,2.039,0.045,2.961l-0.2,0.337c-0.486,0.822-0.419,1.876,0.168,2.625c0.889,1.132,2.212,2.746,3.472,4.007c1.277,1.276,2.956,2.698,4.14,3.666c0.739,0.606,1.785,0.682,2.603,0.184l0.37-0.224c0.879-0.533,1.97-0.536,2.851-0.007c1.495,0.899,3.48,2.107,4.448,2.75c0.603,0.4,0.846,1.159,0.591,1.844C63.255,62.201,62.101,64.083,58.73,64.083z"
                                    />
                                    <path
                                        fill="#472b29"
                                        d="M45.52,55.81c-0.069,0-0.13-0.03-0.18-0.08c-0.05-0.04-0.07-0.11-0.07-0.17c0-0.07,0.021-0.13,0.07-0.18c0.07-0.07,0.18-0.09,0.27-0.05c0.03,0.01,0.061,0.03,0.08,0.05c0.051,0.05,0.08,0.11,0.08,0.18c0,0.06-0.029,0.13-0.08,0.17C45.649,55.78,45.58,55.81,45.52,55.81z"
                                    />
                                    <path
                                        fill="#472b29"
                                        d="M45.518,55.812c-0.08,0-0.157-0.038-0.205-0.11l0.003-0.001c-0.06-0.113-0.035-0.265,0.072-0.337c0.116-0.077,0.26-0.059,0.337,0.056c0.076,0.115,0.048,0.272-0.067,0.349C45.614,55.797,45.565,55.812,45.518,55.812z"
                                    />
                                    <path
                                        fill="#472b29"
                                        d="M45.525,55.817c-0.062,0-0.123-0.023-0.17-0.066c-0.083-0.073-0.11-0.196-0.059-0.3c0.063-0.124,0.215-0.174,0.335-0.112c0.039,0.02,0.077,0.053,0.102,0.089c0.076,0.115,0.046,0.27-0.069,0.347C45.621,55.804,45.573,55.817,45.525,55.817z"
                                    />
                                    <path
                                        fill="#472b29"
                                        d="M45.52,55.82c-0.06,0-0.13-0.03-0.17-0.08c-0.05-0.04-0.08-0.11-0.08-0.17c0-0.07,0.03-0.13,0.08-0.18c0.021-0.02,0.05-0.04,0.08-0.05c0.09-0.04,0.2-0.02,0.271,0.05c0.05,0.05,0.08,0.11,0.08,0.18c0,0.06-0.03,0.13-0.08,0.17C45.66,55.79,45.59,55.82,45.52,55.82z"
                                    />
                                </svg>
                            </div>
                            <div className="text">Phone (OTP)</div>
                        </button>
                        <a
                            href={`${ROUTE.ACCOUNT.LOGINFACEBOOK.PAGES.VIEW.PATH}`}
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
                                        <stop offset="0" stop-color="#f4e9c3" />
                                        <stop
                                            offset=".219"
                                            stop-color="#f8eecd"
                                        />
                                        <stop
                                            offset=".644"
                                            stop-color="#fdf4dc"
                                        />
                                        <stop offset="1" stop-color="#fff6e1" />
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
                                        <stop offset="0" stop-color="#155cde" />
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
                                        <stop offset="1" stop-color="#2eb5f0" />
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
                            <div className="text">Facebook</div>
                        </a>
                        <a
                            href={`${ROUTE.ACCOUNT.LOGINGOOGLE.PAGES.VIEW.PATH}`}
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
                                        <stop offset="0" stop-color="#f4e9c3" />
                                        <stop
                                            offset=".219"
                                            stop-color="#f8eecd"
                                        />
                                        <stop
                                            offset=".644"
                                            stop-color="#fdf4dc"
                                        />
                                        <stop offset="1" stop-color="#fff6e1" />
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
                                        <stop offset="0" stop-color="#ff5840" />
                                        <stop
                                            offset=".007"
                                            stop-color="#ff5840"
                                        />
                                        <stop
                                            offset=".989"
                                            stop-color="#fa528c"
                                        />
                                        <stop offset="1" stop-color="#fa528c" />
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
                                        <stop offset="0" stop-color="#feaa53" />
                                        <stop
                                            offset=".612"
                                            stop-color="#ffcd49"
                                        />
                                        <stop offset="1" stop-color="#ffde44" />
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
                                        <stop offset="0" stop-color="#42d778" />
                                        <stop
                                            offset=".428"
                                            stop-color="#3dca76"
                                        />
                                        <stop offset="1" stop-color="#34b171" />
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
                                        <stop offset="0" stop-color="#155cde" />
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
                                        <stop offset="1" stop-color="#2eb5f0" />
                                    </linearGradient>
                                    <path
                                        fill="url(#95yY7w43Oj6n2vH63j6HJe)"
                                        d="M59,31.97c0.01,7.73-3.26,14.58-8.55,19.31l-7.62-6.72c2.1-1.61,3.77-3.71,4.84-6.15	c0.29-0.66-0.2-1.41-0.92-1.41H37c-2.21,0-4-1.79-4-4v-2c0-2.21,1.79-4,4-4h17C56.75,27,59,29.22,59,31.97z"
                                    />
                                </svg>
                            </div>
                            <div className="text">Google</div>
                        </a>
                        <p className="text-muted text-center small m-0 py-3">
                            or
                        </p>
                        <a
                            href={`${ROUTE.ACCOUNT.REGISTER.PAGES.VIEW.PATH}`}
                            className="btn btn-dark btn-block rounded btn-lg btn-apple"
                        >
                            Sign up
                        </a>
                        {/* 
                        <button
                            onClick={() => {
                                this.setState({ isOtp: !this.state.isOtp });
                            }}
                            className="btn btn-secondary btn-block rounded btn-lg btn-apple mt-2"
                        >
                            Sign in using Phone
                        </button> 
                        */}
                    </div>
                )}
                <div id="recaptcha-container"></div>
            </div>
        );
    }
}

const mapSateToProps = state => ({
    fetch: state.fetch
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
            dispatch(loginCustomerWithCallback(creds, props))
    };
};

export default connect(mapSateToProps, mapDispatchToProps)(Login);
