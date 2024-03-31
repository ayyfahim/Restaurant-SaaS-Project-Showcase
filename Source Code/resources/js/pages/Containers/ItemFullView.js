import React, { useEffect, useState } from "react";
import ReactDOM from "react-dom";
import { addToCart } from "../../actions/cartAction";
import domain from "../../config/api/domain";
import Customization from "./Customization";
import PriceRender from "./PriceRender";
const ItemFullView = props => {
    let {
        name,
        price,
        photo,
        id,
        description,
        currency,
        category_id,
        addon,
        translation
    } = props;
    let addToCart = (id, isCustomizable, index, isAvailiable) => {
        if (!isCustomizable)
            props.AddToCart(id, isAvailiable, null, null, {}, false);
        else {
            console.log(
                "customization name",
                `#customization-${id}-` + `${index}`
            );

            document
                .getElementById(`#customization-${id}-` + `${index}`)
                .click();
        }
    };
    const SaveAddon = (product_id, isAvailiable, addon_id, extra) => {
        props.AddToCart(product_id, isAvailiable, addon_id, extra);
    };

    return (
        <div
            className="col-6 search"
            style={{ marginBottom: "15px" }}
            id={`category-${category_id}`}
        >
            <div className="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                <div className="list-card-image">
                    {props.IsRecommended ? (
                        <div className="member-plan position-absolute">
                            <span
                                className="badge m-2 badge-warning bg-info"
                                style={{ color: "#fff" }}
                            >
                                {" "}
                                {translation?.menu_rec || "REC"}{" "}
                            </span>
                        </div>
                    ) : null}
                    <div className="p-3">
                        <a className="text-dark">
                            <a href={props.more}>
                                <img
                                    src={`${domain.s3_url}/${
                                        photo != null
                                            ? photo
                                            : "themes/default/images/all-img/empty.png"
                                    }`}
                                    className="img-fluid item-img w-100 mb-3"
                                    style={{
                                        width: "100%",
                                        height: "110px",
                                        objectFit: "cover"
                                    }}
                                />
                            </a>
                            <h6>{name}</h6>
                        </a>
                        <div className="d-flex align-items-center">
                            <a href={props.more} className="text-dark">
                                {addon && addon.length ? (
                                    <span className="badge badge-danger">
                                        {" "}
                                        {translation?.menu_custom ||
                                            "CUSTOM"}{" "}
                                    </span>
                                ) : (
                                    <h6 className="price m-0 text-success">
                                        <PriceRender
                                            currency={currency ? currency : "₹"}
                                            price={price}
                                        />
                                    </h6>
                                )}
                            </a>
                            {props.isAvailiable != undefined &&
                            !props.isAvailiable ? (
                                <a href={"#"} className="text-dark ml-2">
                                    <span className="badge badge-danger">
                                        {" "}
                                        {"Not Availiable"}{" "}
                                    </span>
                                </a>
                            ) : null}
                            {props.IsAddToEnable ? (
                                <a
                                    onClick={() =>
                                        addToCart(
                                            id,
                                            addon && addon.length,
                                            props.index,
                                            props.isAvailiable
                                        )
                                    }
                                    className="btn btn-success btn-sm ml-auto"
                                >
                                    +
                                </a>
                            ) : null}
                        </div>
                    </div>
                </div>
            </div>
            {addon && addon != undefined ? (
                <Customization
                    index={props.index}
                    currency={currency ? currency : "₹"}
                    SaveAddon={SaveAddon}
                    addon={addon}
                    newAddon={props.newAddon}
                    isAvailiable={props.isAvailiable}
                    all_addons={props.all_addons}
                    all_addon_categories={props.all_addon_categories}
                    isCustomizable={props.isCustomizable}
                    AddToCart={props.AddToCart}
                    productID={props.productID}
                    notification={props.notification}
                    customizationId={id}
                    // index={props.index}
                    // currency={currency ? currency : "₹"}
                    // SaveAddon={SaveAddon}
                    // addon={addon}
                />
            ) : null}
        </div>
    );
};

export default ItemFullView;
