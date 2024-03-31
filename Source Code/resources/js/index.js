import React from "react";
import ReactDOM from "react-dom";
import { disableReactDevTools } from "@fvilers/disable-react-devtools";
import Loader from "./components/Loader";
import pages from "./pages";
import Page from "./route";
import { BrowserRouter as Router, Link, Route, Switch } from "react-router-dom";
import { Provider } from "react-redux";
import { addToCart, setCart } from "./actions/cartAction";
import { refreshUser } from "./actions/authAction";
import { connect } from "react-redux";
import store from "./store";
import domain from "./config/api/domain";

class Root extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            isLogin: false
        };
    }

    componentWillMount() {
        // console.log(domain.url)
    }

    // componentDidMount() {
    //     this.timer = setInterval(() => this.props.refreshUser(), 5000);
    // }

    render() {
        return (
            <Provider store={store}>
                <Page />
            </Provider>
        );
    }
}
const mapSateToProps = state => ({
    // cart: state.cart.Items
});

if (process.env.NODE_ENV === "production") {
    disableReactDevTools();
}

// export default connect(mapSateToProps, {
//     refreshUser
// })(Root);

export default Root;

if (document.getElementById("root")) {
    ReactDOM.render(<Root />, document.getElementById("root"));
}
