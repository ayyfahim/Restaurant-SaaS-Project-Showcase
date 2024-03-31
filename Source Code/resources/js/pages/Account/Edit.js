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

let storeId = null;

class Edit extends React.Component {
    notificationSystem = React.createRef();
    constructor(props) {
        super(props);
        this.state = {
            name: "",
            firstName: "",
            lastName: "",
            phone: null,
            email: null,
            is_loading: false,
            showEditBox: false,
            editFirstName: false,
            editLastName: false,
            editEmail: false,
            editPhone: false,
            errors: [],
            fetch_message: null
        };
        this.onChange = this.onChange.bind(this);
    }

    componentWillMount() {
        this.props.refreshUser();
        this.getAuthUser();
    }

    getAuthUser() {
        const user = this.props.auth;
        let data = {
            firstName: user.userFirstName,
            lastName: user.userLastName,
            phone: user.userPhone,
            email: user.userEmail
            // userId: user.userId
        };
        this.setState(data);
    }

    onChange(e) {
        this.setState({ [e.target.name]: e.target.value });
    }

    submitForm = () => {
        if (this.state.is_loading) return;

        const notification = this.notificationSystem.current;

        if (!this.props.auth.isLogin) {
            notification.addNotification({
                message: "Please login first.",
                level: "error"
            });
            return;
        }

        this.setState({ is_loading: true });

        event.preventDefault();

        const {
            name,
            firstName,
            lastName,
            phone,
            email,
            editName,
            editEmail,
            editPhone
        } = this.state;

        if (editName) {
            let data = {
                first_name: firstName ?? null,
                last_name: lastName ?? null
            };

            this.props.updateCustomer(data);

            this.setState({ is_loading: false });

            return;
        }

        if (editEmail) {
            let data = {
                // name: name,
                // phone: phone,
                email: email
            };

            this.props.updateCustomer(data);

            this.setState({ is_loading: false });

            return;
        }

        if (editPhone) {
            let data = {
                // name: name,
                phone: phone
                // email: email
            };

            this.props.updateCustomer(data);

            this.setState({ is_loading: false });

            return;
        }
    };

    toggleEditBox = field => {
        if (field == "firstName") {
            this.setState(prevState => ({
                showEditBox: !prevState.showEditBox,
                editFirstName: true,
                editName: true,
                editLastName: false,
                editEmail: false,
                editPhone: false
            }));

            return;
        }

        if (field == "lastName") {
            this.setState(prevState => ({
                showEditBox: !prevState.showEditBox,
                editLastName: true,
                editName: true,
                editFirstName: false,
                editEmail: false,
                editPhone: false
            }));

            return;
        }

        if (field == "email") {
            this.setState(prevState => ({
                showEditBox: !prevState.showEditBox,
                editLastName: false,
                editFirstName: false,
                editName: false,
                editEmail: true,
                editPhone: false
            }));

            return;
        }

        if (field == "phone") {
            this.setState(prevState => ({
                showEditBox: !prevState.showEditBox,
                editLastName: false,
                editFirstName: false,
                editName: false,
                editEmail: false,
                editPhone: true
            }));

            return;
        }

        this.setState(prevState => ({
            showEditBox: !prevState.showEditBox
        }));
    };

    componentWillReceiveProps(nextProps) {
        const notification = this.notificationSystem.current;
        const { fetch } = nextProps;

        if (fetch.status == "error" && fetch.message != null) {
            this.setState(
                {
                    errors: fetch.errors,
                    is_loading: false,
                    fetch_message: fetch.message
                },
                () => {
                    notification.addNotification({
                        message: this.state.fetch_message,
                        level: "error"
                    });
                }
            );
        }

        if (fetch.status == "success" && fetch.message != null) {
            this.setState(
                {
                    errors: [],
                    is_loading: false,
                    fetch_message: fetch.message,

                    showEditBox: false,
                    editName: false,
                    editEmail: false,
                    editPhone: false
                },
                () => {
                    notification.addNotification({
                        message: this.state.fetch_message,
                        level: "success"
                    });
                }
            );
        }
    }

    render() {
        let { translation } = this.props;
        const {
            errors,
            firstName,
            lastName,
            email,
            phone,
            showEditBox,
            editFirstName,
            editLastName,
            editEmail,
            editPhone,
            is_loading
        } = this.state;
        const { auth } = this.props;
        return (
            <div>
                <NotificationSystem ref={this.notificationSystem} />
                <IsLoading is_loading={is_loading} />
                <div className="fixed-bottom-padding bg-light-gray-2 h-100-vh">
                    <SimpleHeader
                        text={"Edit Profile"}
                        goBack={this.props.history.goBack}
                    />

                    <div
                        id="edit-box"
                        className={`shadow-lg ${showEditBox ? "shown" : ""}`}
                    >
                        <div className="container">
                            <div className="row">
                                <div
                                    className="heading-module col-12"
                                    onClick={this.toggleEditBox}
                                >
                                    <i class="icofont-close"></i>
                                </div>
                                <div className="change-box col-12">
                                    {editFirstName && (
                                        <div className="row">
                                            <div className="col-5">
                                                <div className="change-title">
                                                    <h6 className="m-0">
                                                        First Name
                                                    </h6>
                                                </div>
                                            </div>
                                            <div className="col-7">
                                                <input
                                                    class={`form-control ${
                                                        errors &&
                                                        errors.firstName
                                                            ? "is-invalid"
                                                            : ""
                                                    }`}
                                                    type="text"
                                                    placeholder="Full Name"
                                                    value={firstName}
                                                    name="firstName"
                                                    onChange={this.onChange}
                                                />
                                                {errors?.firstName && (
                                                    <div class="invalid-feedback">
                                                        {errors?.firstName[0]}
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                    )}

                                    {editLastName && (
                                        <div className="row">
                                            <div className="col-5">
                                                <div className="change-title">
                                                    <h6 className="m-0">
                                                        Last Name
                                                    </h6>
                                                </div>
                                            </div>
                                            <div className="col-7">
                                                <input
                                                    class={`form-control ${
                                                        errors &&
                                                        errors.lastName
                                                            ? "is-invalid"
                                                            : ""
                                                    }`}
                                                    type="text"
                                                    placeholder="Full Name"
                                                    value={lastName}
                                                    name="lastName"
                                                    onChange={this.onChange}
                                                />
                                                {errors?.lastName && (
                                                    <div class="invalid-feedback">
                                                        {errors?.lastName[0]}
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                    )}

                                    {editEmail && (
                                        <div className="row">
                                            <div className="col-4">
                                                <div className="change-title">
                                                    <h6 className="m-0">
                                                        Email
                                                    </h6>
                                                </div>
                                            </div>
                                            <div className="col-8">
                                                <input
                                                    class={`form-control ${
                                                        errors && errors.email
                                                            ? "is-invalid"
                                                            : ""
                                                    }`}
                                                    type="text"
                                                    placeholder="Email"
                                                    value={email}
                                                    name="email"
                                                    onChange={this.onChange}
                                                />
                                                {errors?.email && (
                                                    <div class="invalid-feedback">
                                                        {errors?.email[0]}
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                    )}

                                    {editPhone && (
                                        <div className="row">
                                            <div className="col-4">
                                                <div className="change-title">
                                                    <h6 className="m-0">
                                                        Phone
                                                    </h6>
                                                </div>
                                            </div>
                                            <div className="col-8">
                                                <input
                                                    class={`form-control ${
                                                        errors && errors.phone
                                                            ? "is-invalid"
                                                            : ""
                                                    }`}
                                                    type="text"
                                                    placeholder="Phone"
                                                    value={phone}
                                                    name="phone"
                                                    onChange={this.onChange}
                                                />
                                                {errors?.phone && (
                                                    <div class="invalid-feedback">
                                                        {errors?.phone[0]}
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                    )}
                                </div>
                                <div className="col-12 my-3">
                                    <button
                                        type="button"
                                        class="btn btn-primary btn-primary-appetizr submit-btn"
                                        onClick={() => this.submitForm()}
                                    >
                                        Change
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="container">
                        <div className="row justify-content-center mt-5">
                            <div className="col-6 text-center">
                                <div
                                    style={{
                                        height: "100px",
                                        width: "100px",
                                        background: "#fff",
                                        borderRadius: "50%",
                                        margin: "auto",
                                        padding: "10px 0"
                                    }}
                                >
                                    <img
                                        src="/images/user_placeholder.png"
                                        alt="user_image"
                                        className="img-fluid"
                                        width="70"
                                        height="70"
                                    />
                                </div>
                            </div>

                            <div className="col-12 mt-3">
                                <div
                                    className="bg-white clearfix edit-item"
                                    onClick={() =>
                                        this.toggleEditBox("firstName")
                                    }
                                >
                                    <div className="float-left">
                                        <h6 className="m-0">First Name</h6>
                                    </div>
                                    <div className="float-right">
                                        <span>{auth.userFirstName}</span>
                                        <div className="px-1 text-muted d-inline-block">
                                            <i class="icofont-rounded-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className="col-12 mt-3">
                                <div
                                    className="bg-white clearfix edit-item"
                                    onClick={() =>
                                        this.toggleEditBox("lastName")
                                    }
                                >
                                    <div className="float-left">
                                        <h6 className="m-0">Last Name</h6>
                                    </div>
                                    <div className="float-right">
                                        <span>{auth.userLastName}</span>
                                        <div className="px-1 text-muted d-inline-block">
                                            <i class="icofont-rounded-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className="col-12 mt-3">
                                <div
                                    className="bg-white clearfix edit-item"
                                    onClick={() => this.toggleEditBox("email")}
                                >
                                    <div className="float-left">
                                        <h6 className="m-0">Email</h6>
                                    </div>
                                    <div className="float-right">
                                        <span>{auth.userEmail}</span>
                                        <div className="px-1 text-muted d-inline-block">
                                            <i class="icofont-rounded-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className="col-12 mt-3">
                                <div
                                    className="bg-white clearfix edit-item"
                                    onClick={() => this.toggleEditBox("phone")}
                                >
                                    <div className="float-left">
                                        <h6 className="m-0">Phone</h6>
                                    </div>
                                    <div className="float-right">
                                        <span>{auth.userPhone}</span>
                                        <div className="px-1 text-muted d-inline-block">
                                            <i class="icofont-rounded-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className="col-12 mt-3 px-3">
                                <div
                                    style={{
                                        borderBottom: "1px solid #e2e2e2"
                                    }}
                                ></div>
                            </div>
                        </div>
                    </div>

                    {/* 
                    <div className="osahan-body mt-5">
                        <div className="cart-page1 shadow">
                            <div className="p-3">
                                <div className="form-group">
                                    <label htmlFor="exampleInputOLDPassword1">
                                        {"Name"}
                                    </label>
                                    <input
                                        name="name"
                                        type="text"
                                        placeholder={"Name"}
                                        class={`form-control ${
                                            errors.name ? "is-invalid" : ""
                                        }`}
                                        value={this.state.name}
                                        onChange={this.onChange}
                                    />
                                    {errors.name && (
                                        <div class="invalid-feedback">
                                            {errors.name[0]}
                                        </div>
                                    )}
                                </div>

                                <div className="form-group">
                                    <label htmlFor="exampleInputNEWPassword1">
                                        {"Phone Number"}
                                    </label>
                                    <input
                                        type="tel"
                                        placeholder={"Phone Number"}
                                        class={`form-control ${
                                            errors.phone ? "is-invalid" : ""
                                        }`}
                                        name="phone"
                                        value={this.state.phone}
                                        onChange={this.onChange}
                                    />
                                    {errors.phone && (
                                        <div class="invalid-feedback">
                                            {errors.phone[0]}
                                        </div>
                                    )}
                                </div>

                                <div className="form-group">
                                    <label htmlFor="exampleInputNEWPassword1">
                                        {"Email"}
                                    </label>
                                    <input
                                        type="email"
                                        placeholder={"Email"}
                                        class={`form-control ${
                                            errors.email ? "is-invalid" : ""
                                        }`}
                                        name="comments"
                                        value={this.state.email}
                                        onChange={this.onChange}
                                    />
                                    {errors.email && (
                                        <div class="invalid-feedback">
                                            {errors.email[0]}
                                        </div>
                                    )}
                                </div>

                                <button
                                    type="button"
                                    class="btn btn-primary mr-2"
                                    onClick={() => this.submitForm()}
                                >
                                    Submit
                                </button>

                                <a
                                    href={`${ROUTE.ACCOUNT.ChangePassword.PAGES.VIEW.PATH}`}
                                    class="btn bg-success text-white"
                                >
                                    Change Password
                                </a>
                            </div>
                        </div>
                    </div> 
                    */}

                    <FooterBar translation={translation} />
                </div>
            </div>
        );
    }
}

const mapSateToProps = state => ({
    auth: state.auth,
    fetch: state.fetch
});

export default connect(mapSateToProps, {
    updateCustomer,
    refreshUser
})(Edit);
