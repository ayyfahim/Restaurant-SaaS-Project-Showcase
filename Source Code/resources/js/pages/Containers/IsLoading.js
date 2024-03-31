import React from "react";
import ReactDOM from "react-dom";
import ROUTE from "../../config/route";
import domain from "../../config/api/domain";
import { connect } from "react-redux";

const IsLoading = props => {
    let { style, is_loading } = props;

    if (is_loading) {
        return (
            <div
                style={{
                    textAlign: "center",
                    position: "fixed",
                    width: "100%",
                    height: "100%",
                    top: "0",
                    left: "0",
                    zIndex: "100",
                    backgroundColor: "rgb(144 144 144 / 60%)"
                }}
            >
                <div
                    style={{
                        position: "relative",
                        width: "100%",
                        height: "100%"
                    }}
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        xmlnsXlink="http://www.w3.org/1999/xlink"
                        style={{
                            margin: "auto",
                            // backgroundColor: "rgb(255, 255, 255)",
                            backgroundColor: "transparent",
                            display: "block",
                            shapeRendering: "auto",
                            position: "absolute",
                            // width: "100%",
                            // height: "100%",
                            top: "50%",
                            left: "50%",
                            zIndex: "100",
                            transform: "translate(-50%, -50%)"
                        }}
                        width="200px"
                        height="200px"
                        viewBox="0 0 100 100"
                        preserveAspectRatio="xMidYMid"
                    >
                        <circle cx="84" cy="50" r="10" fill="#ff0000">
                            <animate
                                attributeName="r"
                                repeatCount="indefinite"
                                dur="0.25s"
                                calcMode="spline"
                                keyTimes="0;1"
                                values="10;0"
                                keySplines="0 0.5 0.5 1"
                                begin="0s"
                            ></animate>
                            <animate
                                attributeName="fill"
                                repeatCount="indefinite"
                                dur="1s"
                                calcMode="discrete"
                                keyTimes="0;0.25;0.5;0.75;1"
                                values="#ff0000;#000000;#00ff05;#fff500;#ff0000"
                                begin="0s"
                            ></animate>
                        </circle>
                        <circle cx="16" cy="50" r="10" fill="#ff0000">
                            <animate
                                attributeName="r"
                                repeatCount="indefinite"
                                dur="1s"
                                calcMode="spline"
                                keyTimes="0;0.25;0.5;0.75;1"
                                values="0;0;10;10;10"
                                keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1"
                                begin="0s"
                            ></animate>
                            <animate
                                attributeName="cx"
                                repeatCount="indefinite"
                                dur="1s"
                                calcMode="spline"
                                keyTimes="0;0.25;0.5;0.75;1"
                                values="16;16;16;50;84"
                                keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1"
                                begin="0s"
                            ></animate>
                        </circle>
                        <circle cx="50" cy="50" r="10" fill="#fff500">
                            <animate
                                attributeName="r"
                                repeatCount="indefinite"
                                dur="1s"
                                calcMode="spline"
                                keyTimes="0;0.25;0.5;0.75;1"
                                values="0;0;10;10;10"
                                keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1"
                                begin="-0.25s"
                            ></animate>
                            <animate
                                attributeName="cx"
                                repeatCount="indefinite"
                                dur="1s"
                                calcMode="spline"
                                keyTimes="0;0.25;0.5;0.75;1"
                                values="16;16;16;50;84"
                                keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1"
                                begin="-0.25s"
                            ></animate>
                        </circle>
                        <circle cx="84" cy="50" r="10" fill="#00ff05">
                            <animate
                                attributeName="r"
                                repeatCount="indefinite"
                                dur="1s"
                                calcMode="spline"
                                keyTimes="0;0.25;0.5;0.75;1"
                                values="0;0;10;10;10"
                                keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1"
                                begin="-0.5s"
                            ></animate>
                            <animate
                                attributeName="cx"
                                repeatCount="indefinite"
                                dur="1s"
                                calcMode="spline"
                                keyTimes="0;0.25;0.5;0.75;1"
                                values="16;16;16;50;84"
                                keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1"
                                begin="-0.5s"
                            ></animate>
                        </circle>
                        <circle cx="16" cy="50" r="10" fill="#000000">
                            <animate
                                attributeName="r"
                                repeatCount="indefinite"
                                dur="1s"
                                calcMode="spline"
                                keyTimes="0;0.25;0.5;0.75;1"
                                values="0;0;10;10;10"
                                keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1"
                                begin="-0.75s"
                            ></animate>
                            <animate
                                attributeName="cx"
                                repeatCount="indefinite"
                                dur="1s"
                                calcMode="spline"
                                keyTimes="0;0.25;0.5;0.75;1"
                                values="16;16;16;50;84"
                                keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1"
                                begin="-0.75s"
                            ></animate>
                        </circle>
                    </svg>
                </div>
            </div>
        );
    } else {
        return null;
    }
};

const mapSateToProps = state => ({
    // account_info: state.store.account_info,
    // currency_location: state.store?.account_info?.currency_symbol_location
});

export default connect(mapSateToProps, {})(IsLoading);
