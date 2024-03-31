import ROUTE from "./index";
import React from "react";
import { connect } from "react-redux";
// import { Redirect, Route } from "react-router";
import GuestView from "../../pages/Account/GuestView";
import {
    BrowserRouter as Router,
    Link,
    Route,
    Switch,
    withRouter,
    Redirect
} from "react-router-dom";

const AuthRoute = props => {
    const { type, isAuthUser } = props;
    if (type === "guest" && isAuthUser)
        return (
            <Redirect
                to={`${
                    ROUTE.STORE.INDEX.PAGES.DETAILED.PATH
                }/${localStorage.getItem("storeId")}`}
            />
        );
    else if (type === "private" && !isAuthUser)
        return (
            <Route
                // path={`${ROUTE.STORE.HOME.PAGES.VIEW.PATH}`}
                exact
                component={GuestView}
            />
        );

    return <Route {...props} />;
};

const mapStateToProps = state => ({
    isAuthUser: state.auth.isLogin
});

export default connect(mapStateToProps)(AuthRoute);
