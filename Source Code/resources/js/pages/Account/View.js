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
import { refreshUser, logoutCustomer } from "../../actions/authAction";
import { leaveTable, fetchOrders, fetchTable } from "../../actions/orderAction";
import misc from "../../helpers/misc";
import CallTheWaiterButton from "../Containers/CallTheWaiterButton";
import CallTheWaiter from "../Containers/CallTheWaiter";

let storeId = null;

class View extends React.Component {
    notificationSystem = React.createRef();
    constructor(props) {
        super(props);
        this.state = {
            is_loading: false,
            errors: [],
            showSideBar: false
        };
        // this.onChange = this.onChange.bind(this);
    }

    componentDidMount() {
        this.props.refreshUser();
        this.timer = setInterval(() => this.props.refreshUser(), 120000);
        if (this.props.table) {
            let data = {
                table_id: this.props.table.table_number
            };
            this.props.fetchTable(data);
        }
    }

    toggleSidebar = () => {
        this.setState(prevState => ({
            showSideBar: !prevState.showSideBar
        }));
    };

    logOutUser() {
        if (!this.props.auth.isLogin) {
            console.log("Please Login First.");
            return;
        }
        this.props.logoutCustomer();
    }

    userHasUnpaidOrders() {
        let { orders } = this.props;

        if (orders?.length > 0) {
            let unpaidOrder = orders.find(data => data.is_paid == 0);

            if (unpaidOrder) {
                return true;
            }
        }

        return false;
    }

    userLeavesTable() {
        this.props.fetchOrders();

        let { orders } = this.props;

        if (orders) {
            if (this.userHasUnpaidOrders()) {
                const notification = this.notificationSystem.current;
                notification.addNotification({
                    message: "Please pay for your orders.",
                    level: "error"
                });

                setInterval(
                    () =>
                        (window.location.href = `${ROUTE.ACCOUNT.PAY.PAGES.VIEW.PATH}`),
                    misc.setTimeoutTimer
                );

                return;
            }

            this.props.leaveTable();

            localStorage.setItem("storeId", null);

            // window.location.href = `${ROUTE.STORE.HOME.PAGES.VIEW.PATH}`;
        }
    }

    render() {
        let { translation, auth, table } = this.props;
        const { errors, showSideBar } = this.state;
        return (
            <div className="bg-light-gray-2">
                <NotificationSystem ref={this.notificationSystem} />

                <div
                    id="customer-sidebar"
                    className={`shadow-sm bg-light-gray-2 ${
                        showSideBar ? "sidebar-shown" : ""
                    }`}
                >
                    <div className="container bg-light-gray-2">
                        <div className="row justify-content-center">
                            <div className="col-12 mb-4">
                                <a onClick={this.toggleSidebar}>
                                    <i
                                        class="icofont-long-arrow-left"
                                        style={{
                                            fontSize: 30
                                        }}
                                    ></i>
                                </a>
                            </div>

                            <div className="col-10">
                                <a
                                    href={`${ROUTE.ACCOUNT.EDIT.PAGES.VIEW.PATH}`}
                                    className="menu-item"
                                >
                                    <i
                                        class="icofont-user-alt-5"
                                        style={{
                                            fontSize: 20
                                        }}
                                    ></i>
                                    Edit Profile
                                </a>

                                <a
                                    href={`${ROUTE.ACCOUNT.ALLERGENS.PAGES.VIEW.PATH}`}
                                    className="menu-item"
                                >
                                    <i
                                        class="icofont-leaf"
                                        style={{
                                            fontSize: 20
                                        }}
                                    ></i>
                                    Allergens
                                </a>

                                <a
                                    href={`${ROUTE.ACCOUNT.ORDERS.PAGES.VIEW.PATH}`}
                                    className="menu-item"
                                >
                                    <i
                                        class="icofont-list"
                                        style={{
                                            fontSize: 20
                                        }}
                                    ></i>
                                    My Orders
                                </a>

                                <a
                                    href={`${ROUTE.ACCOUNT.TABLEORDERS.PAGES.VIEW.PATH}`}
                                    className="menu-item"
                                >
                                    <i
                                        class="icofont-dining-table"
                                        style={{
                                            fontSize: 20
                                        }}
                                    ></i>
                                    Table Orders
                                </a>

                                <a
                                    href={`${ROUTE.ACCOUNT.CARD.PAGES.VIEW.PATH}`}
                                    className="menu-item"
                                >
                                    <i
                                        class="icofont-credit-card"
                                        style={{
                                            fontSize: 20
                                        }}
                                    ></i>
                                    Add Card
                                </a>

                                <a
                                    href={`${ROUTE.ACCOUNT.PAY.PAGES.VIEW.PATH}`}
                                    className="menu-item"
                                >
                                    <i
                                        class="icofont-pay"
                                        style={{
                                            fontSize: 20
                                        }}
                                    ></i>
                                    Pay
                                </a>

                                <a
                                    href={`${ROUTE.ACCOUNT.RECEIPITS.PAGES.VIEW.PATH}`}
                                    className="menu-item"
                                >
                                    <i
                                        class="icofont-file-pdf"
                                        style={{
                                            fontSize: 20
                                        }}
                                    ></i>
                                    Receipts
                                </a>

                                <a
                                    href={`${ROUTE.ACCOUNT.LANGUAGE.PAGES.VIEW.PATH}`}
                                    className="menu-item"
                                >
                                    <i
                                        class="icofont-globe"
                                        style={{
                                            fontSize: 20
                                        }}
                                    ></i>
                                    Language
                                </a>

                                <a
                                    href={`${ROUTE.ACCOUNT.SUPPORT.PAGES.VIEW.PATH}`}
                                    className="menu-item"
                                >
                                    <i
                                        class="icofont-support"
                                        style={{
                                            fontSize: 20
                                        }}
                                    ></i>
                                    Support
                                </a>

                                <a
                                    onClick={() => this.logOutUser()}
                                    className="menu-item"
                                >
                                    <i
                                        class="icofont-logout"
                                        style={{
                                            fontSize: 20
                                        }}
                                    ></i>
                                    Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    className="fixed-bottom-padding bg-light-gray-2 container h-100-vh"
                    style={{
                        paddingBottom: 170
                    }}
                    onClick={e => {
                        e.stopPropagation();
                        this.setState(() => ({
                            showSideBar: false
                        }));
                    }}
                >
                    <div className="row">
                        <div className="col text-left pt-3">
                            <a
                                onClick={e => {
                                    e.stopPropagation();
                                    this.toggleSidebar();
                                }}
                            >
                                <i
                                    class="icofont-navigation-menu"
                                    style={{
                                        fontSize: 20
                                    }}
                                ></i>
                            </a>
                        </div>
                        <div className="col text-right pt-3">
                            <a
                                href={`${ROUTE.ACCOUNT.EDIT.PAGES.VIEW.PATH}`}
                                className="text-dark"
                            >
                                <i
                                    class="icofont-edit"
                                    style={{
                                        fontSize: 20
                                    }}
                                ></i>
                            </a>
                        </div>
                    </div>
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
                            <h5 className="my-2">{auth.userName}</h5>
                            <p>{auth.userEmail}</p>
                            <p>{auth.userPhone}</p>
                        </div>
                        <div className="col-12 mt-5 p-0">
                            <div
                                style={{ borderTop: "1px solid #e2e2e2" }}
                                className="user-buttons"
                            >
                                <div className="user-btn w-100 row m-0 justify-content-center">
                                    <div className="p-1">
                                        <a
                                            href={`${
                                                ROUTE.STORE.INDEX.PAGES.DETAILED
                                                    .PATH
                                            }/${localStorage.getItem(
                                                "storeId"
                                            )}`}
                                            className="m-auto shadow-sm"
                                        >
                                            <img
                                                src="/images/icons/menu.png"
                                                alt=""
                                            />
                                            <h6>Menu</h6>
                                        </a>
                                    </div>
                                    <div className="p-1">
                                        <a
                                            href={`${ROUTE.ACCOUNT.TABLEORDERS.PAGES.VIEW.PATH}`}
                                            className="m-auto shadow-sm"
                                        >
                                            <img
                                                src="/images/icons/tab.png"
                                                alt=""
                                            />
                                            <h6>Tab</h6>
                                        </a>
                                    </div>
                                    <div className="p-1">
                                        <a
                                            href={`${ROUTE.ACCOUNT.ORDERS.PAGES.VIEW.PATH}`}
                                            className="m-auto shadow-sm"
                                        >
                                            <img
                                                src="/images/icons/my_orders.png"
                                                alt=""
                                            />
                                            <h6>Orders</h6>
                                        </a>
                                    </div>
                                    <div className="p-1">
                                        <a
                                            href={`${ROUTE.ACCOUNT.PAY.PAGES.VIEW.PATH}`}
                                            className="m-auto shadow-sm"
                                        >
                                            <img
                                                src="/images/icons/pay.png"
                                                alt=""
                                            />
                                            <h6>Pay</h6>
                                        </a>
                                    </div>
                                    {Object.keys(table).length > 0 && (
                                        <div className="p-1">
                                            <a
                                                onClick={() =>
                                                    this.userLeavesTable()
                                                }
                                                className="m-auto shadow-sm"
                                            >
                                                <img
                                                    src="/images/icons/leave_table.png"
                                                    alt=""
                                                    style={{
                                                        width: 35
                                                    }}
                                                />
                                                <h6>Leave</h6>
                                            </a>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>

                    <CallTheWaiter
                        translation={translation}
                        tables={this.props.tables}
                        store_id={storeId}
                        tableId={this.props.table.id}
                    />

                    {this.props.table && <CallTheWaiterButton />}

                    <FooterBar
                        translation={translation}
                        active="view_account"
                    />
                </div>
            </div>
        );
    }
}

const mapSateToProps = state => ({
    auth: state?.auth,
    fetch: state?.fetch,
    table: state?.orders?.selectedTable,
    orders: state.orders?.Orders
});

export default connect(mapSateToProps, {
    refreshUser,
    logoutCustomer,
    leaveTable,
    fetchOrders,
    fetchTable
})(View);
