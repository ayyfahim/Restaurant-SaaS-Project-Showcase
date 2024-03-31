import React from "react";
import ReactDOM from "react-dom";
import Moment from "moment";
import Header from "../../components/Header";
import SideBar from "../../components/SideBar";
import { NavLink, Route } from "react-router-dom";
import ROUTE from "../../config/route";
import { connect } from "react-redux";
import { selectOrderCheckout } from "../../actions/checkoutAction";
import { fetchOrders, fetchTableOrders } from "../../actions/orderAction";
import { callTheWaiter } from "../../actions/storeAction";
import { FETCH_REFRESH } from "../../actions/types";
import FooterBar from "../Containers/FooterBar";
import NotificationSystem from "react-notification-system";
import PriceRender from "../Containers/PriceRender";
import PayForOrder from "../Containers/PayForOrder";
import PayAtRestaurant from "../Containers/PayAtRestaurant";
import ShowOrder from "../Containers/ShowOrder";
import LinesEllipsis from "react-lines-ellipsis";
import SimpleHeader from "../Containers/SimpleHeader";

var selectedTable = null;
var selectedTableOrders = null;

var style = {
    NotificationItem: {
        // Override the notification item
        DefaultStyle: {
            // Applied to every notification, regardless of the notification level
            margin: "10px 5px 2px 1px",
            background: "#28a745"
        },
        success: {
            color: "white"
        }
    }
};

class Orders extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            selectedOrder: null
        };
        this.onChange = this.onChange.bind(this);
    }
    notificationSystem = React.createRef();

    componentWillMount() {
        if (this.props.isLogin) {
            var data = {
                table_no: this.props.selectedTable?.id
            };

            this.props.fetchOrders(data);

            let { orders } = this.props;
            let selectedOrder = {
                selectedOrder: orders.filter(data => data.id == orders[0].id)
            };
            this.props.selectOrderCheckout(selectedOrder);
        } else {
            var data = {
                table_no: this.props.selectedTable?.id
            };
            this.props.fetchTableOrders(data);

            let { orders } = this.props;
            let selectedOrder = {
                selectedOrder: orders.filter(data => data.id == orders[0].id)
            };
            this.props.selectOrderCheckout(selectedOrder);
        }
    }

    componentDidMount() {
        if (this.props.isLogin) {
            let { orders } = this.props;
            let selectedOrder = {
                selectedOrder: orders.filter(data => data.id == orders[0].id)
            };
            this.props.selectOrderCheckout(selectedOrder);

            let data = {
                table_no: this.props.selectedTable?.id
            };

            this.props.fetchOrders(data);
            this.timer = setInterval(() => this.props.fetchOrders(data), 5000);
        } else {
            let { orders } = this.props;
            let selectedOrder = {
                selectedOrder: orders.filter(data => data.id == orders[0].id)
            };
            this.props.selectOrderCheckout(selectedOrder);

            let data = {
                table_no: this.props.selectedTable?.id
            };

            this.props.fetchTableOrders(data);
            this.timer = setInterval(
                () => this.props.fetchTableOrders(data),
                5000
            );
        }
    }

    onChange(e) {
        this.setState({ [e.target.name]: e.target.value });
    }

    payForOrder(id, modalId) {
        this.setState(
            {
                selectedOrder: id
            },
            () => {
                let { orders } = this.props;

                let data = {
                    selectedOrder: orders.filter(
                        data => data.id == this.state.selectedOrder
                    )
                };

                this.props.selectOrderCheckout(data);

                // console.log(`modalId`, modalId);
                // console.log(
                //     `document.getElementById`,
                //     document.getElementById(`${modalId}`)
                // );

                document.getElementById(`${modalId}`).click();
            }
        );
    }

    payForOrderSelectedOrder(id, modalId) {
        this.setState(
            {
                selectedOrder: id
            },
            () => {
                // document.getElementById("#pay-for-order").click();

                let { selectedTableOrders } = this.props;

                let data = {
                    selectedOrder: selectedTableOrders.filter(
                        data => data.id == this.state.selectedOrder
                    )
                };

                this.props.selectOrderCheckout(data);

                document.getElementById(`${modalId}`).click();
            }
        );
    }

    openOrderModal() {
        document.getElementById("#pay-for-order").click();
    }

    callWaiter(id) {
        let body = {
            order_id: id
        };
        this.props.callTheWaiter(body);

        if (this.props.fetch.status == "error") {
            const notification = this.notificationSystem.current;
            notification.addNotification({
                message: this.props.fetch.message,
                level: "error"
            });
        } else {
            const notification = this.notificationSystem.current;
            notification.addNotification({
                message: "calling waiter ....",
                level: "success"
            });
        }
    }

    isOrderPaid(data) {
        return Number(data.paid_amount) >= Number(data.total);
    }

    render() {
        let { orders, account_info, translation } = this.props;
        let currency = account_info ? account_info.currency_symbol : "USD";

        if (orders.length < 1) {
            return (
                <div className="fixed-bottom-padding">
                    <SimpleHeader
                        text={translation?.my_order || "My Order"}
                        goBack={this.props.history.goBack}
                    />
                    <div
                        style={{
                            textAlign: "center",
                            padding: 50
                        }}
                    >
                        <h2>No orders found...</h2>
                    </div>
                </div>
            );
        }

        return (
            <div>
                <NotificationSystem
                    ref={this.notificationSystem}
                    style={style}
                />
                {/*<Header />*/}
                {/*<SideBar active="Orders" />*/}
                <div className="fixed-bottom-padding">
                    <SimpleHeader
                        text={translation?.my_order || "My Order"}
                        goBack={this.props.history.goBack}
                    />

                    <main>
                        <div className="order-body px-3 pt-3">
                            <h6 className="mb-2">
                                {" "}
                                {translation?.menu_current_order ||
                                    "Current Order"}{" "}
                            </h6>

                            {orders.filter
                                ? orders
                                      .filter(
                                          data =>
                                              data.status == 1 ||
                                              data.status == 2 ||
                                              data.status == 5
                                      )
                                      .map(data => (
                                          <div className="pb-3">
                                              <a className="text-decoration-none text-dark">
                                                  <div
                                                      className="p-3 rounded shadow-sm recommanded1"
                                                      onClick={() => {
                                                          this.payForOrder(
                                                              data.id,
                                                              "#show-order-modal"
                                                          );
                                                      }}
                                                  >
                                                      <div
                                                          className="align-items-center mb-3"
                                                          style={{
                                                              maxWidth: 450
                                                          }}
                                                      >
                                                          <div className="row no-gutters">
                                                              <div className="col">
                                                                  <p
                                                                      className={`text-white py-1 px-2 mb-0 rounded small bg-${
                                                                          data.status ==
                                                                          1
                                                                              ? "warning"
                                                                              : "info"
                                                                      }`}
                                                                      onClick={e => {
                                                                          e.stopPropagation();
                                                                      }}
                                                                  >
                                                                      {data.status ==
                                                                      1
                                                                          ? translation?.order_status_pending ||
                                                                            "Pending"
                                                                          : null}{" "}
                                                                      {data.status ==
                                                                      2
                                                                          ? translation?.order_status_accepted ||
                                                                            "Accepted"
                                                                          : null}{" "}
                                                                      {data.status ==
                                                                      5
                                                                          ? translation?.order_status_ready ||
                                                                            "Ready to Serve"
                                                                          : null}{" "}
                                                                      {this.isOrderPaid(
                                                                          data
                                                                      )
                                                                          ? "(Paid)"
                                                                          : null}
                                                                  </p>
                                                              </div>
                                                              {data.is_paid !==
                                                                  1 &&
                                                                  !this.isOrderPaid(
                                                                      data
                                                                  ) && (
                                                                      <>
                                                                          <div className="col ml-2">
                                                                              <p
                                                                                  className="text-white py-1 px-2 mb-0  rounded small bg-success"
                                                                                  onClick={() => {
                                                                                      window.location.href =
                                                                                          ROUTE.ACCOUNT.PAY.PAGES.VIEW.PATH;
                                                                                  }}
                                                                              >
                                                                                  {
                                                                                      "Pay Now"
                                                                                  }
                                                                              </p>
                                                                          </div>
                                                                      </>
                                                                  )}
                                                          </div>
                                                      </div>
                                                      <p className="text-muted small mb-0">
                                                          <i className="icofont-clock-time"></i>{" "}
                                                          {Moment.utc(
                                                              data.created_at
                                                          ).format(
                                                              "h:mm:ss a"
                                                          )}{" "}
                                                          /{" "}
                                                          <b className="text-danger">
                                                              {Moment.utc(
                                                                  data.created_at
                                                              ).format(
                                                                  "MMM DD YYYY"
                                                              )}
                                                          </b>
                                                      </p>
                                                      <div className="d-flex">
                                                          <p className="text-muted m-0">
                                                              {translation?.menu_order_id ||
                                                                  "Order ID"}
                                                              <br />
                                                              <span className="text-dark font-weight-bold">
                                                                  {
                                                                      data.order_unique_id
                                                                  }
                                                              </span>
                                                          </p>
                                                          <p className="text-muted m-0 ml-auto">
                                                              {translation?.menu_store ||
                                                                  "Store"}{" "}
                                                              <br />
                                                              <span className="text-dark font-weight-bold">
                                                                  <LinesEllipsis
                                                                      text={
                                                                          data.store_name
                                                                      }
                                                                      maxLine="1"
                                                                      ellipsis="..."
                                                                      trimRight
                                                                      basedOn="letters"
                                                                  />
                                                              </span>
                                                          </p>
                                                          <p className="text-muted m-0 ml-auto">
                                                              {translation?.menu_bill_amount ||
                                                                  "Bill Amount"}
                                                              <br />
                                                              <span className="text-dark font-weight-bold">
                                                                  <PriceRender
                                                                      currency={
                                                                          currency
                                                                      }
                                                                      price={
                                                                          data.total
                                                                      }
                                                                  />
                                                              </span>
                                                              <br />
                                                              {"Paid Amount"}
                                                              <br />
                                                              <span className="text-dark font-weight-bold">
                                                                  <PriceRender
                                                                      currency={
                                                                          currency
                                                                      }
                                                                      price={
                                                                          data.paid_amount
                                                                      }
                                                                  />
                                                              </span>
                                                          </p>
                                                      </div>
                                                  </div>
                                              </a>
                                          </div>
                                      ))
                                : null}
                        </div>

                        <div className="order-body px-3 pt-3">
                            <h6 className="mb-2">
                                {" "}
                                {translation?.menu_completed_order ||
                                    "Completed Order"}{" "}
                            </h6>
                            {orders.filter
                                ? orders
                                      .filter(
                                          data =>
                                              data.status != 1 &&
                                              data.status != 2 &&
                                              data.status != 5
                                      )
                                      .map(data => (
                                          <div className="pb-3">
                                              <a className="text-decoration-none text-dark">
                                                  <div className="p-3 rounded shadow-sm recommanded1">
                                                      <div className="d-flex align-items-center mb-3">
                                                          <p
                                                              className={`text-white py-1 px-2 mb-0 rounded small bg-${
                                                                  data.status ==
                                                                  3
                                                                      ? "danger"
                                                                      : "success"
                                                              } ${
                                                                  data.status ==
                                                                  4
                                                                      ? "info"
                                                                      : ""
                                                              }`}
                                                              onClick={e => {
                                                                  e.stopPropagation();
                                                              }}
                                                          >
                                                              {data.status == 3
                                                                  ? "Canceled"
                                                                  : ""}
                                                              {data.status == 4
                                                                  ? translation?.order_status_completed ||
                                                                    "Completed"
                                                                  : ""}
                                                              {data.status == 5
                                                                  ? "Ready To Serve"
                                                                  : ""}
                                                          </p>
                                                          <p className="text-muted ml-auto small mb-0">
                                                              <i className="icofont-clock-time"></i>{" "}
                                                              {Moment.utc(
                                                                  data.created_at
                                                              ).format(
                                                                  "h:mm:ss a"
                                                              )}{" "}
                                                              /{" "}
                                                              <b className="text-danger">
                                                                  {Moment.utc(
                                                                      data.created_at
                                                                  ).format(
                                                                      "MMM DD YYYY"
                                                                  )}
                                                              </b>
                                                          </p>
                                                      </div>
                                                      <div className="d-flex">
                                                          <p className="text-muted m-0">
                                                              {translation?.menu_order_id ||
                                                                  "Order ID"}
                                                              <br />
                                                              <span className="text-dark font-weight-bold">
                                                                  {
                                                                      data.order_unique_id
                                                                  }
                                                              </span>
                                                          </p>
                                                          <p className="text-muted m-0 ml-auto">
                                                              {translation?.menu_store ||
                                                                  "Store"}{" "}
                                                              <br />
                                                              <span className="text-dark font-weight-bold">
                                                                  <LinesEllipsis
                                                                      text={
                                                                          data.store_name
                                                                      }
                                                                      maxLine="1"
                                                                      ellipsis="..."
                                                                      trimRight
                                                                      basedOn="letters"
                                                                  />
                                                              </span>
                                                          </p>
                                                          <p className="text-muted m-0 ml-auto">
                                                              {translation?.menu_bill_amount ||
                                                                  "Bill Amount"}{" "}
                                                              <br />
                                                              <span className="text-dark font-weight-bold">
                                                                  <PriceRender
                                                                      currency={
                                                                          currency
                                                                      }
                                                                      price={
                                                                          data.total
                                                                      }
                                                                  />
                                                              </span>
                                                          </p>
                                                      </div>
                                                  </div>
                                              </a>
                                          </div>
                                      ))
                                : null}
                        </div>

                        <PayForOrder selectedOrder={this.props.selectedOrder} />

                        <PayAtRestaurant
                            selectedOrder={this.props.selectedOrder}
                        />
                        <ShowOrder currency={currency} />

                        <FooterBar translation={translation} active="orders" />
                    </main>
                </div>
            </div>
        );
    }
}

const mapSateToProps = state => ({
    orders: state.orders.Orders,
    fetch: state.fetch,
    selectedOrder: state.checkout.order,
    selectedTable: state.orders.selectedTable,
    selectedTableOrders: state.orders.selectedTableOrders,
    account_info: state.store.account_info,
    translation: state.translation?.active?.data,
    userPhone: state.auth?.userPhone,
    isLogin: state.auth?.isLogin
});

export default connect(mapSateToProps, {
    fetchOrders,
    fetchTableOrders,
    callTheWaiter,
    selectOrderCheckout
})(Orders);
