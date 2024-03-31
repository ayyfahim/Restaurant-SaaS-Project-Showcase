import React from "react";
import ReactDOM from "react-dom";
import Loader from "./components/Loader";
import pages from "./pages";
import ROUTE from "./config/route";
import AuthRoute from "./config/route/PrivateRoute";
import NotificationSystem from "react-notification-system";
// import { getDistance, convertDistance } from "geolib";
import miscVariables from "./helpers/misc";
import {
    initiateGeoLocationWatcher,
    setCurrentPosition,
    positionError
} from "./helpers/geoLocation";

import {
    BrowserRouter as Router,
    Link,
    Route,
    Switch,
    withRouter
} from "react-router-dom";
import { Provider } from "react-redux";
import { addToCart, setCart } from "./actions/cartAction";
import { connect } from "react-redux";
import store from "./store";
import history from "./helpers/history";

var style = {
    NotificationItem: {
        // Override the notification item
        DefaultStyle: {
            // Applied to every notification, regardless of the notification level
            margin: "10px 5px 2px 1px",
            background: "#fff"
        },
        success: {
            color: "black"
        }
    }
};

class Page extends React.Component {
    notificationSystem = React.createRef();

    constructor(props) {
        super(props);
        this.state = {
            customerCoords: null
        };
    }

    componentWillMount() {
        // let cartData = localStorage.getItem('cartData')? JSON.parse(localStorage.getItem('cartData')) :null
        // if(cartData){
        //     this.props.setCart(cartData[0])
        // }
    }

    componentDidMount() {
        // console.log(domain.url)

        const reactNotification = this.notificationSystem.current;

        Echo.connector.options.auth.headers["Authorization"] =
            "Bearer " + this.props?.customer?.token;

        Echo.private(
            "App.Models.Customer." + this.props?.customer?.userId
        ).notification(notification => {
            console.log(`notification man`, notification);
            reactNotification.addNotification({
                message: notification?.message,
                level: notification?.success ? "success" : "error"
            });
        });

        navigator.geolocation.getCurrentPosition(
            position => {
                const getCoordsData = setCurrentPosition(
                    position,
                    this.props.store?.store_latitude,
                    this.props.store?.store_longitude
                );

                if (
                    this.props.store?.order_range <
                    getCoordsData?.metersAwayFromStore
                ) {
                    reactNotification.addNotification({
                        message: `You are outside the order range. You have to be ${this.props.store?.order_range}meter within to start your order`,
                        level: "error"
                    });

                    return;
                }
            },
            error => {
                const getError = positionError(error);

                reactNotification.addNotification({
                    message: getError,
                    level: "error"
                });

                return;
            },
            {
                enableHighAccuracy: false,
                timeout: 15000,
                maximumAge: 0
            }
        );

        // this.geoLocation();
        this.setGlobalTimer();
    }

    setGlobalTimer = () => {
        // if (globalTimerAppetizr) {
        //     if (globalTimerAppetizr == 0) {

        //     }
        // } else {
        //     localStorage.setItem("globalTimerAppetizr", 300000);
        // }

        setInterval(() => {
            let globalTimerAppetizr = localStorage.getItem(
                "globalTimerAppetizr"
            );

            if (!globalTimerAppetizr) {
                console.log("notimer");
                localStorage.setItem("globalTimerAppetizr", 3600000);
            }

            if (globalTimerAppetizr == 0) {
                const reactNotification = this.notificationSystem.current;

                if (this.userHasUnpaidOrders()) {
                    reactNotification.addNotification({
                        message: `Please pay for your orders.`,
                        level: "error"
                    });
                }

                // this.geoLocation();

                localStorage.setItem("globalTimerAppetizr", 3600000);

                return;
            }

            globalTimerAppetizr = localStorage.getItem("globalTimerAppetizr");

            localStorage.setItem(
                "globalTimerAppetizr",
                globalTimerAppetizr - 1000
            );
        }, 1000);
    };

    geoLocation = async () => {
        const reactNotification = this.notificationSystem.current;

        if (this.props?.store?.is_location_required) {
            await initiateGeoLocationWatcher(
                this.props.store?.store_latitude,
                this.props.store?.store_longitude,
                this.props.store?.order_range,
                this.detectGeoChanges
            )
                .then(geoLocationWatcher => {
                    // if (
                    //     this.props.store?.order_range <
                    //     geoLocationWatcher?.metersAwayFromStore
                    // ) {
                    //     reactNotification.addNotification({
                    //         message: `You are outside the order range. You have to be ${this.props.store?.order_range}meter within to start your order`,
                    //         level: "error"
                    //     });
                    // }
                    // console.log("geoLocationWatcher", geoLocationWatcher);
                })
                .catch(err => {
                    // console.log("err", err);

                    reactNotification.addNotification({
                        message: err,
                        level: "error"
                    });
                });

            // if (
            //     geoLocationWatcher &&
            //     geoLocationWatcher?.metersAwayFromStore &&
            //     this.props.store?.order_range <
            //         geoLocationWatcher?.metersAwayFromStore
            // ) {
            //     reactNotification.addNotification({
            //         message: `You are outside the order range. You have to be ${this.props.store?.order_range}meter within to start your order`,
            //         level: "error"
            //     });
            // }

            // console.log(
            //     "geoLocationWatcher",
            //     geoLocationWatcher,
            //     this.props.store?.order_range
            // );

            // console.log(
            //     "this.props.store?.order_range >= geoLocationWatcher.metersAwayFromStore",
            //     this.props.store?.order_range >=
            //         geoLocationWatcher.metersAwayFromStore
            // );
        }
    };

    detectGeoChanges = data => {
        // console.log("detectGeoChanges", data);
        // const reactNotification = this.notificationSystem.current;

        // console.log("data", data);

        if (500 < data?.metersAwayFromStore) {
            if (this.userHasUnpaidOrders()) {
                this.notifyForThisMinute(
                    "Please pay before you leave the zone",
                    "error"
                );
                // reactNotification.addNotification({
                //     message: `Please pay before you leave the zone`,
                //     level: "error"
                // });
            }
        }
    };

    notifyForThisMinute = (msg, type) => {
        const reactNotification = this.notificationSystem.current;

        reactNotification.addNotification({
            message: msg,
            level: type
        });
        // Notify user of things we should notify them of as of this minute
        // ...

        // Schedule next check for beginning of next minute; always wait
        // until we're a second into the minute to make the checks easier
        setTimeout(
            this.notifyForThisMinute(msg, type),
            (61 - new Date().getSeconds()) * 1000
        );
    };

    userHasUnpaidOrders = () => {
        let { orders } = this.props;

        if (orders?.length > 0) {
            let unpaidOrder = orders.find(data => data.is_paid == 0);

            if (unpaidOrder) {
                return true;
            }
        }

        return false;
    };

    render() {
        return (
            <>
                <NotificationSystem
                    ref={this.notificationSystem}
                    style={style}
                />

                <Router history={history}>
                    <Switch>
                        <Route
                            path={`${ROUTE.STORE.HOME.PAGES.VIEW.PATH}`}
                            exact
                            component={pages.HOME}
                        />
                        <Route
                            path={`${ROUTE.STORE.INDEX.PAGES.VIEW.PATH}/:id/:tableId?`}
                            exact
                            component={pages.STORE}
                        />
                        <Route
                            path={`${ROUTE.STORE.INDEX.PAGES.DETAILED.PATH}/:store_id/combo/details/:product_id`}
                            exact
                            component={pages.DETAILED_VIEW}
                        />
                        <Route
                            path={`${ROUTE.STORE.INDEX.PAGES.DETAILED.PATH}/:store_id/product/details/:product_id`}
                            exact
                            component={pages.DETAILED_VIEW}
                        />
                        <Route
                            path={`${ROUTE.STORE.INDEX.PAGES.DETAILED.PATH}/:store_id/category/details/:category_id`}
                            exact
                            component={pages.CATEGORY_DETAIL}
                        />
                        <AuthRoute
                            type="private"
                            path={`${ROUTE.STORE.Checkout.PAGES.Limonetik.PATH}`}
                            exact
                            component={pages.Checkout}
                        />
                        <Route
                            type="private"
                            path={`${ROUTE.STORE.INDEX.PAGES.CART.PATH}/:store_id/`}
                            exact
                            component={pages.CART}
                        />
                        <Route
                            type="private"
                            path={`${ROUTE.ACCOUNT.ORDERS.PAGES.VIEW.PATH}/:phone?`}
                            component={pages.ORDERS}
                        />
                        <Route
                            type="private"
                            path={`${ROUTE.ACCOUNT.TABLEORDERS.PAGES.VIEW.PATH}/:phone?`}
                            component={pages.TableOrders}
                        />
                        <AuthRoute
                            type="private"
                            path={`${ROUTE.ACCOUNT.SHOW.PAGES.VIEW.PATH}`}
                            component={pages.AccountView}
                        />
                        <AuthRoute
                            type="private"
                            path={`${ROUTE.ACCOUNT.EDIT.PAGES.VIEW.PATH}`}
                            component={pages.AccountEdit}
                        />
                        <AuthRoute
                            type="private"
                            path={`${ROUTE.ACCOUNT.LANGUAGE.PAGES.VIEW.PATH}`}
                            component={pages.AccountLanguage}
                        />
                        <AuthRoute
                            type="private"
                            path={`${ROUTE.ACCOUNT.ALLERGENS.PAGES.VIEW.PATH}`}
                            component={pages.AccountAllergens}
                        />
                        <AuthRoute
                            type="private"
                            path={`${ROUTE.ACCOUNT.RECEIPITS.PAGES.VIEW.PATH}`}
                            component={pages.RECEIPTS}
                        />
                        <AuthRoute
                            type="private"
                            path={`${ROUTE.ACCOUNT.PAY.PAGES.VIEW.PATH}`}
                            component={pages.PAY}
                        />
                        <AuthRoute
                            type="private"
                            path={`${ROUTE.ACCOUNT.ChangePassword.PAGES.VIEW.PATH}`}
                            component={pages.ChangePassword}
                        />
                        <AuthRoute
                            type="private"
                            path={`${ROUTE.ACCOUNT.CARD.PAGES.VIEW.PATH}`}
                            component={pages.CARD}
                        />
                        <AuthRoute
                            type="private"
                            path={`${ROUTE.ACCOUNT.SUPPORT.PAGES.VIEW.PATH}`}
                            component={pages.Support}
                        />
                        <AuthRoute
                            type="guest"
                            path={`${ROUTE.ACCOUNT.LOGIN.PAGES.VIEW.PATH}`}
                            component={pages.LOGIN}
                        />
                        <AuthRoute
                            type="guest"
                            path={`${ROUTE.ACCOUNT.REGISTER.PAGES.VIEW.PATH}`}
                            component={pages.REGISTER}
                        />
                        <AuthRoute
                            type="guest"
                            path={`${ROUTE.ACCOUNT.FORGOT.PAGES.VIEW.PATH}`}
                            component={pages.FORGOT}
                        />
                    </Switch>
                </Router>
            </>
        );
    }
}
const mapSateToProps = state => ({
    cart: state?.cart?.Items,
    customer: state?.auth,
    store: state?.store,
    orders: state.orders?.Orders
});

export default connect(mapSateToProps, { setCart })(Page);
