import React from "react";
import ReactDOM from "react-dom";
import Moment from "moment";
import Header from "../../components/Header";
import SideBar from "../../components/SideBar";
import { NavLink, Route } from "react-router-dom";
import ROUTE from "../../config/route";
import { connect } from "react-redux";
import { selectOrderCheckout } from "../../actions/checkoutAction";
import { fetchOrders } from "../../actions/orderAction";
import { callTheWaiter } from "../../actions/storeAction";
import { FETCH_REFRESH } from "../../actions/types";
import FooterBar from "../Containers/FooterBar";
import NotificationSystem from "react-notification-system";
import PriceRender from "../Containers/PriceRender";
import PayForOrder from "../Containers/PayForOrder";
import PayAtRestaurant from "../Containers/PayAtRestaurant";
import ShowOrder from "../Containers/ShowOrder";

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
        // PhoneNumber = this.props.match.params.phone
        //     ? this.props.match.params.phone
        //     : null;

        var data = {
            table_no: this.props.selectedTable?.id
        };

        this.props.fetchOrders(data);

        // let { orders } = this.props;
        // let selectedOrder = {
        //     selectedOrder: orders.filter(data => data.id == orders[0].id)
        // };
        // this.props.selectOrderCheckout(selectedOrder);
    }

    componentWillUpdate(nextProps, nextState) {
        if (nextProps.orders.length) {
            // console.log(`orders`, nextProps);
            // console.log(`orders 1`, nextProps.orders);
            if (this.props.selectedOrder && !this.props.selectedOrder.length) {
                // console.log(`orders 2`, this.props.selectedOrder);
                // console.log(`orders 3`, this.props.selectedOrder.length);
                let { orders } = nextProps;
                let selectedOrder = {
                    selectedOrder: orders.filter(
                        data => data.id == orders[0].id
                    )
                };
                this.props.selectOrderCheckout(selectedOrder);
            }
        }
    }

    componentDidMount() {
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
                // document.getElementById("#pay-for-order").click();

                let { orders } = this.props;

                let data = {
                    selectedOrder: orders.filter(
                        data => data.id == this.state.selectedOrder
                    )
                };

                this.props.selectOrderCheckout(data);

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

    render() {
        let { orders, account_info, translation } = this.props;
        let currency = account_info ? account_info.currency_symbol : "USD";
        selectedTable =
            this.props.selectedTable != null ? this.props.selectedTable : null;
        selectedTableOrders = this.props.selectedTableOrders
            ? this.props.selectedTableOrders
            : null;

        return (
            <div>
                <NotificationSystem
                    ref={this.notificationSystem}
                    style={style}
                />
                {/*<Header />*/}
                {/*<SideBar active="Orders" />*/}
                <div className="fixed-bottom-padding">
                    <div className="p-3 border-bottom shadow">
                        <div className="d-flex align-items-center">
                            <h5 className="font-weight-bold m-0">
                                {translation?.my_order || "My Order"}{" "}
                            </h5>
                        </div>
                    </div>

                    {this.props.userPhone ? (
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
                                                                          {Number(
                                                                              data.paid_amount
                                                                          ) >=
                                                                          Number(
                                                                              data.total
                                                                          )
                                                                              ? "(Paid)"
                                                                              : null}
                                                                      </p>
                                                                  </div>
                                                                  <div className="col">
                                                                      <p
                                                                          style={{
                                                                              marginLeft: 10
                                                                          }}
                                                                          onClick={e => {
                                                                              e.stopPropagation();
                                                                              this.callWaiter(
                                                                                  data.id
                                                                              );
                                                                          }}
                                                                          className="text-white py-1 px-2 mb-0  rounded small bg-secondary"
                                                                      >
                                                                          {translation?.call_the_waiter ||
                                                                              "Call The Waiter"}{" "}
                                                                      </p>
                                                                  </div>
                                                                  {data.status !==
                                                                      1 && (
                                                                      <>
                                                                          <div className="col">
                                                                              <p
                                                                                  style={{
                                                                                      marginLeft: 10
                                                                                  }}
                                                                                  onClick={e => {
                                                                                      e.stopPropagation();
                                                                                      this.payForOrder(
                                                                                          data.id,
                                                                                          "#pay-for-order"
                                                                                      );
                                                                                  }}
                                                                                  className="text-white py-1 px-2 mb-0  rounded small bg-success"
                                                                              >
                                                                                  {
                                                                                      "Pay Online"
                                                                                  }
                                                                              </p>
                                                                          </div>
                                                                          <div className="col">
                                                                              <p
                                                                                  style={{
                                                                                      marginLeft: 10
                                                                                  }}
                                                                                  onClick={e => {
                                                                                      e.stopPropagation();
                                                                                      this.payForOrder(
                                                                                          data.id,
                                                                                          "#pay-at-restaurant"
                                                                                      );
                                                                                  }}
                                                                                  className="text-white py-1 px-2 mb-0 rounded small bg-info"
                                                                              >
                                                                                  {
                                                                                      "Pay at restaurant"
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
                                                                      {
                                                                          data.store_name
                                                                      }
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
                                                                  {
                                                                      "Paid Amount"
                                                                  }
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

                            {selectedTableOrders &&
                            Object.keys(selectedTableOrders).length > 0 ? (
                                <div className="order-body px-3 pt-3">
                                    <h6 className="mb-2">
                                        {" "}
                                        {`Orders on table no: ${selectedTable.id}`}{" "}
                                    </h6>
                                    {selectedTableOrders.filter
                                        ? selectedTableOrders
                                              .filter(
                                                  data =>
                                                      data.table_no ==
                                                      selectedTable.id
                                              )
                                              .map(data => (
                                                  <div className="pb-3">
                                                      <a className="text-decoration-none text-dark">
                                                          <div
                                                              className="p-3 rounded shadow-sm recommanded1"
                                                              onClick={() => {
                                                                  this.payForOrderSelectedOrder(
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
                                                                              }
                                                                          ${
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
                                                                              4
                                                                                  ? translation?.order_status_accepted ||
                                                                                    "Completed"
                                                                                  : null}{" "}
                                                                              {data.status ==
                                                                              5
                                                                                  ? translation?.order_status_ready ||
                                                                                    "Ready to Serve"
                                                                                  : null}{" "}
                                                                              {Number(
                                                                                  data.paid_amount
                                                                              ) >=
                                                                              Number(
                                                                                  data.total
                                                                              )
                                                                                  ? "(Paid)"
                                                                                  : null}
                                                                          </p>
                                                                      </div>
                                                                      <div className="col">
                                                                          <p
                                                                              style={{
                                                                                  marginLeft: 10
                                                                              }}
                                                                              onClick={e => {
                                                                                  e.stopPropagation();
                                                                                  this.callWaiter(
                                                                                      data.id
                                                                                  );
                                                                              }}
                                                                              className="text-white py-1 px-2 mb-0  rounded small bg-secondary"
                                                                          >
                                                                              {translation?.call_the_waiter ||
                                                                                  "Call The Waiter"}{" "}
                                                                          </p>
                                                                      </div>
                                                                      {data.status !==
                                                                          1 && (
                                                                          <>
                                                                              <div className="col">
                                                                                  <p
                                                                                      style={{
                                                                                          marginLeft: 10
                                                                                      }}
                                                                                      onClick={e => {
                                                                                          e.stopPropagation();
                                                                                          this.payForOrderSelectedOrder(
                                                                                              data.id,
                                                                                              "#pay-for-order"
                                                                                          );
                                                                                      }}
                                                                                      className="text-white py-1 px-2 mb-0  rounded small bg-success"
                                                                                  >
                                                                                      {
                                                                                          "Pay Online"
                                                                                      }
                                                                                  </p>
                                                                              </div>
                                                                              <div className="col">
                                                                                  <p
                                                                                      style={{
                                                                                          marginLeft: 10
                                                                                      }}
                                                                                      onClick={e => {
                                                                                          e.stopPropagation();
                                                                                          this.payForOrderSelectedOrder(
                                                                                              data.id,
                                                                                              "#pay-at-restaurant"
                                                                                          );
                                                                                      }}
                                                                                      className="text-white py-1 px-2 mb-0 rounded small bg-info"
                                                                                  >
                                                                                      {
                                                                                          "Pay at restaurant"
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
                                                                          {
                                                                              data.store_name
                                                                          }
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
                                                                      {
                                                                          "Paid Amount"
                                                                      }
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
                            ) : null}

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
                                                                  {data.status ==
                                                                  3
                                                                      ? "Canceled"
                                                                      : ""}
                                                                  {data.status ==
                                                                  4
                                                                      ? translation?.order_status_completed ||
                                                                        "Completed"
                                                                      : ""}
                                                                  {data.status ==
                                                                  5
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
                                                                      {
                                                                          data.store_name
                                                                      }
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

                            {/* {this.props.selectedOrder && (
                                <> */}
                            <PayForOrder
                                selectedOrder={this.props.selectedOrder}
                            />

                            <PayAtRestaurant
                                selectedOrder={this.props.selectedOrder}
                            />

                            <ShowOrder
                                order={this.props.selectedOrder}
                                currency={currency}
                            />
                            {/* </>
                            )} */}

                            <FooterBar
                                translation={translation}
                                active="orders"
                            />
                        </main>
                    ) : (
                        <main>
                            <div className="container">
                                <form
                                    action={`${
                                        ROUTE.ACCOUNT.ORDERS.PAGES.VIEW.PATH
                                    }/${
                                        this.state.phone ? this.state.phone : ""
                                    }`}
                                    method="get"
                                >
                                    <div className="input-group mt-3 rounded shadow-sm overflow-hidden bg-white">
                                        <div className="input-group-prepend">
                                            <button className="border-0 btn btn-outline-secondary text-success bg-white">
                                                <i className="icofont-search"></i>
                                            </button>
                                        </div>
                                        <input
                                            type="number"
                                            className="shadow-none border-0 form-control pl-0"
                                            name="phone"
                                            required
                                            value={this.state.phone}
                                            placeholder={`${translation?.menu_phone_number ||
                                                "Phone Number"}*`}
                                            onChange={this.onChange}
                                        />
                                    </div>
                                    <div
                                        className="text-center"
                                        style={{ marginTop: "20px" }}
                                    >
                                        <button
                                            type="submit"
                                            className="btn red-bg text-white btn-block btn-lg"
                                        >
                                            {translation?.menu_search_order ||
                                                "Search Order"}
                                        </button>
                                    </div>
                                </form>
                                <FooterBar
                                    translation={translation}
                                    active="orders"
                                />
                            </div>
                        </main>
                    )}
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
    userPhone: state.auth.userPhone
});

export default connect(mapSateToProps, {
    fetchOrders,
    callTheWaiter,
    selectOrderCheckout
})(Orders);
