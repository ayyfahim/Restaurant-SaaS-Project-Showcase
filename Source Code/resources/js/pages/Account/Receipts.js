import React from "react";
import ReactDOM from "react-dom";
import Header from "../../components/Header";
import SideBar from "../../components/SideBar";
import { NavLink, Route } from "react-router-dom";
import { connect } from "react-redux";
import domain from "../../config/api/domain";
import ROUTE from "../../config/route";
import FooterBar from "../Containers/FooterBar";
import Invoice from "../Containers/Invoice";
import NotificationSystem from "react-notification-system";
import { fetchAllergens, addAllergens } from "../../actions/authAction";
import SimpleHeader from "../Containers/SimpleHeader";
import { selectOrderCheckout } from "../../actions/checkoutAction";
import { fetchOrders } from "../../actions/orderAction";

let storeId = null;

class Receipts extends React.Component {
    notificationSystem = React.createRef();
    constructor(props) {
        super(props);
        this.state = {
            is_loading: false,
            errors: [],
            fetch_message: null
        };
    }

    componentWillMount() {
        let { auth, orders } = this.props;
        if (auth.isLogin) {
            var data = {
                table_no: null
            };

            this.props.fetchOrders(data);
            let selectedOrder = {
                selectedOrder: orders.filter(data => data.id == orders[0].id)
            };
            this.props.selectOrderCheckout(selectedOrder);
        }
    }

    isOrderPaid(data) {
        return Number(data.paid_amount) >= Number(data.total);
    }

    render() {
        let {
            translation,
            auth,
            orders,
            account_info,
            store_info
        } = this.props;
        let currency = account_info ? account_info.currency_symbol : "USD";
        return (
            <div>
                <NotificationSystem ref={this.notificationSystem} />
                <div
                    className="fixed-bottom-padding bg-light-gray-2 h-100-vh"
                    style={{
                        paddingBottom: 200
                    }}
                >
                    <SimpleHeader
                        text={"Receipts"}
                        goBack={this.props.history.goBack}
                    />

                    <div className="container">
                        {orders.map(
                            order =>
                                order.is_paid !== 0 &&
                                this.isOrderPaid(order) && (
                                    <Invoice
                                        order={order}
                                        currency={currency}
                                        account_info={account_info}
                                        store_info={store_info}
                                    />
                                )
                        )}
                    </div>

                    <FooterBar translation={translation} />
                </div>
            </div>
        );
    }
}

const mapSateToProps = state => ({
    auth: state?.auth,
    orders: state?.orders?.Orders,
    translation: state?.translation?.active?.data,
    account_info: state?.store?.account_info,
    store_info: state?.store
});

export default connect(mapSateToProps, { selectOrderCheckout, fetchOrders })(
    Receipts
);
