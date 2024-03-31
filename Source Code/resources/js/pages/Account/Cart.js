import React from "react";
import ReactDOM from "react-dom";
import Header from "../../components/Header";
import SideBar from "../../components/SideBar";
import { NavLink, Route } from "react-router-dom";
import { connect } from "react-redux";
import { fetchStoreItems } from "../../actions/storeAction";
import {
    addToCart,
    setCart,
    removeFromCart,
    removeAllFromCart
} from "../../actions/cartAction";
import { createOrder } from "../../actions/orderAction";
import domain from "../../config/api/domain";
import ROUTE from "../../config/route";
import FooterBar from "../Containers/FooterBar";
import PriceRender from "../Containers/PriceRender";
import RoundButton from "../Containers/RoundButton";
import NotificationSystem from "react-notification-system";
import axios from "axios";
import api from "../../config/api";
import {
    addTipsForCheckout,
    addCardForCheckout
} from "../../actions/checkoutAction";
import SuccessfullModal from "../Containers/SuccessfullModal";
import ErrorModal from "../Containers/ErrorModal";
import miscVariables from "../../helpers/misc";
import {
    initiateGeoLocationWatcher,
    setCurrentPosition,
    positionError
} from "../../helpers/geoLocation";

let storeId = null;
var errorModalText = "An error occured. Please try again later.";
class Cart extends React.Component {
    notificationSystem = React.createRef();
    constructor(props) {
        super(props);
        this.state = {
            comments: "",
            table_no: null,
            table_code: null,
            total: 0,
            button_disabled: false,
            is_loading: false,
            is_completed: false,
            usedCoupon: false,
            cardPay: false,
            totalCoupon: 0,
            couponCode: null
        };
        this.onChange = this.onChange.bind(this);
    }

    componentWillMount() {
        storeId = this.props.match.params.store_id;
        let data = {
            view_id: storeId
        };
        this.calculateTotal(this.props.cart);
        this.calculateDiscount(this.props.cart);
        this.checkEmpty(this.props.cart);
    }

    componentDidMount() {
        // this.geoWatcher();

        const reactNotification = this.notificationSystem.current;

        navigator.geolocation.getCurrentPosition(
            position => {
                const getCoordsData = setCurrentPosition(
                    position,
                    this.props.store_latitude,
                    this.props.store_longitude
                );

                if (
                    this.props.order_range < getCoordsData?.metersAwayFromStore
                ) {
                    reactNotification.addNotification({
                        message: `You are outside the order range. You have to be ${this.props.store?.order_range}meter within to start your order`,
                        level: "error"
                    });

                    return;
                }
            },
            error => {
                const getError = positionError(error);

                reactNotification.addNotification({
                    message: getError,
                    level: "error"
                });

                this.setState({ button_disabled: true });

                return;
            },
            {
                enableHighAccuracy: false,
                timeout: 15000,
                maximumAge: 0
            }
        );
    }

    componentWillReceiveProps(nextProps) {
        if (nextProps.cart) {
            this.calculateTotal(nextProps.cart);
            this.calculateDiscount(nextProps.cart);
            this.checkEmpty(nextProps.cart);
        }
        if (nextProps.orders != this.props.orders) {
            if (this.props.pay_first) {
                this.setState({ cardPay: true, is_loading: false });
            } else {
                this.setState({ is_completed: true });
                this.props.removeAllFromCart();
            }
        }
    }

    calculateTotalCoupon = (cartData, coupon) => {
        let { products, cart, addons } = this.props;
        let { usedCoupon, totalCoupon } = this.state;
        let product;
        let sum = 0;
        cart = cartData.filter(data => data.storeId == storeId);

        if (!usedCoupon) {
            for (let item in cart) {
                let product = products.find(
                    data => data.id == cart[item].itemId
                );

                if (
                    coupon?.accepted_products == null &&
                    !coupon?.excluded_products?.includes("" + product.id)
                ) {
                    if (
                        coupon?.excluded_categories?.includes(
                            "" + product.category_id
                        )
                    ) {
                        return;
                    }
                    sum = sum + coupon.fixed_amount;
                    this.setState({ totalCoupon: sum, usedCoupon: true });
                    return sum;
                } else if (
                    coupon?.accepted_categories == null &&
                    !coupon?.excluded_categories?.includes(
                        "" + product.category_id
                    )
                ) {
                    sum = sum + coupon.fixed_amount;
                    this.setState({ totalCoupon: sum, usedCoupon: true });
                    return sum;
                } else if (
                    coupon?.accepted_products?.includes("" + product.id) &&
                    !coupon?.excluded_products?.includes("" + product.id)
                ) {
                    sum = sum + coupon.fixed_amount;
                    this.setState({ totalCoupon: sum, usedCoupon: true });
                    return sum;
                } else if (
                    coupon?.accepted_categories?.includes(
                        "" + product.category_id
                    ) &&
                    !coupon?.excluded_categories?.includes(
                        "" + product.category_id
                    )
                ) {
                    sum = sum + coupon.fixed_amount;
                    this.setState({ totalCoupon: sum, usedCoupon: true });
                    return sum;
                }
            }
        }
    };

    calculateTotal = cartData => {
        // console.log(cartData)
        if (cartData.length != undefined) {
            let { products, cart, addons } = this.props;
            let product;
            let sum = 0;
            let addon = 0;
            let temp_sum = 0;
            cart = cartData.filter(data => data.storeId == storeId);
            // console.log(cart);
            for (let item in cart) {
                let product = products.find(
                    data => data.id == cart[item].itemId
                );

                sum = sum + product.price * cart[item].count;

                // console.log(`product ${product.name}`, sum);

                if (cart[item].addon != null || cart[item].extra) {
                    if (cart[item].addon != null) {
                        cart[item].addon.map(addon_id => {
                            let tempAddon = addons.find(
                                data => data.id == addon_id
                            );
                            if (tempAddon) {
                                sum =
                                    sum +
                                    Number(
                                        this.renderData(
                                            "ADDON_PRICE",
                                            tempAddon.id,
                                            cart[item].nestedAddon
                                        )
                                    ) *
                                        cart[item].count;
                            }
                        });
                    }

                    // console.log(`sum 1 product ${product.name}`, sum);

                    if (cart[item].extra) {
                        temp_sum = 0;
                        for (let ext in cart[item].extra) {
                            let tempAddonExtra = addons.find(
                                data =>
                                    data.id == cart[item].extra[ext].addon_id
                            );
                            temp_sum =
                                temp_sum +
                                Number(
                                    this.renderData(
                                        "ADDON_PRICE",
                                        tempAddonExtra.id,
                                        cart[item].nestedAddon
                                    )
                                ) *
                                    cart[item].extra[ext].count;
                        }
                        sum = sum + temp_sum * cart[item].count;
                    }

                    // console.log(`sum 2 product ${product.name}`, sum);
                }
            }
            this.setState({ total: sum });
        }
    };

    calculateDiscount = cartData => {
        // console.log(cartData)
        if (cartData.length != undefined) {
            let { products, cart, addons } = this.props;
            let product;
            let sum = 0;
            let addon = 0;
            let temp_sum = 0;
            cart = cartData.filter(data => data.storeId == storeId);
            // console.log(cart);
            for (let item in cart) {
                let product = products.find(
                    data => data.id == cart[item].itemId
                );

                if (product?.is_discountable) {
                    let discountType = product.discounts[0].discount_type;

                    if (discountType == 1) {
                        sum =
                            sum +
                            Number(product.discounts[0].discount_price_fixed) *
                                cart[item].count;
                    } else if (discountType == 2) {
                        let percantageAmount = Number(
                            product.discounts[0].discount_price_percentage
                        );
                        // console.log("percantageAmount", percantageAmount);
                        sum =
                            sum +
                            (Number(product.price) / 100) *
                                percantageAmount *
                                cart[item].count;
                    }
                }
                console.log("discount", sum);
                this.setState({ totalDiscount: sum });
            }
        }
    };

    checkEmpty = cart => {
        let data = cart.filter(data => data.storeId == storeId);
        let check = cart.find(data => data.count <= 0);
        check != undefined
            ? this.setState({ button_disabled: true })
            : this.setState({ button_disabled: false });
    };

    renderData = (TYPE, ID, nestedData = {}) => {
        let { products, cart, addons, addon_categories } = this.props;
        let response;
        switch (TYPE) {
            case "NAME":
                response = products.find(data => data.id == ID).name;
                break;
            case "IMAGE":
                response = products.find(data => data.id == ID).image_url;
                break;
            case "PRICE":
                response = products.find(data => data.id == ID).price;
                break;
            case "ADDON_PRICE":
                response =
                    addons.find(data => data.id == ID).price +
                    this.renderData("NESTED_CATEGORY_PRICE", ID, nestedData);
                break;

            case "NESTED_CATEGORY_PRICE":
                let price = [];
                let getSngAddonFilterPrice = [];
                let getExtAddonFilterPrice = [];

                getSngAddonFilterPrice = nestedData?.addon?.filter(
                    addon => addon.parentAddonId == ID
                );
                getSngAddonFilterPrice?.map(addon => {
                    price.push(this.renderData("ADDON_PRICE", addon.addon_id));
                });

                getExtAddonFilterPrice = nestedData?.extra?.filter(
                    addon => addon.parentAddonId == ID
                );
                getExtAddonFilterPrice?.map(addon => {
                    price.push(this.renderData("ADDON_PRICE", addon.addon_id));
                });

                console.log(
                    `price getSngAddonFilterPrice getExtAddonFilterPrice`,
                    price,
                    getSngAddonFilterPrice,
                    getExtAddonFilterPrice
                );

                response = price.reduce((a, b) => a + b, 0);
                break;

            case "NESTED_CATEGORY_LIST":
                let name = [];
                let getSngAddonFilter = [];
                let getExtAddonFilter = [];

                getSngAddonFilter = nestedData?.addon?.filter(
                    addon => addon.parentAddonId == ID
                );
                getSngAddonFilter?.map(addon => {
                    let tempName = `${this.renderData(
                        "ADDON_CATEGORY_NAME",
                        addon.addon_id
                    )}: ${this.renderData("ADDON_NAME", addon.addon_id)}`;
                    name.push(tempName);
                });

                getExtAddonFilter = nestedData?.extra?.filter(
                    addon => addon.parentAddonId == ID
                );
                getExtAddonFilter?.map(addon => {
                    let tempName = `${this.renderData(
                        "ADDON_NAME",
                        addon.addon_id
                    )} x${addon.count}`;
                    name.push(tempName);
                });

                response =
                    getSngAddonFilter?.length > 0 ||
                    getExtAddonFilter?.length > 0
                        ? `(${name.join(", ")})`
                        : name.join(", ");
                break;

            case "ADDON_NAME":
                response = addons.find(data => data.id == ID).addon_name;
                break;
            case "ADDON_CATEGORY_NAME":
                let getCategoryId = addons.find(data => data.id == ID)
                    .addon_category_id;
                response = addon_categories.find(
                    data => data.id == getCategoryId
                ).name;
                break;
            case "IS_DISCOUNTABLE":
                response = products.find(data => data.id == ID).is_discountable;
                break;
            case "DISCOUNT_PRICE":
                response =
                    products.find(data => data.id == ID).discounts[0]
                        .discount_type == 1
                        ? products.find(data => data.id == ID).discounts[0]
                              .discount_price_fixed
                        : products.find(data => data.id == ID).discounts[0]
                              .discount_price_percentage;
                break;
        }
        return response;
    };

    updateCart = (REF, count, type, addon, extra) => {
        if (type == "ADD") this[`textInput${REF}`].value = count + 1;
        else if (type == "SUB") {
            if (count - 1 <= 0) return;

            this[`textInput${REF}`].value = count - 1;
        }
        let data = {
            storeId: storeId,
            itemId: REF,
            count: parseInt(this[`textInput${REF}`].value - count),
            addon: addon,
            extra: extra
        };
        this.props.addToCart(data);

        // console.log(data)
    };
    removeFormCart = (id, addon) => {
        this.props.removeFromCart(id, addon);
    };

    renderItems = () => {
        let { products, cart, account_info } = this.props;
        let currency = account_info ? account_info.currency_symbol : "₹";
        cart = cart.filter(data => data.storeId == storeId);
        return (
            <div class="">
                <div
                    className="cart-items position-relative pb-4"
                    style={{
                        borderBottom: "1px solid #cacdd0"
                    }}
                >
                    {cart.map(data => (
                        <div
                            className="row no-gutters justify-content-center align-items-center px-3 pt-4 pb-3 mx-2 mb-2"
                            style={{
                                border: "2px solid #bfbfbf",
                                borderRadius: 5
                            }}
                        >
                            <div className="col-4 col-sm-2 col-md-2 col-lg-1 p-0 align-self-start">
                                <img
                                    src={`${domain.s3_url}/${this.renderData(
                                        "IMAGE",
                                        data.itemId
                                    ) ||
                                        "themes/default/images/all-img/empty.png"}`}
                                    className="img-fluid shadow-sm"
                                    style={{ backgroundColor: "#ffffff" }}
                                />
                            </div>
                            <div className="ml-3 text-dark text-decoration-none col col-sm-5 col-md-4 col-lg-3 col-xl-2 p-0">
                                <div className="row no-gutters align-content-center">
                                    <div class="col my-auto">
                                        <h6 className="mb-1">
                                            {this.renderData(
                                                "NAME",
                                                data.itemId
                                            )}
                                        </h6>
                                    </div>
                                </div>
                                <div className="row align-items-center no-gutters">
                                    <div className="col-12">
                                        <PriceRender
                                            currency={currency}
                                            price={
                                                this.renderData(
                                                    "PRICE",
                                                    data.itemId
                                                ) * data.count
                                            }
                                            style={{ fontSize: 14 }}
                                        />
                                    </div>
                                    <div className="input-group input-spinner col-8 cart-items-number align-items-center">
                                        <div className="input-group-prepend">
                                            <button
                                                className="btn btn-success btn-sm"
                                                type="button"
                                                onClick={() =>
                                                    this.updateCart(
                                                        data.itemId,
                                                        data.count,
                                                        "SUB",
                                                        data.addon,
                                                        data.extra
                                                    )
                                                }
                                            >
                                                <i class="icofont-minus"></i>
                                            </button>
                                        </div>
                                        <input
                                            type="text"
                                            className="form-control"
                                            value={data.count}
                                            placeholder=""
                                            ref={input => {
                                                this[
                                                    `textInput${data.itemId}`
                                                ] = input;
                                            }}
                                        />
                                        <div className="input-group-append">
                                            <button
                                                className="btn btn-success btn-sm"
                                                type="button"
                                                onClick={() =>
                                                    this.updateCart(
                                                        data.itemId,
                                                        data.count,
                                                        "ADD",
                                                        data.addon,
                                                        data.extra
                                                    )
                                                }
                                            >
                                                <i class="icofont-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div className="col-4 text-right cart-delete">
                                        <button
                                            className="btn btn-sm"
                                            type="button"
                                            onClick={() =>
                                                this.removeFormCart(
                                                    data.itemId,
                                                    data.addon
                                                )
                                            }
                                            style={{
                                                color: "red"
                                            }}
                                        >
                                            <i class="icofont-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div className="col-12">
                                {data.addon && data.addon.length ? (
                                    <div class="pt-3">
                                        <div class="clearfix">
                                            <p class="mb-1 text-muted">
                                                Settings:{" "}
                                                <span class="float-right text-dark"></span>
                                            </p>
                                            {data?.addon?.map(ext => (
                                                <p class="mb-1 text-muted row m-0 no-gutters">
                                                    <div className="col">
                                                        {`${this.renderData(
                                                            "ADDON_CATEGORY_NAME",
                                                            ext
                                                        )}: ${this.renderData(
                                                            "ADDON_NAME",
                                                            ext
                                                        )} ${this.renderData(
                                                            "NESTED_CATEGORY_LIST",
                                                            ext,
                                                            data.nestedAddon
                                                        )}`}
                                                    </div>
                                                    <div className="col-3 text-right">
                                                        <PriceRender
                                                            currency={
                                                                currency
                                                                    ? currency
                                                                    : "USD"
                                                            }
                                                            price={this.renderData(
                                                                ext == null
                                                                    ? "PRICE"
                                                                    : "ADDON_PRICE",
                                                                ext == null
                                                                    ? data.itemId
                                                                    : ext,
                                                                data.nestedAddon
                                                            )}
                                                        />
                                                    </div>
                                                </p>
                                            ))}
                                        </div>
                                    </div>
                                ) : null}

                                {data.extra && data.extra.length ? (
                                    <div class="pt-3">
                                        <div class="clearfix">
                                            <p class="mb-1 text-muted">
                                                Extra:{" "}
                                                <span class="float-right text-dark"></span>
                                            </p>
                                            {data?.extra?.map(ext => (
                                                <p class="mb-1 text-muted row m-0 no-gutters">
                                                    <div className="col">
                                                        {`${this.renderData(
                                                            "ADDON_NAME",
                                                            ext.addon_id
                                                        )} x${
                                                            ext.count
                                                        } ${this.renderData(
                                                            "NESTED_CATEGORY_LIST",
                                                            ext.addon_id,
                                                            data.nestedAddon
                                                        )}`}
                                                    </div>
                                                    <div className="col-3 text-right">
                                                        <PriceRender
                                                            currency={
                                                                data.addon ==
                                                                null
                                                                    ? "USD"
                                                                    : currency
                                                            }
                                                            price={this.renderData(
                                                                "ADDON_PRICE",
                                                                ext.addon_id,
                                                                data.nestedAddon
                                                            )}
                                                        />
                                                    </div>
                                                </p>
                                            ))}
                                        </div>
                                    </div>
                                ) : null}
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        );
    };

    onChange(e) {
        this.setState({ [e.target.name]: e.target.value });
    }

    geoWatcher = async () => {
        let {
            is_location_required,
            store_latitude,
            store_longitude,
            order_range
        } = this.props;

        console.log(
            "first",
            is_location_required,
            store_latitude,
            store_longitude,
            order_range
        );

        const notification = this.notificationSystem.current;

        if (is_location_required) {
            await initiateGeoLocationWatcher(
                store_latitude,
                store_longitude,
                order_range
            )
                .then(geoLocationWatcher => {
                    console.log("geoLocationWatcher", geoLocationWatcher);
                })
                .catch(err => {
                    // console.log("err", err);

                    notification.addNotification({
                        message: err,
                        level: "error"
                    });

                    console.log("err", err);

                    this.setState({ button_disabled: true });
                    // this.setState({ is_loading: true });
                });
        }
    };

    submitForm = () => {
        this.setState({ button_disabled: true });
        const notification = this.notificationSystem.current;
        if (!this.props.auth.isLogin) {
            notification.addNotification({
                message: "Please login first.",
                level: "error"
            });
            setTimeout(() => {
                window.location.href = `${ROUTE.ACCOUNT.LOGIN.PAGES.VIEW.PATH}`;
            }, 3000);
            return;
        }

        // Will uncomment later
        // if (this.props.auth.cards.length < 1) {
        //     notification.addNotification({
        //         message: "Please add a card first.",
        //         level: "error"
        //     });
        //     return;
        // }

        if (this.state.is_loading) {
            notification.addNotification({
                message: "Please wait.",
                level: "error"
            });
            return;
        }

        event.preventDefault();
        const {
            // name,
            // phone,
            table_no,
            table_code,
            total,
            button_disabled,
            is_loading,
            couponCode
        } = this.state;
        const { cart, tables, translation } = this.props;
        // if (!(name && phone) && is_loading == false) return;
        if (table_no) {
            let data = tables.filter(data => data.table_name == table_no);
            if (data[0]?.table_code) {
                if (!(data[0]?.table_code == table_code)) {
                    alert(
                        translation?.table_code_error_message ||
                            "INVALID TABLE CODE/PLEASE ENTER A VALID CODE"
                    );
                    return;
                }
            }
        }

        let data = {
            store_id: storeId,
            couponCode: couponCode,
            comments: this.state.comments,
            total: (
                Number(this.state.total) +
                Number(this.props.service_charge) -
                Number(this.state.totalDiscount) -
                Number(this.state.totalCoupon)
            ).toFixed(2),
            cart: cart.filter(data => data.storeId == storeId),
            store_charge: this.props.service_charge,
            tax: Number((this.state.total * this.props.tax) / 100).toFixed(2),
            discount: Number(this.state.totalDiscount).toFixed(2),
            coupon: Number(this.state.totalCoupon).toFixed(2),
            sub_total: Number(this.state.total).toFixed(2)
        };

        this.props.createOrder(data);
        this.setState({ is_loading: false });
    };

    applyCoupon = () => {
        let url = api.coupon.check.path;
        const notification = this.notificationSystem.current;
        fetch(url, {
            method: "POST",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
                Authorization: "Bearer " + this.props.auth.token
            },
            body: JSON.stringify({
                code: this.state.couponCode,
                storeId: storeId
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.status == "success") {
                    if (
                        data.payload.coupon.minimum_spend !== 0 &&
                        this.state.total < data.payload.coupon.minimum_spend
                    ) {
                        notification.addNotification({
                            message: `You have to spend minimum: ${data.payload.coupon.minimum_spend} to use this coupon`,
                            level: "error"
                        });
                        return;
                    }
                    if (
                        data.payload.coupon.maximum_spend !== 0 &&
                        this.state.total > data.payload.coupon.maximum_spend
                    ) {
                        notification.addNotification({
                            message: `You can only spend maximum: ${data.payload.coupon.maximum_spend} to use this coupon`,
                            level: "error"
                        });
                        return;
                    }
                    this.calculateTotalCoupon(
                        this.props.cart,
                        data.payload.coupon
                    );
                } else {
                    console.log("error:", data);
                    notification.addNotification({
                        message: data.payload.message,
                        level: "error"
                    });
                }
            })
            .catch(error => {
                console.log("error:", error);
                notification.addNotification({
                    message: "An error occured. Please try again later.",
                    level: "error"
                });
            });
    };

    renderTableCode = table_name => {
        const { tables, translation } = this.props;
        let data = tables.filter(data => data.table_name == table_name);

        if (data[0]?.table_code) {
            return (
                <div className="form-group">
                    <label htmlFor="exampleInputNEWPassword1">
                        {translation?.enter_your_table_code ||
                            "Enter Your Code"}
                    </label>
                    <input
                        type="text"
                        className="form-control"
                        name="table_code"
                        placeholder={
                            translation?.enter_your_table_code ||
                            "Enter Your Code"
                        }
                        value={this.state.table_code}
                        onChange={this.onChange}
                    />
                </div>
            );
        }
    };

    checkStatus = async response => {
        if (response.status >= 200 && response.status < 300)
            return await response.json();

        throw await response.json();
    };

    secondaryHeader(text) {
        return (
            <p
                className="mt-3 text-secondary"
                style={{
                    fontWeight: "bold",
                    textTransform: "uppercase",
                    fontSize: "15px"
                }}
            >
                {text ?? "payment"}
            </p>
        );
    }

    payViaCard() {
        if (this.state.is_loading) return;

        const notification = this.notificationSystem.current;

        this.setState({ is_loading: true });

        let { addTipsForCheckout, addCardForCheckout } = this.props;
        // addTipsForCheckout({ tips: this.calculateTotalTips() });
        addCardForCheckout({ card: this.state.selectedCardId });

        // return;

        this.setState({ is_loading: false }, () => {
            // window.location.href = ROUTE.STORE.Checkout.PAGES.Limonetik.PATH;
            this.callServerPost();
        });
        return;
    }

    callServerPost = async (retries = 5) => {
        // let retries = 5;

        const notification = this.notificationSystem.current;

        // Will uncomment later
        // if (this.props.auth.cards.length < 1) {
        //     notification.addNotification({
        //         message: "Please add a card first.",
        //         level: "error"
        //     });
        //     return;
        // }

        let { checkout_order, checkout_tips, selected_card } = this.props;

        let data = {
            orders: checkout_order,
            tips: checkout_tips,
            card: selected_card
        };

        let url = api.store.Checkout.limonetikCreateOrder.path;

        if (data == null) {
            this.errorOccured();
            return;
        }

        this.setState({ is_loading: true });

        const res = fetch(url, {
            method: "POST",
            body: JSON.stringify(data),
            headers: {
                Authorization: "Bearer " + this.props.auth.token
            }
        })
            .then(response => this.checkStatus(response))
            .then(res => {
                const resData = res.payload.data;

                console.log(`resData.Status`, resData.Status);

                if (resData.Status == "Authorized") {
                    this.setState(
                        {
                            // is_loading: false,
                            paymentOrderId: resData.PaymentOrderId
                        },
                        () => {
                            this.paymentReceived();
                        }
                    );
                } else {
                    this.setState(
                        {
                            is_loading: false,
                            paymentOrderId: resData.PaymentOrderId,
                            paymentPageUrl: resData.PaymentPageUrl
                        },
                        () => {
                            this.updateIframe();
                        }
                    );
                }
            })
            .catch(err => {
                console.log(
                    `err?.payload?.data?.ReturnCode`,
                    err?.payload?.data?.ReturnCode
                );
                if (err?.payload?.data?.ReturnCode == 9500) {
                    if (retries > 0) {
                        notification.addNotification({
                            message: `Please wait ${(miscVariables.retryFetch *
                                retries) /
                                1000} seconds.`,
                            level: "error"
                        });

                        setTimeout(() => {
                            this.callServerPost(retries - 1);
                        }, miscVariables.retryFetch * retries);
                    }
                    return;
                }
                console.log(`err`, err);
                this.errorOccured();
            });

        return res;
    };

    updateIframe() {
        var frame = document.getElementById("LmkFrame");
        frame.setAttribute("src", this.state.paymentPageUrl);
        var currentSrcIframe =
            frame.ownerDocument.defaultView.window[0].location.href;
        frame.addEventListener("load", () => {
            {
                if (currentSrcIframe.includes("payment.limonetik") == false) {
                    this.paymentReceived();
                }
            }
        });
    }

    chargePayment = PaymentOrder => {
        this.setState({ is_loading: true });
        let url = api.store.Checkout.limonetikChargeOrder.path;

        let chargePaymentData = {
            PaymentOrderId: PaymentOrder.Id,
            ChargeAmount: PaymentOrder.Amount,
            Currency: "EUR",
            orders: this.props.checkout_order,

            customer_id: this.props.auth.userId,
            amount: PaymentOrder.Amount,
            currency: PaymentOrder.Currency,
            fromCart: true
        };

        const res = fetch(url, {
            method: "POST",
            body: JSON.stringify(chargePaymentData),
            headers: {
                Authorization: "Bearer " + this.props.auth.token
            }
        })
            .then(response => response.json())
            .then(res => {
                if (res.success == false) {
                    this.errorOccured();
                }

                const resData = res.payload.data;

                this.setState(
                    {
                        modalText: "Payment Received Successfully",
                        is_loading: false
                    },
                    () => {
                        let { modalText } = this.state;
                        var notification = this.notificationSystem.current;
                        notification.addNotification({
                            message: modalText,
                            level: "success"
                        });
                        this.setState({ is_completed: true });
                        this.props.removeAllFromCart();

                        document.getElementById("#successfullModal").click();

                        setTimeout(() => {
                            window.location.href = `/store/home`;
                        }, miscVariables.setTimeoutTimer);
                    }
                );
            })
            .catch(error => {
                this.errorOccured();
            });
    };

    paymentReceived = () => {
        this.setState({ is_loading: true });
        let data = {
            PaymentOrderId: this.state.paymentOrderId
        };
        let url = api.store.Checkout.limonetikGetOrder.path;

        const res = fetch(url, {
            method: "POST",
            body: JSON.stringify(data),
            headers: {
                Authorization: "Bearer " + this.props?.auth?.token
            }
        })
            .then(response => response.json())
            .then(res => {
                this.setState({ is_loading: false });

                const resData = res.payload.data;

                if (resData.PaymentOrder.Status == "Authorized") {
                    this.chargePayment(resData.PaymentOrder);
                }
            });
    };

    errorOccured = () => {
        this.setState(
            {
                is_loading: false,
                modalText: errorModalText
            },
            () => {
                let { modalText } = this.state;
                var notification = this.notificationSystem.current;

                notification.addNotification({
                    message: modalText,
                    level: "error"
                });

                document.getElementById("#errorModal").click();

                // this.props.dispatchPaymentDone();
            }
        );

        // setTimeout(() => {
        //     window.location.href = `/store/${localStorage.getItem("storeId")}`;
        // }, miscVariables.setTimeoutTimer);

        return;
    };

    render() {
        let { products, cart, account_info, translation, auth } = this.props;
        let {
            usedCoupon,
            couponCode,
            cardPay,
            selectedCardId,
            modalText,
            is_loading
        } = this.state;
        let currency = account_info ? account_info.currency_symbol : "₹";

        if (is_loading) {
            return (
                <div
                    style={{
                        textAlign: "center",
                        paddingTop: "50px",
                        position: "absolute",
                        width: "100%",
                        height: "100%",
                        top: "0",
                        left: "0",
                        zIndex: "100",
                        backgroundColor: "#fff"
                    }}
                >
                    <img src="/images/loading.gif" class="img-fluid mt-3" />
                </div>
            );
        }

        if (cardPay) {
            return (
                <main className="px-3 py-4" id="payment_orders">
                    <NotificationSystem ref={this.notificationSystem} />
                    {this.props.auth?.cards?.length > 0 && (
                        <>
                            {this.secondaryHeader("Pay via saved cards:")}

                            {this.props.auth.cards.map(card => (
                                <div
                                    className=""
                                    onClick={() => {
                                        if (selectedCardId == card.id) {
                                            this.setState({
                                                selectedCardId: null
                                            });
                                            return;
                                        }

                                        this.setState({
                                            selectedCardId: card.id
                                        });
                                    }}
                                >
                                    <div
                                        style={{
                                            backgroundColor: "#fff",
                                            padding: 15,
                                            display: "flex",
                                            marginBottom: "20px",
                                            borderRadius: "7px",
                                            border: "1px solid"
                                        }}
                                        className={`align-items-center ${
                                            selectedCardId == card.id
                                                ? "border-danger"
                                                : "border-secondary"
                                        }`}
                                    >
                                        <div className="mr-4">
                                            <i
                                                class="icofont-card"
                                                style={{
                                                    fontSize: 40
                                                }}
                                            ></i>
                                        </div>
                                        <p
                                            style={{
                                                marginBottom: "0"
                                            }}
                                        >
                                            {card.card_name}
                                        </p>
                                        <div
                                            style={{
                                                marginLeft: "auto"
                                            }}
                                            className={`${
                                                selectedCardId == card.id
                                                    ? "text-danger"
                                                    : ""
                                            }`}
                                        >
                                            <div
                                                style={{
                                                    width: 20,
                                                    height: 20,
                                                    borderRadius: "50%",
                                                    border: "2px solid ",
                                                    position: "relative"
                                                }}
                                                className={`${
                                                    selectedCardId == card.id
                                                        ? "active"
                                                        : ""
                                                }`}
                                            >
                                                <div
                                                    style={{
                                                        width: 10,
                                                        height: 10,
                                                        borderRadius: "50%",
                                                        position: "absolute",
                                                        top: "50%",
                                                        left: "50%",
                                                        transform:
                                                            "translate(-50%, -50%)",
                                                        backgroundColor:
                                                            selectedCardId ==
                                                            card.id
                                                                ? "#d30000"
                                                                : "inherit"
                                                    }}
                                                ></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </>
                    )}

                    <a
                        onClick={() => this.payViaCard()}
                        disabled={
                            this.state.button_disabled || this.state.is_loading
                                ? "disabled"
                                : null
                        }
                        className="text-decoration-none"
                    >
                        <div
                            className="shadow d-flex align-items-center p-3 text-white text-center"
                            style={{
                                backgroundColor: "red",
                                borderRadius: 35
                            }}
                        >
                            <div className="more w-100">
                                <h6 className="m-0">Pay</h6>
                            </div>
                            <div className="ml-auto">
                                <i className="icofont-simple-right"></i>
                            </div>
                        </div>
                    </a>
                    <SuccessfullModal text={modalText} />
                    <ErrorModal text={modalText} />
                </main>
            );
        }

        if (this.state.is_completed) {
            return (
                <div>
                    <div className="osahan-success bg-success vh-100">
                        <div className="p-5 text-center">
                            <i className="icofont-cart-alt display-1 text-white"></i>
                            <h1 className="text-white font-weight-bold">
                                {translation?.menu_order_successmsg ||
                                    "Order Placed Successfully."}{" "}
                                🎉
                            </h1>
                            <h5 class="text-white font-weight-bold mt-5">
                                You can check your order status under the "My
                                Account" tab.
                            </h5>
                        </div>
                    </div>

                    <div className="fixed-bottom fixed-bottom-auto bg-white rounded p-3 m-3 text-center">
                        <h6 className="font-weight-bold mb-2">
                            {translation?.menu_order_successmsg ||
                                "Order Placed Successfully."}
                        </h6>

                        <a
                            href={`${ROUTE.STORE.INDEX.PAGES.VIEW.PATH}/${storeId}`}
                            className="btn rounded btn-warning btn-lg btn-block"
                        >
                            {translation?.back_to_menu || "Back to Menu."}
                        </a>
                    </div>
                </div>
            );
        } else if (this.props.cart.length == 0) {
            return (
                <div>
                    {/*<Header />*/}
                    {/*<SideBar active="Cart" />*/}
                    <div className="osahan-success bg-danger vh-100">
                        <div className="p-5 text-center">
                            <i className="icofont-cart-alt display-1 text-white"></i>
                            <h1 className="text-white font-weight-bold">
                                {translation?.menu_cart_empty ||
                                    "Your cart is empty."}{" "}
                                🎉
                            </h1>
                        </div>
                    </div>

                    <div className="fixed-bottom fixed-bottom-auto bg-white rounded p-3 m-3 text-center">
                        <h6 className="font-weight-bold mb-2">
                            {translation?.menu_cart_empty ||
                                "Your cart is empty."}
                        </h6>

                        <a
                            href={`${ROUTE.STORE.INDEX.PAGES.VIEW.PATH}/${storeId}`}
                            className="btn rounded btn-warning btn-lg btn-block"
                        >
                            {translation?.back_to_menu || "Back to Menu."}{" "}
                        </a>
                    </div>
                </div>
            );
        } else {
            return (
                <div>
                    <NotificationSystem ref={this.notificationSystem} />
                    <div
                        className="fixed-bottom-padding"
                        style={{
                            background: "#fff"
                        }}
                    >
                        <div className="px-3 py-4 text-center">
                            <div className="d-flex align-items-center justify-content-center">
                                <h4 className="font-weight-bold m-0">
                                    Your Order
                                </h4>
                            </div>
                        </div>

                        <div className="osahan-body">
                            {this.renderItems()}

                            <div className="mt-3">
                                <div className="p-2">
                                    <div className="form-group custom-text-box-1">
                                        <span>
                                            {translation?.menu_comment ||
                                                "Comment"}
                                        </span>
                                        <input
                                            type="text"
                                            className="form-control"
                                            name="comments"
                                            value={this.state.comments}
                                            onChange={this.onChange}
                                        />
                                    </div>
                                </div>

                                {!usedCoupon && auth.isLogin && (
                                    <div className="p-2">
                                        <div className="row align-items-center">
                                            <div className="col-6 offset-6 justify-self-end">
                                                <div className="form-group custom-text-box-1 mb-0">
                                                    <span>Coupon Code</span>
                                                    <input
                                                        type="text"
                                                        className="form-control d-inline-flex"
                                                        name="couponCode"
                                                        value={
                                                            this.state
                                                                .couponCode
                                                        }
                                                        onChange={this.onChange}
                                                    />
                                                </div>
                                            </div>
                                            {couponCode && (
                                                <div className="col-12 text-right mt-1">
                                                    <a
                                                        href="#"
                                                        className=""
                                                        onClick={
                                                            this.applyCoupon
                                                        }
                                                    >
                                                        Apply Coupon
                                                    </a>
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                )}

                                <div className="p-2">
                                    <div class="p-2 border-bottom bg-white mb-2">
                                        <div class="d-flex align-items-center mb-2">
                                            <h6 class="font-weight-bold mb-1">
                                                {translation?.menu_subtotal ||
                                                    "Subtotal"}
                                            </h6>
                                            <h6 class="font-weight-bold ml-auto mb-1">
                                                {" "}
                                                <PriceRender
                                                    currency={currency}
                                                    price={this.state.total}
                                                />{" "}
                                            </h6>
                                        </div>

                                        <div class="d-flex align-items-center mb-2">
                                            <h6 class="font-weight-bold mb-1">
                                                {translation?.menu_service_charge ||
                                                    "Service Charge"}
                                            </h6>
                                            <h6 class="font-weight-bold ml-auto mb-1">
                                                {" "}
                                                <PriceRender
                                                    currency={currency}
                                                    price={
                                                        this.props
                                                            .service_charge
                                                            ? this.props
                                                                  .service_charge
                                                            : 0
                                                    }
                                                />{" "}
                                            </h6>
                                        </div>

                                        {usedCoupon && (
                                            <div class="d-flex align-items-center mb-2">
                                                <h6 class="font-weight-bold mb-1">
                                                    {"Coupon"}
                                                </h6>
                                                <h6 class="font-weight-bold ml-auto mb-1">
                                                    {"- ("}
                                                    <PriceRender
                                                        currency={currency}
                                                        price={
                                                            this.state
                                                                .totalCoupon
                                                        }
                                                    />
                                                    {")"}
                                                </h6>
                                            </div>
                                        )}

                                        {this.state.totalDiscount !== 0 && (
                                            <div class="d-flex align-items-center mb-2">
                                                <h6 class="font-weight-bold mb-1">
                                                    {" "}
                                                    {"Total Discount"}
                                                </h6>
                                                <h6 class="font-weight-bold ml-auto mb-1">
                                                    {"- ("}
                                                    <PriceRender
                                                        currency={currency}
                                                        price={
                                                            this.state
                                                                .totalDiscount
                                                        }
                                                    />
                                                    {")"}
                                                </h6>
                                            </div>
                                        )}
                                    </div>
                                    <div class="p-2 bg-white mb-2">
                                        <div class="d-flex align-items-center mb-2">
                                            <h6 class="font-weight-bold mb-1">
                                                {translation?.menu_total_cost ||
                                                    "Total Cost"}
                                            </h6>
                                            <h6 class="font-weight-bold ml-auto mb-1">
                                                {" "}
                                                <PriceRender
                                                    currency={currency}
                                                    price={
                                                        Number(
                                                            this.state.total
                                                        ) +
                                                        Number(
                                                            this.props
                                                                .service_charge
                                                        ) -
                                                        Number(
                                                            this.state
                                                                .totalDiscount
                                                        ) -
                                                        Number(
                                                            this.state
                                                                .totalCoupon
                                                        )
                                                    }
                                                />{" "}
                                            </h6>
                                        </div>
                                    </div>
                                    <button
                                        disabled={
                                            this.state.button_disabled
                                                ? true
                                                : false
                                        }
                                        // class="disabled"
                                        style={{
                                            width: "100%",
                                            border: "none",
                                            background: "transparent"
                                        }}
                                        className="text-decoration-none "
                                        onClick={() => this.submitForm()}
                                    >
                                        <RoundButton text={"Place Order"} />
                                    </button>
                                </div>
                            </div>
                        </div>

                        <FooterBar translation={translation} active="cart" />
                    </div>
                </div>
            );
        }
    }
}

const mapSateToProps = state => ({
    order_range: state.store.order_range,
    is_location_required: state.store.is_location_required,
    store_latitude: state.store.store_latitude,
    store_latitude: state.store.store_latitude,
    order_range: state.store.order_range,
    pay_first: state.store.pay_first,
    store_name: state.store.store_name,
    service_charge: state.store.service_charge,
    tax: state.store.tax,
    description: state.store.description,
    sliders: state.store.sliders,
    recommendedItems: state.store.recommendedItems,
    account_info: state.store.account_info,
    categories: state.store.categories,
    products: state.store.products,
    cart: state.cart.Items,
    orders: state.orders.Orders,
    tables: state.store.tables,
    addons: state.store.addons,
    translation: state.translation?.active?.data,
    auth: state.auth,
    table: state?.orders?.selectedTable,
    addon_categories: state.store.addon_categories,
    selected_card: state.checkout?.selectedCard,
    checkout_tips: state.checkout?.tips,
    checkout_order: state.checkout?.orders,
    checkout_order: state.checkout?.order
});
export default connect(mapSateToProps, {
    fetchStoreItems,
    addToCart,
    setCart,
    createOrder,
    removeFromCart,
    removeAllFromCart,
    addTipsForCheckout,
    addCardForCheckout
})(Cart);
