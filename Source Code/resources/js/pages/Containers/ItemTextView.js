import React, { useEffect, useState } from "react";
import ReactDOM from "react-dom";
import { addToCart } from "../../actions/cartAction";
import domain from "../../config/api/domain";
import Customization from "./Customization";
import PriceRender from "./PriceRender";
import LinesEllipsis from "react-lines-ellipsis";
import ReactTooltip from "react-tooltip";

const ItemTextView = props => {
    let {
        name,
        price,
        photo,
        id,
        description,
        currency,
        category_id,
        addon,
        allergens,
        customer_allergens,
        translation
    } = props;

    let addToCart = (id, isCustomizable, index, isAvailiable) => {
        if (!isCustomizable) props.AddToCart(id, isAvailiable);
        else {
            document
                .getElementById(`#customization-${id}-` + `${index}`)
                .click();
        }
    };

    const SaveAddon = (product_id, isAvailiable, addon_id, extra) => {
        props.AddToCart(product_id, isAvailiable, addon_id, extra);
    };

    const ifMatchingAllergen = () => {
        var result = allergens?.filter(function(o1) {
            return customer_allergens?.some(function(o2) {
                return o1.id === o2.id; // return the ones with equal id
            });
        });

        // console.log(`result ${name}`, result);

        return result;
    };

    const ifHasDietPreference = () => {
        return allergens.some(r => r.type == 2);
    };

    return (
        <div
            className="col-12 search"
            style={{ marginBottom: "20px" }}
            id={`category-${category_id}`}
            onClick={() => {
                window.location.href = props.more;
            }}
        >
            <ReactTooltip clickable={true} />
            <div className="row align-items-center justify-content-center">
                <div className="col-7 col-sm-5">
                    <h6
                        style={{
                            fontWeight: 600
                        }}
                    >
                        {name}
                    </h6>
                    <p
                        className="text-muted"
                        style={{
                            fontSize: 14
                        }}
                    >
                        <LinesEllipsis
                            text={description}
                            maxLine="2"
                            ellipsis="..."
                            trimRight
                            basedOn="letters"
                        />
                    </p>
                    {ifMatchingAllergen().length > 0 && (
                        <p className="text-danger">
                            <i class="icofont-warning"></i> Contains matching
                            allergens.
                        </p>
                    )}
                    {allergens && (
                        <div className="mb-3">
                            {allergens.map((data, index) => (
                                <>
                                    {data.type == 2 && (
                                        <img
                                            src={`${domain.url}/${data.active_image_url}`}
                                            alt={data.name}
                                            className="img-fluid"
                                            style={{
                                                width: 20
                                            }}
                                            data-tip={data.name}
                                        />
                                    )}
                                </>
                            ))}
                        </div>
                    )}
                    <h6 className="price m-0 text-success custom-color-red">
                        <PriceRender
                            currency={currency ? currency : "₹"}
                            price={price}
                        />
                    </h6>
                </div>
                <div className="col col-md-3 col-lg-2">
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
                                    // maxHeight: "80px",
                                    objectFit: "cover"
                                }}
                            />
                        </a>
                    </a>
                </div>
                <div className="col-12 mt-2">
                    <div style={{ borderBottom: "1px solid #e2e2e2" }}></div>
                </div>
            </div>
            {addon && addon != undefined ? (
                <Customization
                    index={props.index}
                    currency={currency ? currency : "₹"}
                    SaveAddon={SaveAddon}
                    addon={addon}
                    isAvailiable={props.isAvailiable}
                />
            ) : null}
        </div>
    );
};

export default ItemTextView;
