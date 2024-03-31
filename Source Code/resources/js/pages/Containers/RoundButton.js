import React from "react";
import { connect } from "react-redux";

const RoundButton = props => {
    let { color, text } = props;

    let arrow = props.arrow ?? true;
    let arrowPosition = props.arrowPosition ?? "right";
    let customCss = props.css ?? {};

    let defaultCss = {
        backgroundColor: color ?? "red",
        borderRadius: 35
    };

    let buttonCss = { ...defaultCss, ...customCss };

    return (
        <div
            className="shadow d-flex align-items-center p-3 text-white text-center"
            style={buttonCss}
        >
            {arrow && arrowPosition == "left" && (
                <div className="ml-auto">
                    <i className="icofont-simple-left"></i>
                </div>
            )}
            <div className="more w-100">
                <h6 className="m-0">{text ?? "Click Here"}</h6>
            </div>
            {arrow && arrowPosition == "right" && (
                <div className="ml-auto">
                    <i className="icofont-simple-right"></i>
                </div>
            )}
        </div>
    );
};

const mapSateToProps = state => ({
    // account_info: state.store.account_info,
    // currency_location: state.store?.account_info?.currency_symbol_location
});

export default connect(mapSateToProps, {})(RoundButton);
