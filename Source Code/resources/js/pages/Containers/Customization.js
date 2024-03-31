import React, { useEffect, useRef, useState } from "react";
import ReactDOM from "react-dom";
import domain from "../../config/api/domain";
import { connect } from "react-redux";
import useDynamicRefs from "use-dynamic-refs";
import PriceRender from "./PriceRender";
import CustomizationTwo from "../Containers/BlockCustomization";
import { add } from "lodash";
const Customization = props => {
    let { addon, currency, translation } = props;
    const [extraAddon, setExtraAddon] = useState([]);
    const [Addon, setAddon] = useState([]);
    const [Choosenaddons, setChoosenaddons] = useState([]);

    const [getRef, setRef] = useDynamicRefs();
    let updateAddon = (id, type, index, product_id) => {
        let count = 0;
        switch (type) {
            case "ADD":
                count =
                    Number(
                        document.getElementById(
                            `addon-${id}-${index}-${product_id}`
                        ).value
                    ) + 1;
                break;
            case "SUB":
                count =
                    Number(
                        document.getElementById(
                            `addon-${id}-${index}-${product_id}`
                        ).value
                    ) - 1;
                break;
        }
        if (count >= 0) {
            document.getElementById(
                `addon-${id}-${index}-${product_id}`
            ).value = count;
            let extra = {
                addon_id: id,
                count: Number(
                    document.getElementById(
                        `addon-${id}-${index}-${product_id}`
                    ).value
                )
            };
            addExtraAddon(extra);
        }
    };

    const setAddonCheckbox = (id, type, addon_id) => {
        let newFind = Choosenaddons.find(cAddonId => cAddonId == addon_id);
        let cNewData = [];
        if (newFind) {
            let cNewData = Choosenaddons.filter(
                cAddonId => cAddonId != addon_id
            );
            setChoosenaddons([...cNewData, addon_id]);

            addon.map(singleAddon => {
                singleAddon.categories.map(singleAddonCategory => {
                    if (singleAddonCategory.id == addon_id) {
                        let singleAddonCategoryAddonIDs = [];

                        singleAddonCategory.addons.map(addon => {
                            singleAddonCategoryAddonIDs.push(addon.id);
                        });

                        console.log(
                            `singleAddonCategoryAddonIDs`,
                            singleAddonCategoryAddonIDs
                        );

                        let data = Addon.filter(
                            item => !singleAddonCategoryAddonIDs.includes(item)
                        );
                        console.log(`data`, data);

                        setAddon([...data, id]);

                        console.log(`Addon`, Addon);
                    }
                });
            });
        } else {
            setChoosenaddons([...Choosenaddons, addon_id]);
            setAddon([...Addon, id]);
        }
    };

    const addExtraAddon = extra => {
        let find = extraAddon.find(data => data.addon_id == extra.addon_id);
        let newData = [];
        if (find) {
            let newData = extraAddon.filter(
                item => item.addon_id != find.addon_id
            );
            // console.log(newData)
            setExtraAddon([...newData, extra]);
        } else setExtraAddon([...extraAddon, extra]);
    };

    let saveAddon = (product_id, type) => {
        let isAvailiable = props.isAvailiable;
        if (type == "SNG" && Addon == null) {
            let extra = extraAddon.filter(data => data.count != 0);
            props.SaveAddon(
                product_id,
                isAvailiable,
                addon[0]?.categories[0]?.addons[0]?.id,
                extra
            );
        } else {
            let extra = extraAddon.filter(data => data.count != 0);
            props.SaveAddon(product_id, isAvailiable, Addon, extra);
        }
    };

    const showSngAddon = data => {
        return (
            <>
                <div class="w-100 sng-btns" data-toggle="buttons">
                    {data.addons?.map((value, index) => (
                        <div class="mr-2">
                            <label
                                class={`btn btn-outline-secondary ${
                                    Addon.find(addonId => addonId == value.id)
                                        ? "active2"
                                        : ""
                                }`}
                            >
                                <input
                                    type="radio"
                                    name={`options-${index}`}
                                    onClick={() =>
                                        setAddonCheckbox(
                                            value.id,
                                            "SNG",
                                            data.id
                                        )
                                    }
                                    id={`${value.id}`}
                                />
                                {value.addon_name} -{" "}
                                <PriceRender
                                    currency={currency}
                                    price={value.price}
                                    showFree={true}
                                />
                            </label>
                        </div>
                        // {value.nested_addons?.map((nested_addon, index) =>
                        //     nested_addon.addon_category.type == "SNG"
                        //         ? showSngAddon(nested_addon.addon_category)
                        //         : showExtAddon(nested_addon.addon_category)
                        // )}
                    ))}
                </div>
            </>
        );
    };

    const showExtAddon = data => {
        return (
            <div>
                {data.addons?.map((value, index) => (
                    <>
                        <div class="cart-items bg-white position-relative">
                            <div class="d-flex align-items-center p-3 custom1-new">
                                <a href="#"></a>
                                <a class="ml-3 text-dark text-decoration-none w-100">
                                    <h5 class="mb-1">{value.addon_name}</h5>
                                    <div class="d-flex align-items-center md-3">
                                        <p class="total_price md-3 font-weight-bold m-0">
                                            <PriceRender
                                                currency={currency}
                                                price={value.price}
                                            />
                                        </p>
                                        <div className="md-3">
                                            <div class="input-group input-spinner  cart-items-number">
                                                <div class="input-group-append ">
                                                    <button
                                                        class="btn btn-success btn-sm"
                                                        type="button"
                                                        id="button-minus"
                                                        onClick={() =>
                                                            updateAddon(
                                                                value.id,
                                                                "SUB",
                                                                props.index,
                                                                addon[0]
                                                                    ?.product_id
                                                            )
                                                        }
                                                    >
                                                        {" "}
                                                        −{" "}
                                                    </button>
                                                </div>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    defaultValue={0}
                                                    ref={setRef(
                                                        `addon-${value.id}-${props.index}`
                                                    )}
                                                    id={`addon-${value.id}-${props.index}-${addon[0]?.product_id}`}
                                                    name={`extra-${props.index}`}
                                                />
                                                <div class="input-group-prepend">
                                                    <button
                                                        class="btn btn-success btn-sm"
                                                        onClick={() =>
                                                            updateAddon(
                                                                value.id,
                                                                "ADD",
                                                                props.index,
                                                                addon[0]
                                                                    ?.product_id
                                                            )
                                                        }
                                                        type="button"
                                                        id="button-plus"
                                                    >
                                                        {" "}
                                                        +{" "}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        {value?.nested_addons?.map((nested_addon, index) => (
                            <>
                                <p>{nested_addon.addon_category.type}</p>
                                {nested_addon.addon_category.type == "SNG"
                                    ? showSngAddon(nested_addon.addon_category)
                                    : showExtAddon(nested_addon.addon_category)}
                            </>
                        ))}
                    </>
                ))}
            </div>
        );
    };

    if (addon) {
        return (
            <div
                class="modal fade"
                id={`customization-${addon[0]?.product_id}-${props.index}`}
                tabindex="-1"
                role="dialog"
                aria-labelledby="exampleModalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">
                                {translation?.menu_customizations_text ||
                                    "Customization"}{" "}
                            </h5>
                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close"
                            >
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body pb-5">
                            <CustomizationTwo
                                index={props.index}
                                translation={translation}
                                currency={currency ? currency : "₹"}
                                SaveAddon={props.AddToCart}
                                addon={addon}
                                all_addons={props.all_addons}
                                all_addon_categories={
                                    props.all_addon_categories
                                }
                                isAvailiable={props.isAvailiable}
                                isCustomizable={props.isCustomizable}
                                AddToCart={props.AddToCart}
                                productID={props.productID}
                                notification={props.notification}
                                fromRecommendItem={true}
                            />
                        </div>

                        <div class="modal-footer p-0 border-0 fixed-bottom">
                            <div class="col-6 m-0 p-0">
                                {/* <button
                                    type="button"
                                    class="btn btn-dark btn-lg btn-block"
                                    data-dismiss="modal"
                                >
                                    {translation?.menu_close || "Close"}{" "}
                                </button> */}
                                <button
                                    style={{ visibility: "hidden" }}
                                    type="button"
                                    id={`#customization-${addon[0]?.product_id}-${props.index}`}
                                    class="btn btn-outline-success btn-sm ml-auto"
                                    data-toggle="modal"
                                    data-target={`#customization-${addon[0]?.product_id}-${props.index}`}
                                >
                                    Add
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    } else return null;
};
const mapSateToProps = state => ({
    cart: state.cart.Items,
    products: state.store.products,
    translation: state.translation?.active?.data
});

export default connect(mapSateToProps, {})(Customization);
