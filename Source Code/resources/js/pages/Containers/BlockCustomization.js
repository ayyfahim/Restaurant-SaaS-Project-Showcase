import React, { useEffect, useRef, useState } from "react";
import ReactDOM from "react-dom";
import domain from "../../config/api/domain";
import { connect } from "react-redux";
import useDynamicRefs from "use-dynamic-refs";
import PriceRender from "./PriceRender";
import { add } from "lodash";
import Expand from "react-expand-animated";

const BlockCustomization = props => {
    let {
        addon,
        currency,
        translation,
        isCustomizable,
        notification,
        all_addons,
        all_addon_categories,
        fromRecommendItem
    } = props;

    let mainIndex = props.index;

    // For Product Addons
    const [extraAddon, setExtraAddon] = useState([]);
    const [Addon, setAddon] = useState([]);
    const [Choosenaddons, setChoosenaddons] = useState([]);

    // For Nested Addons
    const [extraNestedAddon, setExtraNestedAddon] = useState([]);
    const [NestedAddon, setNestedAddon] = useState([]);
    const [ChoosenNestedaddons, setChoosenNestedaddons] = useState([]);

    const [getRef, setRef] = useDynamicRefs();

    let updateAddon = (id, type, index, product_id, addon_category_id) => {
        let count = 0;

        let getAddonElementById = document.getElementById(
            `addon-${id}-${index}-${product_id}`
        );

        if (fromRecommendItem) {
            getAddonElementById = document.getElementById(
                `rcmnd${mainIndex}-addon-${id}-${index}-${product_id}`
            );
        }

        switch (type) {
            case "ADD":
                count = Number(getAddonElementById.value) + 1;
                break;
            case "SUB":
                count = Number(getAddonElementById.value) - 1;
                break;
        }

        if (type == "ADD") {
            // Check if the addon group maximum limit exceeded

            // let addonItem = all_addons.find(
            //     addon_item => addon_item.addon_category_id == addon_category_id
            // );

            let addonCategory = all_addon_categories.find(
                item => item.id == addon_category_id
            );

            let addonCategoryAddons = addonCategory.addons;

            let addonIDs = Array.from(addonCategoryAddons, i => i.id);
            // console.log(`addonIDs`, addonIDs);

            let result = extraAddon.filter(extra =>
                addonIDs.some(id => id === extra.addon_id)
            );

            let totalCountOfAddons = null;

            if (result.length > 0) {
                let totalCountOfAddons = result.reduce(
                    (a, b) => +a + +b.count,
                    0
                );

                if (
                    addonCategory.maximum_amount &&
                    totalCountOfAddons >= addonCategory.maximum_amount
                ) {
                    notification.addNotification({
                        message: `You can choose maximum amount of ${addonCategory.maximum_amount} for ${addonCategory.name}`,
                        level: "error"
                    });
                    return;
                }
            }
        }

        if (count >= 0) {
            getAddonElementById.value = count;
            let extra = {
                addon_id: id,
                count: Number(getAddonElementById.value),
                addon_category_id: addon_category_id
            };
            addExtraAddon(extra);
        }
    };

    let updateNestedAddon = (
        id,
        type,
        index,
        product_id,
        addon_category_id,
        parentAddonId
    ) => {
        let count = 0;
        let getAddonElementById = document.getElementById(
            `addon-${id}-${index}-${product_id}-${parentAddonId}`
        );
        if (fromRecommendItem) {
            getAddonElementById = document.getElementById(
                `rcmnd${mainIndex}-addon-${id}-${index}-${product_id}-${parentAddonId}`
            );
        }
        switch (type) {
            case "ADD":
                count = Number(getAddonElementById.value) + 1;
                break;
            case "SUB":
                count = Number(getAddonElementById.value) - 1;
                break;
        }

        if (type == "ADD") {
            // Check if the addon group maximum limit exceeded

            // let addonItem = all_addons.find(
            //     addon_item => addon_item.addon_category_id == addon_category_id
            // );

            let addonCategory = all_addon_categories.find(
                item => item.id == addon_category_id
            );

            let addonCategoryAddons = addonCategory.addons;

            let addonIDs = Array.from(addonCategoryAddons, i => i.id);
            // console.log(`addonIDs`, addonIDs);

            let result = extraNestedAddon.filter(
                extra =>
                    addonIDs.some(id => id === extra.addon_id) &&
                    extra.parentAddonId == parentAddonId
            );

            let totalCountOfAddons = null;

            if (result.length > 0) {
                let totalCountOfAddons = result.reduce(
                    (a, b) => +a + +b.count,
                    0
                );

                if (
                    addonCategory.maximum_amount &&
                    totalCountOfAddons >= addonCategory.maximum_amount
                ) {
                    notification.addNotification({
                        message: `You can choose maximum amount of ${addonCategory.maximum_amount} for ${addonCategory.name}`,
                        level: "error"
                    });
                    return;
                }
            }
        }

        if (count >= 0) {
            getAddonElementById.value = count;
            let extra = {
                addon_id: id,
                count: Number(getAddonElementById.value),
                addon_category_id: addon_category_id,
                parentAddonId: parentAddonId
            };
            addExtraNestedAddon(extra, parentAddonId);
        }
    };

    const checkMaxAddon = () => {
        let allAddons = addon.map(item => {
            return item.categories[0];
        });

        let count;

        let returnValue = allAddons.every(addonCategory => {
            if (
                addonCategory.type == "EXT" &&
                addonCategory.minimum_amount != 0 &&
                addonCategory.minimum_amount != null
            ) {
                let xExtraAddon = extraAddon?.filter(
                    item =>
                        item?.addon_category_id &&
                        item?.addon_category_id == addonCategory.id
                );

                // console.log("xExtraAddon", xExtraAddon, !xExtraAddon);

                if (!xExtraAddon.length) {
                    notification.addNotification({
                        message: `You have to choose minimum amount of ${addonCategory.minimum_amount} for ${addonCategory.name}`,
                        level: "error"
                    });
                    return false;
                }

                if (xExtraAddon) {
                    count = xExtraAddon.reduce((a, b) => +a + +b.count, 0);

                    if (count && count < addonCategory.minimum_amount) {
                        notification.addNotification({
                            message: `You have to choose minimum amount of ${addonCategory.minimum_amount} for ${addonCategory.name}`,
                            level: "error"
                        });
                        return false;
                    }
                }
            }

            if (addonCategory.type == "SNG") {
                let getAddonCategory = all_addon_categories.find(
                    item => item.id == addonCategory.id
                );

                let addonCategoryAddons = getAddonCategory.addons;

                let addonIDs = Array.from(addonCategoryAddons, i => i.id);

                let result = Addon.filter(extra =>
                    addonIDs.some(id => id === extra)
                );

                count = result.length;

                if (count < addonCategory.minimum_amount) {
                    notification.addNotification({
                        message: `You have to choose minimum amount of ${addonCategory.minimum_amount} for ${addonCategory.name}`,
                        level: "error"
                    });
                    return false;
                }
            }

            return true;
        });

        if (!returnValue) {
            return returnValue;
        }

        returnValue = all_addons.every(addon => {
            let result = Addon.find(
                addonId =>
                    // addonIDs.some(id => id === extra)
                    addonId == addon.id
            );

            if (result) {
                if (addon.nested_addons.length > 0) {
                    let returnValue2 = addon.nested_addons.every(
                        nestedAddon => {
                            if (nestedAddon.addon_category.type == "SNG") {
                                let getAddonCategory = all_addon_categories.find(
                                    item =>
                                        item.id == nestedAddon.addon_category.id
                                );

                                let addonCategoryAddons =
                                    getAddonCategory.addons;

                                let addonIDs = Array.from(
                                    addonCategoryAddons,
                                    i => i.id
                                );

                                let result = NestedAddon.filter(addon =>
                                    addonIDs.some(
                                        addonId => addonId === addon.addon_id
                                    )
                                );

                                count = result.length;
                                nestedAddon.addon_category;

                                if (
                                    count <
                                    nestedAddon.addon_category.minimum_amount
                                ) {
                                    notification.addNotification({
                                        message: `You have to choose minimum amount of ${nestedAddon.addon_category.minimum_amount} for ${nestedAddon.addon_category.name}`,
                                        level: "error"
                                    });
                                    return false;
                                }
                            }

                            if (nestedAddon.addon_category.type == "EXT") {
                                let xExtraAddon = extraNestedAddon?.filter(
                                    item =>
                                        item?.addon_category_id &&
                                        item?.addon_category_id ==
                                            nestedAddon.addon_category.id
                                );

                                // console.log("xExtraAddon", xExtraAddon, !xExtraAddon);

                                if (!xExtraAddon.length) {
                                    notification.addNotification({
                                        message: `You have to choose minimum amount of ${nestedAddon.addon_category.minimum_amount} for ${nestedAddon.addon_category.name}`,
                                        level: "error"
                                    });
                                    return false;
                                }

                                if (xExtraAddon) {
                                    count = xExtraAddon.reduce(
                                        (a, b) => +a + +b.count,
                                        0
                                    );

                                    if (
                                        count &&
                                        count <
                                            nestedAddon.addon_category
                                                .minimum_amount
                                    ) {
                                        notification.addNotification({
                                            message: `You have to choose minimum amount of ${nestedAddon.addon_category.minimum_amount} for ${nestedAddon.addon_category.name}`,
                                            level: "error"
                                        });
                                        return false;
                                    }
                                }
                            }

                            return true;
                        }
                    );

                    return returnValue2;
                }
            }

            result = extraAddon?.filter(item => item?.addon_id == addon.id);

            if (result.length > 0) {
                if (addon.nested_addons.length > 0) {
                    let returnValue2 = addon.nested_addons.every(
                        nestedAddon => {
                            if (nestedAddon.addon_category.type == "SNG") {
                                let getAddonCategory = all_addon_categories.find(
                                    item =>
                                        item.id == nestedAddon.addon_category.id
                                );

                                let addonCategoryAddons =
                                    getAddonCategory.addons;

                                let addonIDs = Array.from(
                                    addonCategoryAddons,
                                    i => i.id
                                );

                                let result = NestedAddon.filter(addon =>
                                    addonIDs.some(
                                        addonId => addonId === addon.addon_id
                                    )
                                );

                                count = result.length;
                                nestedAddon.addon_category;

                                if (
                                    count <
                                    nestedAddon.addon_category.minimum_amount
                                ) {
                                    notification.addNotification({
                                        message: `You have to choose minimum amount of ${nestedAddon.addon_category.minimum_amount} for ${nestedAddon.addon_category.name}`,
                                        level: "error"
                                    });
                                    return false;
                                }
                            }

                            if (nestedAddon.addon_category.type == "EXT") {
                                let xExtraAddon = extraNestedAddon?.filter(
                                    item =>
                                        item?.addon_category_id &&
                                        item?.addon_category_id ==
                                            nestedAddon.addon_category.id
                                );

                                // console.log("xExtraAddon", xExtraAddon, !xExtraAddon);

                                if (!xExtraAddon.length) {
                                    notification.addNotification({
                                        message: `You have to choose minimum amount of ${nestedAddon.addon_category.minimum_amount} for ${nestedAddon.addon_category.name}`,
                                        level: "error"
                                    });
                                    return false;
                                }

                                if (xExtraAddon) {
                                    count = xExtraAddon.reduce(
                                        (a, b) => +a + +b.count,
                                        0
                                    );

                                    if (
                                        count &&
                                        count <
                                            nestedAddon.addon_category
                                                .minimum_amount
                                    ) {
                                        notification.addNotification({
                                            message: `You have to choose minimum amount of ${nestedAddon.addon_category.minimum_amount} for ${nestedAddon.addon_category.name}`,
                                            level: "error"
                                        });
                                        return false;
                                    }
                                }
                            }

                            return true;
                        }
                    );

                    return returnValue2;
                }
            }

            return true;
        });

        return returnValue;

        // console.log("returnValue", returnValue);

        // let getAllAddons = all_addons.map(item => {
        //     return item.categories[0];
        // });
    };

    const setAddonCheckbox = (id, type, addon_id) => {
        let newFind = Choosenaddons.find(cAddonId => cAddonId == addon_id);
        let cNewData = [];
        if (newFind) {
            let cNewData = Choosenaddons.filter(
                cAddonId => cAddonId != addon_id
            );
            setChoosenaddons([...cNewData]);

            all_addon_categories.map(singleAddonCategory => {
                if (singleAddonCategory.id == addon_id) {
                    let singleAddonCategoryAddonIDs = [];

                    singleAddonCategory.addons.map(addon => {
                        singleAddonCategoryAddonIDs.push(addon.id);
                    });

                    let data = Addon.filter(
                        item => !singleAddonCategoryAddonIDs.includes(item)
                    );

                    setAddon([...data]);
                }
            });
        } else {
            setChoosenaddons([...Choosenaddons, addon_id]);
            setAddon([...Addon, id]);
        }
    };

    const setAddonCheckboxMulti = (id, type, add_cat_id) => {
        let addonCategory = all_addon_categories.find(
            item => item.id == add_cat_id
        );
        // console.log("first", addonCategory, add_cat_id);

        let addonCategoryAddons = addonCategory.addons;

        let addonIDs = Array.from(addonCategoryAddons, i => i.id);
        // console.log(`addonIDs`, addonIDs);

        let result = Addon.filter(extra =>
            addonIDs.some(addonId => addonId === extra && extra !== id)
        );
        // console.log("result.length", result.length);

        let totalCountOfAddons = null;

        if (result.length > 0) {
            let totalCountOfAddons = result.length;

            if (
                addonCategory.maximum_amount &&
                totalCountOfAddons >= addonCategory.maximum_amount
            ) {
                notification.addNotification({
                    message: `You can choose maximum amount of ${addonCategory.maximum_amount} for ${addonCategory.name}`,
                    level: "error"
                });
                return;
            }
        }

        let find = Addon.find(addonId => addonId == id);
        let newData = [];
        if (find) {
            let newData = Addon.filter(addonId => addonId != id);
            setAddon([...newData]);
        } else setAddon([...Addon, id]);
    };

    const setNestedAddonCheckbox = (id, type, addon_id, parentAddonId) => {
        let newFind = ChoosenNestedaddons.find(
            cAddonId =>
                cAddonId.addon_category_id == addon_id &&
                cAddonId.parentAddonId == parentAddonId
        );

        // console.log(`newFind`, newFind, addon_id);
        let cNewData = [];
        if (newFind) {
            let cNewData = ChoosenNestedaddons.filter(cAddon => {
                if (
                    cAddon.addon_category_id == addon_id &&
                    cAddon.parentAddonId == parentAddonId
                ) {
                    return false;
                }

                return true;
            });
            // console.log(`cNewData`, cNewData);
            setChoosenNestedaddons([...cNewData]);

            all_addon_categories.map(singleAddonCategory => {
                if (singleAddonCategory.id == addon_id) {
                    let singleAddonCategoryAddonIDs = [];

                    singleAddonCategory.addons.map(addon => {
                        singleAddonCategoryAddonIDs.push(addon.id);
                    });

                    let result = NestedAddon.filter(item => {
                        if (
                            singleAddonCategoryAddonIDs.includes(
                                item.addon_id
                            ) &&
                            item.parentAddonId == parentAddonId
                        ) {
                            return false;
                        }
                        return true;
                    });

                    setNestedAddon([...result]);
                }
            });
        } else {
            let nestedDataForAddonCategory = {
                addon_category_id: addon_id,
                parentAddonId: parentAddonId
            };

            let nestedData = {
                addon_id: id,
                parentAddonId: parentAddonId
            };

            setChoosenNestedaddons([
                ...ChoosenNestedaddons,
                nestedDataForAddonCategory
            ]);
            setNestedAddon([...NestedAddon, nestedData]);
        }
    };

    const setNestedAddonCheckboxMulti = (id, type, addon_id, parentAddonId) => {
        let addonCategory = all_addon_categories.find(
            item => item.id == addon_id
        );
        // console.log("first", addonCategory, add_cat_id);

        let addonCategoryAddons = addonCategory.addons;

        let addonIDs = Array.from(addonCategoryAddons, i => i.id);
        // console.log(`addonIDs`, addonIDs);

        let result = NestedAddon.filter(extra =>
            addonIDs.some(
                addonId => addonId === extra.addon_id && extra.addon_id !== id
            )
        );
        // console.log("result.length", result.length);

        let totalCountOfAddons = null;

        if (result.length > 0) {
            let totalCountOfAddons = result.length;

            if (
                addonCategory.maximum_amount &&
                totalCountOfAddons >= addonCategory.maximum_amount
            ) {
                notification.addNotification({
                    message: `You can choose maximum amount of ${addonCategory.maximum_amount} for ${addonCategory.name}`,
                    level: "error"
                });
                return;
            }
        }

        let newFind = ChoosenNestedaddons.find(
            cAddonId =>
                cAddonId.addon_category_id == addon_id &&
                cAddonId.parentAddonId == parentAddonId
        );
        let newData = [];
        if (newFind) {
            let cNewData = ChoosenNestedaddons.filter(cAddon => {
                if (
                    cAddon.addon_category_id == addon_id &&
                    cAddon.parentAddonId == parentAddonId
                ) {
                    return false;
                }

                return true;
            });
            // console.log(`cNewData`, cNewData);
            setChoosenNestedaddons([...cNewData]);

            let result = NestedAddon.filter(item => {
                if (
                    item.addon_id == id &&
                    item.parentAddonId == parentAddonId
                ) {
                    return false;
                }
                return true;
            });

            setNestedAddon([...result]);
        } else {
            let nestedDataForAddonCategory = {
                addon_category_id: addon_id,
                parentAddonId: parentAddonId
            };

            let nestedData = {
                addon_id: id,
                parentAddonId: parentAddonId
            };

            setChoosenNestedaddons([
                ...ChoosenNestedaddons,
                nestedDataForAddonCategory
            ]);
            setNestedAddon([...NestedAddon, nestedData]);
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

    const addExtraNestedAddon = (extra, parentAddonId) => {
        let find = extraNestedAddon.find(
            data =>
                data.addon_id == extra.addon_id &&
                data.parentAddonId == parentAddonId
        );
        let newData = [];
        if (find) {
            let newData = extraNestedAddon.filter(
                item =>
                    item.addon_id != find.addon_id &&
                    item.parentAddonId != find.parentAddonId
            );
            // console.log(newData)
            setExtraNestedAddon([...newData, extra]);
        } else setExtraNestedAddon([...extraNestedAddon, extra]);
    };

    let saveAddon = (product_id, type) => {
        let { isAvailiable, isCustomizable, AddToCart } = props;

        if (!isCustomizable) {
            AddToCart(product_id);
            return;
        }

        let nestedAddon = {
            addon: NestedAddon,
            extra: extraNestedAddon.filter(data => data.count != 0)
        };

        if (type == "SNG" && Addon == null) {
            let extra = extraAddon.filter(data => data.count != 0);
            props.SaveAddon(
                product_id,
                isAvailiable,
                addon[0]?.categories[0]?.addons[0]?.id,
                extra,
                nestedAddon,
                fromRecommendItem ? false : true
            );
        } else {
            let extra = extraAddon.filter(data => data.count != 0);
            props.SaveAddon(
                product_id,
                isAvailiable,
                Addon,
                extra,
                nestedAddon,
                fromRecommendItem ? false : true
            );
        }
    };

    const showSngAddon = (data, value, index) => {
        if (!data?.is_active || !value?.is_active) {
            return;
        }

        return (
            <div
                class="single-sng"
                onClick={() => {
                    if (data.multi_select) {
                        setAddonCheckboxMulti(value.id, "SNG", data.id);
                    } else {
                        setAddonCheckbox(value.id, "SNG", data.id);
                    }
                }}
            >
                <label
                    class={`${
                        Addon.find(addonId => addonId == value.id)
                            ? "active2"
                            : ""
                    }`}
                >
                    <div className="sng-checkbox"></div>
                    <input
                        type="radio"
                        name={`options-${index}`}
                        id={`${value.id}`}
                    />
                    {value.addon_name} {"("}
                    <PriceRender
                        currency={currency}
                        price={value.price}
                        showFree={true}
                    />
                    {")"}
                </label>
            </div>
        );
    };

    const showExtAddon = (data, value) => {
        if (!data?.is_active || !value?.is_active) {
            return;
        }

        return (
            <div
                class="cart-items bg-white position-relative mb-3"
                style={{
                    borderBottom: "1px solid #eaeaea"
                }}
            >
                <div class="row align-items-center">
                    <div className="col d-flex align-items-center">
                        <h6 class="m-0 mr-1">{value.addon_name}</h6>

                        <p class="total_price font-weight-bold m-0">
                            (
                            <PriceRender
                                currency={currency}
                                price={value.price}
                            />
                            )
                        </p>
                    </div>
                    <div class="col d-flex align-items-center md-3">
                        <div class="input-group input-spinner  cart-items-number ml-auto">
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
                                            addon[0]?.product_id,
                                            value.addon_category_id
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
                                    `${
                                        fromRecommendItem
                                            ? `rcmnd${mainIndex}-`
                                            : ""
                                    }addon-${value.id}-${props.index}`
                                )}
                                id={`${
                                    fromRecommendItem
                                        ? `rcmnd${mainIndex}-`
                                        : ""
                                }addon-${value.id}-${props.index}-${
                                    addon[0]?.product_id
                                }`}
                                name={`extra-${props.index}`}
                                style={{
                                    height: 35
                                }}
                                readOnly={true}
                            />
                            <div class="input-group-prepend">
                                <button
                                    class="btn btn-success btn-sm"
                                    onClick={() =>
                                        updateAddon(
                                            value.id,
                                            "ADD",
                                            props.index,
                                            addon[0]?.product_id,
                                            value.addon_category_id
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
            </div>
        );
    };

    const showNestedSngAddon = (data, value, index, parentAddonId) => {
        return (
            <div
                class="single-sng"
                onClick={() => {
                    if (data.multi_select) {
                        setNestedAddonCheckboxMulti(
                            value.id,
                            "SNG",
                            data.id,
                            parentAddonId
                        );
                    } else {
                        setNestedAddonCheckbox(
                            value.id,
                            "SNG",
                            data.id,
                            parentAddonId
                        );
                    }
                }}
            >
                <label
                    class={`${
                        NestedAddon.find(
                            addon =>
                                addon.addon_id == value.id &&
                                addon.parentAddonId == parentAddonId
                        )
                            ? "active2"
                            : ""
                    }`}
                >
                    <div className="sng-checkbox"></div>
                    <input
                        type="radio"
                        name={`options-${index}`}
                        id={`${value.id}`}
                    />
                    {value.addon_name} {"("}
                    <PriceRender
                        currency={currency}
                        price={value.price}
                        showFree={true}
                    />
                    {")"}
                </label>
            </div>
        );
    };

    const showNestedExtAddon = (data, value, parentAddonId) => {
        return (
            <div
                class="cart-items bg-white position-relative mb-3"
                style={{
                    borderBottom: "1px solid #eaeaea"
                }}
            >
                <div class="row align-items-center">
                    <div className="col d-flex align-items-center">
                        <h6 class="m-0 mr-1">{value.addon_name}</h6>

                        <p class="total_price font-weight-bold m-0">
                            (
                            <PriceRender
                                currency={currency}
                                price={value.price}
                            />
                            )
                        </p>
                    </div>
                    <div class="col d-flex align-items-center md-3">
                        <div class="input-group input-spinner  cart-items-number ml-auto">
                            <div class="input-group-append ">
                                <button
                                    class="btn btn-success btn-sm"
                                    type="button"
                                    id="button-minus"
                                    onClick={() =>
                                        updateNestedAddon(
                                            value.id,
                                            "SUB",
                                            props.index,
                                            addon[0]?.product_id,
                                            value.addon_category_id,
                                            parentAddonId
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
                                    `${
                                        fromRecommendItem
                                            ? `rcmnd${mainIndex}-`
                                            : ""
                                    }addon-${value.id}-${
                                        props.index
                                    }-${parentAddonId}`
                                )}
                                id={`${
                                    fromRecommendItem
                                        ? `rcmnd${mainIndex}-`
                                        : ""
                                }addon-${value.id}-${props.index}-${
                                    addon[0]?.product_id
                                }-${parentAddonId}`}
                                name={`extra-${props.index}`}
                                style={{
                                    height: 25
                                }}
                                readOnly={true}
                            />
                            <div class="input-group-prepend">
                                <button
                                    class="btn btn-success btn-sm"
                                    onClick={() =>
                                        updateNestedAddon(
                                            value.id,
                                            "ADD",
                                            props.index,
                                            addon[0]?.product_id,
                                            value.addon_category_id,
                                            parentAddonId
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
            </div>
        );
    };

    if (addon) {
        return (
            <div
                // id={`customization-${addon[0]?.product_id}-${props.index}`}
                tabindex="-1"
                role="dialog"
                aria-labelledby="exampleModalLabel"
                aria-hidden="true"
            >
                <div id="customization-addons">
                    <div>
                        <div>
                            {addon.map(addon =>
                                addon.categories.map(
                                    data =>
                                        !!data.is_active && (
                                            <div class="form-row">
                                                <div class="mb-3 col-md-12 form-group">
                                                    <label class="form-label">
                                                        {data.name}
                                                    </label>

                                                    {data.type == "SNG" ? (
                                                        <div
                                                            class="w-100 sng2-buttons"
                                                            data-toggle="buttons"
                                                        >
                                                            {data.addons?.map(
                                                                (
                                                                    value,
                                                                    index
                                                                ) => (
                                                                    <>
                                                                        {showSngAddon(
                                                                            data,
                                                                            value,
                                                                            index
                                                                        )}

                                                                        <Expand
                                                                            open={
                                                                                Addon.find(
                                                                                    addonId =>
                                                                                        addonId ==
                                                                                        value.id
                                                                                )
                                                                                    ? true
                                                                                    : false
                                                                            }
                                                                        >
                                                                            {value.nested_addons.map(
                                                                                nested_add => (
                                                                                    <div class="form-row nested_row mt-3">
                                                                                        <div class="mb-3 col-md-12 form-group">
                                                                                            <label class="form-label">
                                                                                                {
                                                                                                    nested_add
                                                                                                        .addon_category
                                                                                                        .name
                                                                                                }
                                                                                            </label>

                                                                                            {nested_add
                                                                                                .addon_category
                                                                                                .type ==
                                                                                            "SNG" ? (
                                                                                                <div
                                                                                                    class="w-100 sng2-buttons"
                                                                                                    data-toggle="buttons"
                                                                                                >
                                                                                                    {nested_add.addon_category.addons?.map(
                                                                                                        (
                                                                                                            nestedValue,
                                                                                                            index
                                                                                                        ) => (
                                                                                                            <>
                                                                                                                {showNestedSngAddon(
                                                                                                                    nested_add.addon_category,
                                                                                                                    nestedValue,
                                                                                                                    index,
                                                                                                                    value.id
                                                                                                                )}
                                                                                                            </>
                                                                                                        )
                                                                                                    )}
                                                                                                </div>
                                                                                            ) : (
                                                                                                <div>
                                                                                                    {nested_add.addon_category.addons?.map(
                                                                                                        (
                                                                                                            nestedValue,
                                                                                                            index
                                                                                                        ) => (
                                                                                                            <>
                                                                                                                {showNestedExtAddon(
                                                                                                                    nested_add.addon_category,
                                                                                                                    nestedValue,
                                                                                                                    value.id
                                                                                                                )}
                                                                                                            </>
                                                                                                        )
                                                                                                    )}
                                                                                                </div>
                                                                                            )}
                                                                                        </div>
                                                                                    </div>
                                                                                )
                                                                            )}
                                                                        </Expand>
                                                                    </>
                                                                )
                                                            )}
                                                        </div>
                                                    ) : (
                                                        <div>
                                                            {data.addons?.map(
                                                                (
                                                                    value,
                                                                    index
                                                                ) => (
                                                                    <>
                                                                        {showExtAddon(
                                                                            data,
                                                                            value
                                                                        )}

                                                                        <Expand
                                                                            open={extraAddon.find(
                                                                                cAddon =>
                                                                                    cAddon.addon_id ==
                                                                                        value.id &&
                                                                                    cAddon.count >
                                                                                        0
                                                                            )}
                                                                        >
                                                                            {value.nested_addons.map(
                                                                                nested_add => (
                                                                                    <div class="form-row nested_row">
                                                                                        <div class="mb-3 col-md-12 form-group">
                                                                                            <label class="form-label">
                                                                                                {
                                                                                                    nested_add
                                                                                                        .addon_category
                                                                                                        .name
                                                                                                }
                                                                                            </label>

                                                                                            {nested_add
                                                                                                .addon_category
                                                                                                .type ==
                                                                                            "SNG" ? (
                                                                                                <div
                                                                                                    class="w-100 sng2-buttons"
                                                                                                    data-toggle="buttons"
                                                                                                >
                                                                                                    {nested_add.addon_category.addons?.map(
                                                                                                        (
                                                                                                            nestedValue,
                                                                                                            index
                                                                                                        ) => (
                                                                                                            <>
                                                                                                                {showNestedSngAddon(
                                                                                                                    nested_add.addon_category,
                                                                                                                    nestedValue,
                                                                                                                    index,
                                                                                                                    value.id
                                                                                                                )}
                                                                                                            </>
                                                                                                        )
                                                                                                    )}
                                                                                                </div>
                                                                                            ) : (
                                                                                                <div>
                                                                                                    {nested_add.addon_category.addons?.map(
                                                                                                        (
                                                                                                            nestedValue,
                                                                                                            index
                                                                                                        ) => (
                                                                                                            <>
                                                                                                                {showNestedExtAddon(
                                                                                                                    nested_add.addon_category,
                                                                                                                    nestedValue,
                                                                                                                    value.id
                                                                                                                )}
                                                                                                            </>
                                                                                                        )
                                                                                                    )}
                                                                                                </div>
                                                                                            )}
                                                                                        </div>
                                                                                    </div>
                                                                                )
                                                                            )}
                                                                        </Expand>
                                                                    </>
                                                                )
                                                            )}
                                                        </div>
                                                    )}
                                                </div>
                                            </div>
                                        )
                                )
                            )}
                        </div>

                        <div class=" p-0 border-0">
                            <div class="col-12 m-0 p-0">
                                <button
                                    type="button"
                                    // data-dismiss="modal"
                                    onClick={() => {
                                        if (!checkMaxAddon()) return;

                                        if (isCustomizable) {
                                            saveAddon(
                                                addon[0]?.product_id,
                                                addon[0]?.categories[0].type
                                            );
                                        } else {
                                            saveAddon(
                                                props.productID,
                                                addon[0]?.categories[0].type
                                            );
                                        }
                                    }}
                                    class="btn btn-danger btn-lg btn-block"
                                >
                                    {translation?.add_to_cart || "Add To Cart"}{" "}
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

export default connect(mapSateToProps, {})(BlockCustomization);
