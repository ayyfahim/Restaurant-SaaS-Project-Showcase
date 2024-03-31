import React from "react";
import ReactDOM from "react-dom";
import { NavLink, Route } from "react-router-dom";
import ROUTE from "../../config/route";
import { connect } from "react-redux";
import ErrorModal from "../Containers/ErrorModal";

class FooterBar extends React.Component {
    state = {
        isSideBarOpen: false,
        store_id: null
    };

    componentWillMount() {
        this.setState({ store_id: localStorage.getItem("storeId") });
    }

    closeSideBar = value => {
        this.setState({ isSideBarOpen: !value });
        document.body.classList.add("sidemenu-open");
    };

    loginFirst(path) {
        if (this.props.isLogin) {
            window.location.href = `${path}`;
        } else {
            document.getElementById("#errorModal").click();

            setTimeout(() => {
                window.location.href = `${path}`;
            }, 3000);
        }
    }

    render() {
        let { store_name, description, active, cart, translation } = this.props;
        let { store_id } = this.state;
        return (
            <div
                class="osahan-menu-fotter fixed-bottom bg-white text-center border-top"
                style={{
                    height: "auto"
                }}
            >
                <div class="row m-0">
                    <a
                        href={`${ROUTE.STORE.INDEX.PAGES.VIEW.PATH}/${store_id}`}
                        className={
                            active == "home"
                                ? "text-dark small col font-weight-bold text-decoration-none p-2 py-3 selected"
                                : "text-dark small col font-weight-bold text-decoration-none p-2 py-3"
                        }
                    >
                        <p class="h5 m-0">
                            <img
                                src={`/images/icons/store/${
                                    active == "home"
                                        ? "menu_red.png"
                                        : "menu.png"
                                }`}
                                style={{ width: 20, height: 20 }}
                            />
                        </p>
                    </a>
                    <a
                        href={`${ROUTE.STORE.INDEX.PAGES.CART.PATH}/${store_id}`}
                        className={
                            active == "cart"
                                ? "text-dark small col font-weight-bold text-decoration-none p-2 py-3 selected"
                                : "text-dark small col font-weight-bold text-decoration-none p-2 py-3"
                        }
                    >
                        <p class="h5 m-0">
                            <img
                                src={`/images/icons/store/${
                                    active == "cart"
                                        ? "cart_red.png"
                                        : "cart.png"
                                }`}
                                style={{ width: 20, height: 20 }}
                            />
                        </p>
                    </a>

                    <a
                        href={`${ROUTE.ACCOUNT.SHOW.PAGES.VIEW.PATH}`}
                        className={
                            active == "orders"
                                ? "text-dark small col font-weight-bold text-decoration-none p-2 py-3 selected"
                                : "text-dark small col font-weight-bold text-decoration-none p-2 py-3"
                        }
                    >
                        <p class="h5 m-0">
                            <img
                                src={`/images/icons/store/${
                                    active == "view_account"
                                        ? "view_account_red.png"
                                        : "view_account.png"
                                }`}
                                style={{ width: 20, height: 20 }}
                            />
                        </p>
                    </a>
                </div>
            </div>
        );
    }
}
const mapSateToProps = state => ({
    isLogin: state.auth?.isLogin
});
export default connect(mapSateToProps, {})(FooterBar);
