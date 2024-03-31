import React from "react";
import ReactDOM from "react-dom";
import domain from "../../config/api/domain";
import Customization from "./Customization";
import PriceRender from "./PriceRender";

const ItemCardView = props => {
    let {
        name,
        price,
        photo,
        id,
        description,
        currency,
        AddToCart,
        category_id,
        addon,
        translation,
        isAvailiable
    } = props;

    let addToCart = (id, isCustomizable) => {
        if (!isCustomizable) props.AddToCart(id);
        else {
            document.getElementById(`#customization-${id}`).click();
        }
    };
    const SaveAddon = (product_id, addon_id, extra) => {
        props.AddToCart(product_id, addon_id, extra);
    };
    return (
        <div>
            <div class="osahan-slider-item m-2">
                <div class="list-card h-100 overflow-hidden position-relative recommanded1">
                    <div class="list-card-osahan-2 ">
                        <a
                            href={props.more}
                            class="text-decoration-none text-dark"
                        >
                            <img
                                src={`${domain.s3_url}/${
                                    photo != null
                                        ? photo
                                        : "themes/default/images/all-img/empty.png"
                                }`}
                                class="recommanded1-bg"
                                style={{
                                    width: "100%",
                                    height: "180px",
                                    objectFit: "cover"
                                }}
                            />

                            <div class="p-3">
                                <div class="row">
                                    <div class="col">
                                        <h5
                                            style={{
                                                color: "#D30000"
                                            }}
                                        >
                                            {name}
                                        </h5>
                                    </div>
                                </div>
                                <div className="row">
                                    {/*<p class="text-gray mb-1 small">{description}</p>*/}
                                    <div class="col-auto pr-1">
                                        {addon && addon.length ? (
                                            <span className="mb-1 badge badge-danger">
                                                {translation?.menu_custom ||
                                                    "CUSTOMIZABLE"}{" "}
                                            </span>
                                        ) : (
                                            <h6 className="mb-1 font-weight-bold">
                                                <PriceRender
                                                    currency={
                                                        currency
                                                            ? currency
                                                            : "₹"
                                                    }
                                                    price={price}
                                                />
                                            </h6>
                                        )}
                                    </div>
                                    <div class="col-auto pl-1">
                                        {isAvailiable != undefined &&
                                        !isAvailiable ? (
                                            <span className="mb-1 badge badge-danger">
                                                {"Not Availiable"}{" "}
                                            </span>
                                        ) : null}
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    {/* {props.IsAddToEnable ?
            <div class="input-group ml-auto cart-items-number">
            <div class="input-group-prepend">

            <div class="input-group-append">
                                    <button onClick={() => addToCart(id,addon && addon.length)} class="btn btn-success btn-sm" type="button" > + </button>
                                </div>
                                </div>
                                </div>
            :null} */}
                </div>
            </div>
            {/*<Customization currency={currency ? currency : "₹"} SaveAddon={SaveAddon} addon={addon} />*/}
        </div>
    );
};
export default ItemCardView;
