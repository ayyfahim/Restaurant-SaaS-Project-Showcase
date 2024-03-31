import React from "react";
import ReactDOM from "react-dom";
import ROUTE from "../../config/route";
import countries from "../../helpers/geo/countries.json";
import domain from "../../config/api/domain";
import { connect } from "react-redux";
import { registerCustomer } from "../../actions/authAction";
import { resetFetch } from "../../actions/fetchAction";
import NotificationSystem from "react-notification-system";
import SuccessfullModal from "../Containers/SuccessfullModal";

class Register extends React.Component {
    notificationSystem = React.createRef();
    constructor(props) {
        super(props);
        this.state = {
            email: "",
            name: "",
            phoneNumber: "",
            phoneCountry: null,
            password: "",
            confirmPassword: "",
            countries: {},
            is_loading: false,
            is_done: false,
            errors: []
        };
    }

    componentWillMount() {
        this.setState({ errors: [], countries: countries.countries });
    }

    handleChange = e => {
        this.setState({
            [e.target.id]: e.target.value
        });
    };

    handleSubmit = e => {
        e.preventDefault();
        console.log("submit button has been clicked");
        console.log(this.state);
        this.props.signUp(this.state);
    };

    submitForm = () => {
        event.preventDefault();
        // this.props.resetFetch();

        const {
            email,
            name,
            password,
            confirmPassword,
            phoneNumber
        } = this.state;

        let data = {
            email: this.state.email,
            name: this.state.name,
            password: this.state.password,
            password_confirmation: this.state.confirmPassword,
            phone: this.state.phoneNumber,
            phoneCountry: this.state.phoneCountry
        };

        this.setState({ is_loading: true });

        this.props.registerCustomer(data, this.props.history);

        // if (this.props.fetch.status == "error") {
        //     this.setState({
        //         errors: this.props.fetch.errors,
        //         is_loading: false
        //     });

        //     notification.addNotification({
        //         message: this.props.fetch.message,
        //         level: "error"
        //     });
        //     return;
        // }

        // if (this.props.fetch.status == "success") {
        //     document.getElementById("#successfullModal").click();
        // }
    };

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
            document.getElementById("#successfullModal").click();
        }
    }

    render() {
        const errors = this.state.errors;
        const countries = this.state.countries;
        return (
            <>
                <NotificationSystem ref={this.notificationSystem} />
                <div className="osahan-signup">
                    <div className="p-3">
                        <h2 className="my-0">Welcome Back</h2>
                        <p className="small">Sign up to Continue.</p>
                        <form action="#">
                            <div className="form-group">
                                <label htmlFor="email">Email</label>
                                <input
                                    placeholder="Enter Email"
                                    type="email"
                                    className={`form-control ${
                                        errors.email ? "is-invalid" : ""
                                    }`}
                                    id="email"
                                    aria-describedby="emailHelp"
                                    onChange={this.handleChange}
                                />
                                {errors.email && (
                                    <div class="invalid-feedback">
                                        {errors.email[0]}
                                    </div>
                                )}
                            </div>
                            <div className="form-group">
                                <label htmlFor="name">Name</label>
                                <input
                                    placeholder="Enter Name"
                                    type="text"
                                    className={`form-control ${
                                        errors.name ? "is-invalid" : ""
                                    }`}
                                    id="name"
                                    aria-describedby="emailHelp"
                                    onChange={this.handleChange}
                                />
                                {errors.name && (
                                    <div class="invalid-feedback">
                                        {errors.name[0]}
                                    </div>
                                )}
                            </div>
                            <div className="form-group row mx-0">
                                <label
                                    htmlFor="phoneNumber"
                                    className="col-12 pl-0"
                                >
                                    Phone Number
                                </label>
                                <select
                                    class="form-control col-3"
                                    id="phoneCountry"
                                    onChange={this.handleChange}
                                >
                                    {countries.map(country => (
                                        <option value={country.sortname}>
                                            {country.name}
                                        </option>
                                    ))}
                                </select>
                                <input
                                    placeholder="Enter Phone Number"
                                    type="tel"
                                    className={`form-control col-9 ${
                                        errors.phone ? "is-invalid" : ""
                                    }`}
                                    id="phoneNumber"
                                    aria-describedby="emailHelp"
                                    onChange={this.handleChange}
                                />
                                {errors.phone && (
                                    <div class="invalid-feedback">
                                        {errors.phone[0]}
                                    </div>
                                )}
                            </div>
                            <div className="form-group">
                                <label htmlFor="password">Password</label>
                                <input
                                    placeholder="Enter Password"
                                    type="password"
                                    className={`form-control ${
                                        errors.password ? "is-invalid" : ""
                                    }`}
                                    id="password"
                                    onChange={this.handleChange}
                                />
                                {errors.password && (
                                    <div class="invalid-feedback">
                                        {errors.password[0]}
                                    </div>
                                )}
                            </div>
                            <div className="form-group">
                                <label htmlFor="confirmPassword">
                                    Confirm Password
                                </label>
                                <input
                                    placeholder="Enter Password"
                                    type="password"
                                    className={`form-control ${
                                        errors.password_confirmation
                                            ? "is-invalid"
                                            : ""
                                    }`}
                                    id="confirmPassword"
                                    onChange={this.handleChange}
                                />
                            </div>
                            <button
                                type="submit"
                                className="btn btn-success btn-lg rounded btn-block"
                                onClick={() => this.submitForm()}
                            >
                                Sign Up
                            </button>
                        </form>
                    </div>
                </div>

                <SuccessfullModal text="Thank You! Your Registration Was Successful" />
            </>
        );
    }
}

const mapSateToProps = state => ({
    email: state.email,
    name: state.name,
    password: state.password,
    confirmPassword: state.confirmPassword,
    phoneNumber: state.phoneNumber,
    fetch: state.fetch
});

const mapDispatchToProps = dispatch => {
    return {
        registerCustomer: (creds, props) =>
            dispatch(registerCustomer(creds, props)),
        resetFetch: () => dispatch(resetFetch())
    };
};

export default connect(mapSateToProps, mapDispatchToProps)(Register);
