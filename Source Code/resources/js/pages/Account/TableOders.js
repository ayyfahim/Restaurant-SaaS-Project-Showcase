import React from "react";
import Moment from "moment";
import { connect } from "react-redux";
import { selectOrdersForCheckout } from "../../actions/checkoutAction";
import { fetchOrders, fetchTableOrders } from "../../actions/orderAction";
import { callTheWaiter } from "../../actions/storeAction";
import FooterBar from "../Containers/FooterBar";
import NotificationSystem from "react-notification-system";
import PriceRender from "../Containers/PriceRender";
import PayForOrder from "../Containers/PayForOrder";
import PayAtRestaurant from "../Containers/PayAtRestaurant";
import ShowOrder from "../Containers/ShowOrder";
import miscVariables from "../../helpers/misc";
import LinesEllipsis from "react-lines-ellipsis";
import SimpleHeader from "../Containers/SimpleHeader";

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

class TableOders extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            selectedOrder: null
        };
        this.onChange = this.onChange.bind(this);
    }
    notificationSystem = React.createRef();

    componentWillMount() {
        var data = {
            table_no: this.props?.selectedTable?.id
        };
        this.props.fetchTableOrders(data);

        let { selectedTableOrders } = this.props;
        let selectedOrder = selectedTableOrders?.filter(
            data => data.id == selectedTableOrders[0].id
        );
        this.props.selectOrdersForCheckout({ selectedOrders: selectedOrder });
    }

    componentDidMount() {
        let { selectedTableOrders } = this.props;
        let selectedOrder = selectedTableOrders?.filter(
            data => data.id == selectedTableOrders[0].id
        );
        this.props.selectOrdersForCheckout({ selectedOrders: selectedOrder });

        let data = {
            table_no: this.props?.selectedTable?.id
        };

        this.props.fetchTableOrders(data);
        this.timer = setInterval(
            () => this.props.fetchTableOrders(data),
            miscVariables.setTimeoutTimerForFetching
        );
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

                let data = orders.filter(
                    data => data.id == this.state.selectedOrder
                );

                this.props.selectOrdersForCheckout({ selectedOrders: data });

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
                let { selectedTableOrders } = this.props;

                let data = selectedTableOrders?.filter(
                    data => data.id == this.state.selectedOrder
                );

                this.props.selectOrdersForCheckout({ selectedOrders: data });

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

        let { selectedTable, selectedTableOrders } = this.props;

        if (!selectedTable) {
            return (
                <div className="fixed-bottom-padding">
                    <div className="p-3 py-5 border-bottom">
                        <div className="d-flex align-items-center justify-content-center">
                            <h5 className="font-weight-bold m-0">
                                Please scan a QR code first.
                            </h5>
                            <img
                                src="/images/icons/dashboard/qr_code_builder.png"
                                alt=""
                            />
                        </div>
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
                <div className="fixed-bottom-padding">
                    <SimpleHeader
                        text={"Table Orders"}
                        goBack={this.props.history.goBack}
                    />

                    <main>
                        {selectedTableOrders?.length > 0 ? (
                            <div className="order-body px-3 pt-3">
                                <h6 className="mb-2">{`Orders on table no: ${selectedTable?.table_number}`}</h6>
                                {selectedTableOrders
                                    ? selectedTableOrders
                                          ?.filter(
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
                                                                  <div className="col-6">
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
                                                                          {this.isOrderPaid(
                                                                              data
                                                                          )
                                                                              ? "(Paid)"
                                                                              : null}
                                                                      </p>
                                                                  </div>
                                                                  <div className="col-6">
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
                                                                  {data.is_paid !==
                                                                      1 &&
                                                                      !this.isOrderPaid(
                                                                          data
                                                                      ) && (
                                                                          <>
                                                                              <div className="col-6 mt-2">
                                                                                  <p
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
                                                                              <div className="col-6 mt-2">
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
                        ) : (
                            <div className="fixed-bottom-padding">
                                <div className="p-3 py-5 border-bottom">
                                    <div className="d-flex align-items-center">
                                        <h5 className="font-weight-bold m-0">
                                            No orders found
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        )}

                        <PayForOrder
                            selectedOrder={this.props.selectedOrders}
                        />

                        <PayAtRestaurant
                            selectedOrder={this.props.selectedOrders}
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
    // orders: state.orders.Orders,
    fetch: state.fetch,
    selectedOrder: state.checkout.order,
    selectedOrders: state.checkout.orders,
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
    selectOrdersForCheckout
})(TableOders);
