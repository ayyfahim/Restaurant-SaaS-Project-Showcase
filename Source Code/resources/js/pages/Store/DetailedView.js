import React from "react";
import ReactDOM from "react-dom";
import { ToastProvider, useToasts } from "react-toast-notifications";
import NotificationSystem from "react-notification-system";
import Header from "../../components/Header";
import SideBar from "../../components/SideBar";
import Tag from "../Containers/Tag";
import Slider from "../Containers/Slider";
import OfferTab from "../Containers/OfferTab";
import ItemCardView from "../Containers/ItemCardView";
import ItemFullView from "../Containers/ItemFullView";

import { connect } from "react-redux";
import { fetchStoreItems } from "../../actions/storeAction";
import { addToCart, setCart } from "../../actions/cartAction";
import ROUTE from "../../config/route";
import FooterBar from "../Containers/FooterBar";
import Category from "../Containers/Category";
import domain from "../../config/api/domain";
import Customization from "../Containers/Customization";
import CustomizationTwo from "../Containers/BlockCustomization";
import PriceRender from "../Containers/PriceRender";
import ReactTooltip from "react-tooltip";
import { returnBackToStore } from "../../helpers/storeHelpers";

let storeId = null;
var style = {
    NotificationItem: {
        // Override the notification item
        DefaultStyle: {
            // Applied to every notification, regardless of the notification level
            margin: "10px 5px 2px 1px",
            background: "#fff"
        },
        success: {
            color: "black"
        }
    }
};

class DetailedView extends React.Component {
    state = {
        product: {}
    };
    notificationSystem = React.createRef();
    componentWillMount() {
        // console.log("hersdsdsde")

        // console.log(this.props.products.filter(data => data.id == this.props.match.params.product_id))
        storeId = this.props.match.params.store_id;
        localStorage.setItem("storeId", storeId);
        let cartData = JSON.parse(localStorage.getItem("cartData"));
        let data = {
            view_id: storeId
        };
        this.props.fetchStoreItems(data);
    }

    componentWillReceiveProps(nextProps) {
        if (nextProps.cart != this.props.cart) {
            // console.log("next props", nextProps.cart)
            //    localStorage.setItem("cartData",JSON.stringify(nextProps.cart))
        }
        if (this.props.products != nextProps.products) {
            let DetailedItem = nextProps.products.filter(
                data => data.id == this.props.match.params.product_id
            );
            this.setState({ product: DetailedItem }, () => {
                this.checkProductImage();
            });
        }
    }

    addToCart = (id, isCustomizable, index, isAvailiable = true) => {
        if (!isCustomizable) this.AddToCart(id, isAvailiable);
        else {
            document.getElementById(`#customization-${id}-${index}`).click();
        }
    };

    AddToCart = (
        id,
        isAvailiable = true,
        addon = null,
        extra = null,
        nestedAddon = {},
        redirect = true
    ) => {
        let { translation } = this.props;
        const notification = this.notificationSystem.current;

        if (isAvailiable != undefined && !isAvailiable) {
            notification.addNotification({
                message: "Product is not availiable now.",
                level: "error"
            });
            return;
        }

        notification.addNotification({
            message: translation?.item_add_to_cart || "Item Added To Cart",
            level: "success"
        });

        let data = {
            storeId: storeId,
            itemId: id,
            count: 1,
            addon: addon,
            extra: extra,
            nestedAddon: nestedAddon
        };
        this.props.addToCart(data);

        if (redirect) {
            returnBackToStore();
        }
    };

    checkProductImage() {
        if (this.state?.product[0]?.image_url == null) {
            window.location.href = `/store/${localStorage.getItem("storeId")}`;
        }
    }

    render() {
        console.log("Product");
        let {
            store_name,
            description,
            sliders,
            recommendedItems,
            account_info,
            categories,
            products,
            translation
        } = this.props;
        let currency = account_info ? account_info.currency_symbol : "₹";

        return (
            <div>
                <ReactTooltip clickable={true} />
                <div
                    className="card1"
                    style={{
                        position: "relative"
                    }}
                >
                    <div className="header">
                        <a
                            className="font-weight-bold text-white text-decoration-none"
                            onClick={() => window.history.back(-1)}
                        >
                            <i
                                className="icofont-close back-page"
                                style={{
                                    paddingLift: "10px",
                                    position: "absolute",
                                    top: "15px",
                                    left: "15px",
                                    background: "red",
                                    borderRadius: "50%",
                                    padding: "15px",
                                    fontSize: "25px"
                                }}
                            ></i>
                        </a>

                        {/* <SideBar active="Home" store_id={storeId}/>*/}
                        <NotificationSystem
                            ref={this.notificationSystem}
                            style={style}
                        />

                        <img
                            src={`${domain.s3_url}/${
                                this.state?.product[0]?.image_url != null
                                    ? this.state?.product[0]?.image_url
                                    : "themes/default/images/all-img/empty.png"
                            }`}
                            alt="Responsive image"
                            width="100%"
                            style={{ marginBottom: "00px" }}
                        />
                    </div>
                </div>

                <div
                    className="fixed-bottom-padding"
                    style={{
                        marginTop: "-25px"
                    }}
                >
                    <div
                        class="px-4 pb-3 descdeatils"
                        style={{
                            "border-top-right-radius": "30px",
                            "border-top-left-radius": "30px"
                        }}
                    >
                        <div class="pt-3">
                            <h2 style={{ fontSize: "2.2rem" }}>
                                {this.state?.product[0]?.name}
                            </h2>
                            <p
                                className="text-muted small"
                                style={{
                                    whiteSpace: "pre-line",
                                    fontSize: 15
                                }}
                            >
                                {this.state?.product[0]?.description}
                            </p>
                            <p class="font-weight-bold">
                                <PriceRender
                                    currency={currency ? currency : "₹"}
                                    price={this.state?.product[0]?.price}
                                />
                            </p>

                            <p class="font-weight-light text-dark m-0 d-flex align-items-center">
                                <span class="badge badge-danger">
                                    {this.state?.product[0]?.is_availiable !=
                                        undefined &&
                                    !this.state?.product[0]?.is_availiable
                                        ? translation?.not_available ||
                                          "NOT AVAILABLE"
                                        : translation?.available || "AVAILABLE"}
                                </span>
                                {this.state?.product[0]?.is_recommended == 1 ? (
                                    <span class="badge badge-success ml-2">
                                        {" "}
                                        {translation?.recommended ||
                                            "RECOMMENDED"}
                                    </span>
                                ) : null}
                            </p>
                            {this.state?.product[0]?.allergens && (
                                <div className="my-3">
                                    {this.state?.product[0]?.allergens.map(
                                        (data, index) => (
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
                                        )
                                    )}
                                </div>
                            )}
                            <a href="#">
                                <div class="rating-wrap d-flex align-items-center mt-2"></div>
                            </a>
                        </div>

                        {this.state?.product[0]?.allergens && (
                            <div class="pb-3">
                                <div class="row">
                                    <div class="col-12">
                                        <p class="font-weight-bold m-0">
                                            Contains:{" "}
                                            {this.state?.product[0]?.allergens.map(
                                                (data, index) => (
                                                    <>
                                                        {data.type == 1 && (
                                                            <img
                                                                src={`${domain.url}/${data.active_image_url}`}
                                                                alt={data.name}
                                                                className="img-fluid"
                                                                style={{
                                                                    width: 20
                                                                }}
                                                                data-tip={
                                                                    data.name
                                                                }
                                                            />
                                                        )}
                                                    </>
                                                )
                                            )}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        )}

                        <div>
                            <CustomizationTwo
                                index={"DL"}
                                translation={translation}
                                currency={currency ? currency : "₹"}
                                SaveAddon={this.AddToCart}
                                addon={this.state?.product[0]?.addon_items}
                                all_addons={this.props.all_addons}
                                all_addon_categories={
                                    this.props.addon_categories
                                }
                                isAvailiable={
                                    this.state?.product[0]?.is_availiable
                                }
                                isCustomizable={
                                    this.state?.product[0]?.addon_items &&
                                    this.state?.product[0]?.addon_items.length
                                }
                                AddToCart={this.AddToCart}
                                productID={this.state?.product[0]?.id}
                                notification={this.notificationSystem.current}
                            />
                        </div>

                        <div class="title d-flex align-items-center mb-3 mt-3">
                            <h6 class="m-0">
                                {translation?.menu_maybe_you_likethis ||
                                    "Maybe You Like this."}
                            </h6>
                        </div>
                        <div className="pick_today ">
                            <div className="row pt-3">
                                {recommendedItems
                                    ? recommendedItems.map((data, index) => (
                                          <ItemFullView
                                              index={index}
                                              addon={data?.addon_items}
                                              newAddon={
                                                  this.state?.product[0]
                                                      ?.addon_items
                                              }
                                              more={`${ROUTE.STORE.INDEX.PAGES.DETAILED.PATH}/${storeId}/product/details/${data.id}`}
                                              name={data.name}
                                              IsRecommended={
                                                  data.is_recommended
                                              }
                                              IsAddToEnable={
                                                  this.props.is_accept_order
                                              }
                                              AddToCart={this.AddToCart}
                                              currency={currency}
                                              price={data.price}
                                              photo={data.image_url}
                                              id={data.id}
                                              description={data.description}
                                              translation={translation}
                                              all_addons={this.props.all_addons}
                                              all_addon_categories={
                                                  this.props.addon_categories
                                              }
                                              notification={
                                                  this.notificationSystem
                                                      .current
                                              }
                                              isCustomizable={
                                                  this.state?.product[0]
                                                      ?.addon_items &&
                                                  this.state?.product[0]
                                                      ?.addon_items.length
                                                      ? true
                                                      : false
                                              }
                                          />
                                      ))
                                    : null}
                            </div>
                        </div>
                    </div>
                </div>
                <FooterBar translation={translation} />
            </div>
        );
    }
}

const mapSateToProps = state => ({
    addon_categories: state.store.addon_categories,
    store_name: state.store.store_name,
    description: state.store.description,
    sliders: state.store.sliders,
    recommendedItems: state.store.recommendedItems,
    account_info: state.store.account_info,
    categories: state.store.categories,
    products: state.store.products,
    all_addons: state.store.addons,
    cart: state.cart.Items,
    is_accept_order: state.store.is_accept_order,
    translation: state.translation?.active?.data,
    isLogin: state.auth?.isLogin
});
export default connect(mapSateToProps, { fetchStoreItems, addToCart, setCart })(
    DetailedView
);
