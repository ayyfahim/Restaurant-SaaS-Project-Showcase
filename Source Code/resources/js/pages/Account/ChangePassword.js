import React from "react";
import ReactDOM from "react-dom";
import Header from "../../components/Header";
import SideBar from "../../components/SideBar";
import { NavLink, Route } from "react-router-dom";
import { connect } from "react-redux";
import domain from "../../config/api/domain";
import ROUTE from "../../config/route";
import FooterBar from "../Containers/FooterBar";
import NotificationSystem from "react-notification-system";
import { updateCustomerPassword } from "../../actions/authAction";
import SuccessfullModal from "../Containers/SuccessfullModal";
import { resetFetch } from "../../actions/fetchAction";

let storeId = null;
class ChangePassword extends React.Component {
    notificationSystem = React.createRef();
    constructor(props) {
        super(props);
        this.state = {
            current_password: "",
            new_password: "",
            new_confirm_password: ""
        };
        this.onChange = this.onChange.bind(this);
    }

    componentWillMount() {
        this.setState({ errors: [] });
    }

    componentWillReceiveProps(nextProps) {
        const notification = this.notificationSystem.current;
        const { fetch } = nextProps;

        // console.log(`fetch props`, fetch);

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
            document.getElementById("#successfullModal").click();
        }
    }

    getAuthUser() {
        const user = this.props.auth;
        let data = {
            name: user.userName,
            phone: user.userPhone,
            email: user.userEmail,
            userId: user.userId
        };
        this.setState(data);
    }

    onChange(e) {
        this.setState({ [e.target.name]: e.target.value });
    }

    submitForm = () => {
        const notification = this.notificationSystem.current;

        if (!this.props.auth.isLogin) {
            notification.addNotification({
                message: "Please login first.",
                level: "error"
            });
            return;
        }

        this.props.resetFetch();

        event.preventDefault();
        const {
            current_password,
            new_password,
            new_confirm_password
        } = this.state;

        let data = {
            current_password: current_password,
            new_password: new_password,
            new_confirm_password: new_confirm_password
        };

        this.props.updateCustomerPassword(data, this.props.history);
    };

    render() {
        const errors = this.state.errors;
        let { translation } = this.props;
        return (
            <div>
                <NotificationSystem ref={this.notificationSystem} />
                <div className="fixed-bottom-padding">
                    {/*<Header />*/}
                    {/*<SideBar active="Cart" />*/}

                    <div className="p-3 border-bottom shadow">
                        <div className="d-flex align-items-center">
                            <h5 className="font-weight-bold m-0">
                                {"My Account"}
                            </h5>
                        </div>
                    </div>

                    <div className="osahan-body mt-5">
                        <div className="cart-page1 shadow">
                            <div className="p-3">
                                <div className="form-group">
                                    <label htmlFor="exampleInputOLDPassword1">
                                        {"Current Password"}
                                    </label>
                                    <input
                                        name="current_password"
                                        type="password"
                                        placeholder={"Current Password"}
                                        className={`form-control ${
                                            errors.current_password
                                                ? "is-invalid"
                                                : ""
                                        }`}
                                        value={this.state.current_password}
                                        onChange={this.onChange}
                                    />
                                    {errors.current_password && (
                                        <div class="invalid-feedback">
                                            {errors.current_password[0]}
                                        </div>
                                    )}
                                </div>

                                <div className="form-group">
                                    <label htmlFor="exampleInputOLDPassword1">
                                        {"New Password"}
                                    </label>
                                    <input
                                        name="new_password"
                                        type="password"
                                        placeholder={"New Password"}
                                        className={`form-control ${
                                            errors.new_password
                                                ? "is-invalid"
                                                : ""
                                        }`}
                                        value={this.state.new_password}
                                        onChange={this.onChange}
                                    />
                                    {errors.new_password && (
                                        <div class="invalid-feedback">
                                            {errors.new_password[0]}
                                        </div>
                                    )}
                                </div>

                                <div className="form-group">
                                    <label htmlFor="exampleInputOLDPassword1">
                                        {"Confirm Password"}
                                    </label>
                                    <input
                                        name="new_confirm_password"
                                        type="password"
                                        placeholder={"Confirm Password"}
                                        className={`form-control ${
                                            errors.new_confirm_password
                                                ? "is-invalid"
                                                : ""
                                        }`}
                                        value={this.state.new_confirm_password}
                                        onChange={this.onChange}
                                    />
                                    {errors.new_confirm_password && (
                                        <div class="invalid-feedback">
                                            {errors.new_confirm_password[0]}
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
                                    href={`${ROUTE.ACCOUNT.SHOW.PAGES.VIEW.PATH}`}
                                    class="btn bg-success text-white"
                                >
                                    Go to my account
                                </a>
                            </div>
                        </div>
                    </div>

                    <FooterBar translation={translation} />
                    <SuccessfullModal text="Thank You! Your Registration Was Successful" />
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
    updateCustomerPassword,
    resetFetch
})(ChangePassword);
