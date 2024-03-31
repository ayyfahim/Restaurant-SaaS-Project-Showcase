import React from "react";
import ReactDOM from "react-dom";
import { NavLink, Route } from "react-router-dom";
import domain from "../../config/api/domain";
import ROUTE from "../../config/route";
import { logoutCustomer } from "../../actions/authAction";
import { connect } from "react-redux";

class Header extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            isSideBarOpen: false,
            store_id: null
        };
    }

    componentWillMount() {
        this.setState({ store_id: localStorage.getItem("storeId") });
    }

    closeSideBar = value => {
        this.setState({ isSideBarOpen: !value });
        document.body.classList.add("sidemenu-open");
    };

    logOutUser() {
        if (!this.props.isLogin) {
            console.log("Please Login First.");
            return;
        }
        this.props.logoutCustomer();
    }

    render() {
        let {
            store_name,
            store_logo,
            description,
            logo,
            translation,
            isLogin
        } = this.props;
        let { store_id } = this.state;
        return (
            <div class="p-3">
                <div class="title d-flex align-items-center">
                    <a class="text-decoration-none text-dark d-flex align-items-center">
                        {store_logo ? (
                            <img
                                src={`/${store_logo}`}
                                alt={store_name}
                                style={{ maxHeight: 30 }}
                            />
                        ) : (
                            <h4 class="font-weight-bold text-white m-0">
                                {store_name}
                            </h4>
                        )}
                    </a>

                    <div className="ml-auto d-flex">
                        {isLogin ? (
                            <>
                                <p
                                    class="m-0"
                                    onClick={() =>
                                        document
                                            .getElementById("#call-the-waiter")
                                            .click()
                                    }
                                >
                                    <a class="text-decoration-none bg-white p-1 rounded shadow-sm d-flex align-items-center">
                                        <i className="icofont-boy text-dark"></i>
                                        <span class="badge badge-danger p-1 ml-1 small">
                                            {translation?.call_the_waiter ||
                                                "Call the waiter"}
                                        </span>
                                    </a>
                                </p>
                                <a
                                    class="text-decoration-none bg-white p-1 rounded shadow-sm d-flex align-items-center ml-1"
                                    href={`${ROUTE.ACCOUNT.SHOW.PAGES.VIEW.PATH}`}
                                >
                                    <i className="icofont-boy text-dark"></i>
                                    <span class="badge badge-danger p-1 ml-1 small">
                                        {"My Account"}
                                    </span>
                                </a>
                                <a
                                    class="text-decoration-none bg-white p-1 rounded shadow-sm d-flex align-items-center ml-1"
                                    href="#"
                                    onClick={() => this.logOutUser()}
                                >
                                    <i className="icofont-boy text-dark"></i>
                                    <span class="badge badge-danger p-1 ml-1 small">
                                        {"Log Out"}
                                    </span>
                                </a>
                            </>
                        ) : (
                            <a
                                class="text-decoration-none bg-white p-1 rounded shadow-sm d-flex align-items-center ml-1"
                                href={`${ROUTE.ACCOUNT.LOGIN.PAGES.VIEW.PATH}`}
                            >
                                <i className="icofont-boy text-dark"></i>
                                <span class="badge badge-danger p-1 ml-1 small">
                                    {"Login/Sign Up"}
                                </span>
                            </a>
                        )}
                    </div>
                </div>
            </div>
        );
    }
}

// export default Header;

const mapSateToProps = state => ({
    isSideBarOpen: state.isSideBarOpen,
    store_id: state.store_id,
    isLogin: state.auth.isLogin
});

export default connect(mapSateToProps, {
    logoutCustomer
})(Header);
