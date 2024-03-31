import React from "react";
import ReactDOM from "react-dom";
import ROUTE from "../../config/route";
import domain from "../../config/api/domain";
import { connect } from "react-redux";
import getSymbolFromCurrency from "currency-symbol-map";

const PriceRender = props => {
    let { currency_location, showFree, style } = props;
    let price = Number(props.price).toFixed(2);

    if (price == Number(0).toFixed(2) && showFree) {
        return <price>Free</price>;
        false;
    }

    if (currency_location == "right") {
        return (
            <price style={style ?? null}>
                {price} {getSymbolFromCurrency(props.currency)}
            </price>
        );
    } else {
        return (
            <price style={style ?? null}>
                {getSymbolFromCurrency(props.currency)} {price}
            </price>
        );
    }
};

const mapSateToProps = state => ({
    account_info: state.store.account_info,
    currency_location: state.store?.account_info?.currency_symbol_location
});

export default connect(mapSateToProps, {})(PriceRender);
