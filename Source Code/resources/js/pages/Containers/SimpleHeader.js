import React from "react";
import ROUTE from "../../config/route";
// import domain from "../../config/api/domain";
// import { Route } from "react-router-dom";
// import ReactDOM from "react-dom";

const SimpleHeader = props => {
    const path = props?.goBack ?? redirectToAccountView;

    function redirectToAccountView() {
        console.log(`working`);
        window.location.href = `${ROUTE.ACCOUNT.SHOW.PAGES.VIEW.PATH}`;
    }

    return (
        <div
            className={`p-3 border-bottom shadow-sm ${props.mb ??
                "mb-3"} ${props.bg ?? "bg-white"}`}
        >
            <div className="d-flex align-items-center">
                <a
                    onClick={path}
                    style={{
                        display: "block",
                        color: "#000",
                        textAlign: "center",
                        fontSize: "20px",
                        marginRight: "19px"
                    }}
                >
                    <i class="icofont-arrow-left"></i>
                </a>
                <h5 className="font-weight-bold m-0">
                    {props?.text ?? "Go Back"}
                </h5>
            </div>
        </div>
    );
};

export default SimpleHeader;
