import React, { useRef } from "react";
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
import ItemTextView from "../Containers/ItemTextView";
import PriceRender from "../Containers/PriceRender";

import domain from "../../config/api/domain";

import {
    Link,
    Element,
    Events,
    animateScroll as scroll,
    scrollSpy,
    scroller
} from "react-scroll";

import { connect } from "react-redux";
import { fetchStoreItems } from "../../actions/storeAction";
import { fetchTable, fetchTableByUser } from "../../actions/orderAction";
import {
    fetchTranslation,
    fetchAllTranslation
} from "../../actions/translationAction";
import { addToCart, setCart } from "../../actions/cartAction";
import ROUTE from "../../config/route";
import FooterBar from "../Containers/FooterBar";
import Category from "../Containers/Category";
import Customization from "../Containers/Customization";
import CallTheWaiter from "../Containers/CallTheWaiter";
import LanguageSwitcher from "../Containers/LanguageSwitcher";
import ErrorModal from "../Containers/ErrorModal";
import CallTheWaiterButton from "../Containers/CallTheWaiterButton";

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

class Store extends React.Component {
    constructor(props) {
        super(props);
        this.topBarRef = React.createRef(null);
        // this.handleLogoLoad(this);
    }

    state = {
        modalText: "Please login to see this page",
        activeFoodMenu: null,
        topBarPadding: null
    };

    notificationSystem = React.createRef();

    componentWillMount() {
        storeId = this.props.match.params.id;

        localStorage.setItem("storeId", storeId);

        let data = {
            view_id: storeId
        };
        this.props.fetchAllTranslation();
        this.props.fetchStoreItems(data);
        this.props.fetchTranslation({
            language_id: JSON.parse(localStorage.getItem("active_language_id")),
            store_id: storeId
        });

        if (this.props.match.params.tableId != null) {
            let data = {
                table_id: this.props.match.params.tableId
            };
            this.props.fetchTable(data);
        } else {
            this.props.fetchTableByUser();
        }
    }

    componentWillReceiveProps(nextProps) {
        if (nextProps.cart != this.props.cart) {
            console.log("next props", nextProps.cart);
            //    localStorage.setItem("cartData",JSON.stringify(nextProps.cart))
        }
    }

    componentDidMount() {
        this.setState({
            topBarPadding: this.topBarRef.current.clientHeight + 10
        });
        // console.log("topBarPadding", topBarPadding);
        const script = document.createElement("script");
        script.id = "";
        script.text = `
            $(document).ready(function(){
                $(".categories-slider")
                .slick({
                    infinite: true,
                    slidesToShow: 3,
                    arrows: false,
                    centerPadding: "10px"
                });
                $(".combo-slider")
                .slick({
                    infinite: true,
                    slidesToShow: 3,
                    arrows: false,
                    centerPadding: "10px"
                });
            });
        `;
        document.body.appendChild(script);
        // console.log(`script`, script);

        Events.scrollEvent.register("begin", function() {
            console.log("begin", arguments);
        });

        Events.scrollEvent.register("end", function() {
            console.log("end", arguments);
        });
    }

    componentWillReceiveProps(nextProps) {
        const notification = this.notificationSystem.current;
        const { food_menus } = nextProps;
        const { activeFoodMenu } = this.state;

        if (food_menus && food_menus[0] !== undefined) {
            if (!activeFoodMenu) {
                this.setState({ activeFoodMenu: food_menus[0]?.id });
            }
        }
    }

    handleSetActive(to) {
        console.log(to);
    }

    AddToCart = (id, isAvailiable, addon = null, extra = null) => {
        const notification = this.notificationSystem.current;

        if (isAvailiable != undefined && !isAvailiable) {
            notification.addNotification({
                message: "Product is not availiable now.",
                level: "error"
            });
            return;
        }

        let { translation } = this.props;

        notification.addNotification({
            message: translation?.item_add_to_cart || "Item Added To Cart",
            level: "success"
        });
        let data = {
            storeId: storeId,
            itemId: id,
            count: 1,
            addon: addon,
            extra: extra
        };
        this.props.addToCart(data);
    };

    handleLogoLoad = event => {
        // this.topBarRef = React.createRef(null);

        // console.log("this.topBarRef", this.topBarRef);
        // console.log("this.topBarRef.current", this.topBarRef.current);
        // console.log(
        //     "topBarRef.current.clientHeight",
        //     this.topBarRef.current.clientHeight
        // );
        this.setState({
            topBarPadding: this.topBarRef.current.clientHeight + 10
        });
        // console.log("topBarPadding", topBarPadding);
    };

    render() {
        let {
            store_name,
            store_logo,
            store_logo_wide,
            description,
            sliders,
            recommendedItems,
            account_info,
            food_menus,
            categories,
            products,
            translation,
            all_Translation,
            active_language_id
        } = this.props;

        let { activeFoodMenu, topBarPadding } = this.state;

        let currency = account_info ? account_info.currency_symbol : "₹";
        let { modalText } = this.state;
        return (
            <div class="fixed-bottom-padding bg-light-gray-2">
                <nav id="ultraNav" className="shadow-sm" ref={this.topBarRef}>
                    <div
                        className="store_info text-center"
                        style={{
                            backgroundImage: `url("${store_logo_wide}")`,
                            backgroundRepeat: "no-repeat",
                            backgroundPosition: "center",
                            backgroundSize: "contain",
                        }}
                    >
                        {store_logo ? (
                            <img
                                src={`${store_logo}`}
                                alt={store_name}
                                class="m-auto img-fluid"
                                style={{
                                    maxWidth: 150
                                }}
                                onLoad={this.handleLogoLoad}
                            />
                        ) : (
                            <h4 class="font-weight-bold m-0">{store_name}</h4>
                        )}
                    </div>
                    <br />
                    <ul
                        className="scrolling-wrapper main-categories sub-categories"
                        style={{ margin: "0 10px", paddingBottom: 5 }}
                    >
                        {food_menus
                            ? food_menus.map((menu, index) => (
                                  <li className="item mx-auto">
                                      <a
                                          className={`nav-link p-3 ${
                                              this.state.activeFoodMenu ==
                                              menu.id
                                                  ? "active"
                                                  : null
                                          }`}
                                          onClick={() => {
                                              this.setState({
                                                  activeFoodMenu: menu.id
                                              });
                                          }}
                                      >
                                          {menu.name}
                                      </a>
                                  </li>
                              ))
                            : null}
                    </ul>
                    <ul
                        className="scrolling-wrapper sub-categories"
                        style={{ margin: "0 15px" }}
                    >
                        {categories
                            ? categories.map(
                                  category =>
                                      activeFoodMenu == category.menu_id &&
                                      category.has_product && (
                                          <li className="item mx-auto">
                                              <Link
                                                  activeClass="active"
                                                  to={`show-category-${category.id}`}
                                                  spy={true}
                                                  smooth={true}
                                                  offset={-190}
                                                  duration={500}
                                                  //   onSetActive={this.handleSetActive}
                                                  //   href={`#show-category-${category.id}`}
                                                  className="nav-link p-2"
                                              >
                                                  {category.name}
                                              </Link>
                                          </li>
                                      )
                              )
                            : null}
                    </ul>
                </nav>

                <br />

                <div
                    className="container"
                    style={{
                        paddingTop: topBarPadding
                    }}
                >
                    <div
                        className="input-group mt-3 rounded shadow-sm overflow-hidden bg-white"
                        style={{ height: "60px" }}
                    >
                        <div className="input-group-prepend">
                            <button className="border-0 btn btn-outline-secondary text-success bg-white">
                                <i className="icofont-search"></i>
                            </button>
                        </div>
                        <input
                            type="text"
                            id="Search"
                            onKeyUp={object => {
                                if (object.target.value.length == 0) {
                                    this.setState({
                                        activeFoodMenu: food_menus[0]?.id
                                    });
                                    return;
                                }

                                if (this.state.activeFoodMenu != null) {
                                    this.setState({ activeFoodMenu: null });
                                }

                                window.searchThroughProducts();
                            }}
                            className="shadow-none border-0 form-control pl-0"
                            placeholder={
                                translation?.search_products ||
                                "Search for Products.."
                            }
                            aria-label=""
                            style={{ marginTop: "15px" }}
                        />
                    </div>
                </div>

                <div
                    class="card h-100-vh"
                    style={{
                        border: "none",
                        backgroundColor: "#f2f2f2",
                        marginTop: 25
                    }}
                >
                    <div class="title px-3">
                        <h4 class="m-0">Combos</h4>
                    </div>
                    <div class="combo-slider px-3">
                        {products
                            ? products.map(
                                  product =>
                                      product.type == 2 && (
                                          <div
                                              class="combo-slider-item bg-white item-img search"
                                              onClick={() =>
                                                  (window.location.href = `${ROUTE.STORE.INDEX.PAGES.DETAILED.PATH}/${storeId}/combo/details/${product.id}`)
                                              }
                                          >
                                              <div class="block1">
                                                  <div class="block-content1 recommended-item-content rec-v2-1">
                                                      <img
                                                          src={`${
                                                              domain.s3_url
                                                          }/${
                                                              product.image_url !=
                                                              null
                                                                  ? product.image_url
                                                                  : "themes/default/images/all-img/empty.png"
                                                          }`}
                                                          alt={product.name}
                                                          class="img-fluid"
                                                      />
                                                      <div class="my-2 recommended-item-meta">
                                                          <div class="text-left recommended-v2-ellipsis-meta">
                                                              <div class="d-flex align-items-center">
                                                                  <span class="m-0 combo-title">
                                                                      {
                                                                          product.name
                                                                      }
                                                                  </span>
                                                              </div>
                                                          </div>
                                                          <div class="d-flex align-items-center">
                                                              <p class="total_price font-weight-bold m-0">
                                                                  <h6 className="price m-0">
                                                                      <PriceRender
                                                                          currency={
                                                                              currency
                                                                                  ? currency
                                                                                  : "₹"
                                                                          }
                                                                          price={
                                                                              product.price
                                                                          }
                                                                      />
                                                                  </h6>
                                                              </p>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      )
                              )
                            : null}
                    </div>

                    <div class="title px-3">
                        <h4 class="m-0">
                            {translation?.menu_recommend || "Recommend for You"}
                        </h4>
                    </div>
                    <div class="trending-slider px-3">
                        {recommendedItems
                            ? recommendedItems.map((data, index) => (
                                  <ItemCardView
                                      index={index}
                                      translation={translation}
                                      addon={data.addon_items}
                                      category_id={data.category_id}
                                      more={`${ROUTE.STORE.INDEX.PAGES.DETAILED.PATH}/${storeId}/product/details/${data.id}`}
                                      name={data.name}
                                      IsAddToEnable={this.props.is_accept_order}
                                      AddToCart={this.AddToCart}
                                      currency={currency}
                                      price={data.price}
                                      photo={data.image_url}
                                      id={data.id}
                                      description={data.description}
                                  />
                              ))
                            : null}
                    </div>

                    {categories
                        ? categories.map(
                              category =>
                                  activeFoodMenu == category.menu_id &&
                                  category.has_product && (
                                      <div
                                          id={`show-category-${category.id}`}
                                          className="row align-items-center justify-content-center m-0"
                                      >
                                          <div class="title search mb-3 mt-3 px-3 col-7 col-sm-5">
                                              <h4 class="m-0">
                                                  {category.name}
                                              </h4>
                                          </div>
                                          <div className="col col-md-3 col-lg-2"></div>
                                          <div className="pick_today px-3">
                                              <div className="row pt-3">
                                                  {products
                                                      ? products.map(
                                                            (data, index) =>
                                                                category.id ==
                                                                    data.category_id &&
                                                                data.is_availiable ==
                                                                    true && (
                                                                    <ItemTextView
                                                                        index={
                                                                            index
                                                                        }
                                                                        translation={
                                                                            translation
                                                                        }
                                                                        addon={
                                                                            data.addon_items
                                                                        }
                                                                        category_id={
                                                                            data.category_id
                                                                        }
                                                                        more={`${ROUTE.STORE.INDEX.PAGES.DETAILED.PATH}/${storeId}/product/details/${data.id}`}
                                                                        name={
                                                                            data.name
                                                                        }
                                                                        IsRecommended={
                                                                            data.is_recommended
                                                                        }
                                                                        IsAddToEnable={
                                                                            this
                                                                                .props
                                                                                .is_accept_order
                                                                        }
                                                                        AddToCart={
                                                                            this
                                                                                .AddToCart
                                                                        }
                                                                        currency={
                                                                            currency
                                                                        }
                                                                        price={
                                                                            data.price
                                                                        }
                                                                        photo={
                                                                            data.image_url
                                                                        }
                                                                        id={
                                                                            data.id
                                                                        }
                                                                        description={
                                                                            data.description
                                                                        }
                                                                        isAvailiable={
                                                                            data.is_availiable
                                                                        }
                                                                        allergens={
                                                                            data.allergens
                                                                        }
                                                                        customer_allergens={
                                                                            this
                                                                                .props
                                                                                .customer_allergens
                                                                        }
                                                                    />
                                                                )
                                                        )
                                                      : null}
                                              </div>
                                          </div>
                                      </div>
                                  )
                          )
                        : null}

                    {categories
                        ? categories.map(
                              category =>
                                  activeFoodMenu == null &&
                                  category.has_product && (
                                      <div
                                          id={`show-category-${category.id}`}
                                          className="row align-items-center justify-content-center m-0"
                                      >
                                          <div class="title search mb-3 mt-3 px-3 col-7 col-sm-5">
                                              <h4 class="m-0">
                                                  {category.name}
                                              </h4>
                                          </div>
                                          <div className="col col-md-3 col-lg-2"></div>
                                          <div className="pick_today px-3">
                                              <div className="row pt-3">
                                                  {products
                                                      ? products.map(
                                                            (data, index) =>
                                                                category.id ==
                                                                    data.category_id &&
                                                                data.is_availiable ==
                                                                    true && (
                                                                    <ItemTextView
                                                                        index={
                                                                            index
                                                                        }
                                                                        translation={
                                                                            translation
                                                                        }
                                                                        addon={
                                                                            data.addon_items
                                                                        }
                                                                        category_id={
                                                                            data.category_id
                                                                        }
                                                                        more={`${ROUTE.STORE.INDEX.PAGES.DETAILED.PATH}/${storeId}/product/details/${data.id}`}
                                                                        name={
                                                                            data.name
                                                                        }
                                                                        IsRecommended={
                                                                            data.is_recommended
                                                                        }
                                                                        IsAddToEnable={
                                                                            this
                                                                                .props
                                                                                .is_accept_order
                                                                        }
                                                                        AddToCart={
                                                                            this
                                                                                .AddToCart
                                                                        }
                                                                        currency={
                                                                            currency
                                                                        }
                                                                        price={
                                                                            data.price
                                                                        }
                                                                        photo={
                                                                            data.image_url
                                                                        }
                                                                        id={
                                                                            data.id
                                                                        }
                                                                        description={
                                                                            data.description
                                                                        }
                                                                        isAvailiable={
                                                                            data.is_availiable
                                                                        }
                                                                        allergens={
                                                                            data.allergens
                                                                        }
                                                                        customer_allergens={
                                                                            this
                                                                                .props
                                                                                .customer_allergens
                                                                        }
                                                                    />
                                                                )
                                                        )
                                                      : null}
                                              </div>
                                          </div>
                                      </div>
                                  )
                          )
                        : null}
                </div>
                {/* <button onClick={()=>document.getElementById('#customization').click()}>TEST</button> */}

                <CallTheWaiter
                    translation={translation}
                    tables={this.props.tables}
                    store_id={storeId}
                    tableId={this.props.table.id}
                />

                {this.props.is_accept_order ? (
                    <FooterBar translation={translation} active="home" />
                ) : null}
                <ErrorModal text={modalText} />
                {this.props.isLogin && <CallTheWaiterButton />}
            </div>
        );
    }
}

const mapSateToProps = state => ({
    store_name: state.store.store_name,
    store_logo: state.store.store_logo,
    store_logo_wide: state.store.store_logo_wide,
    description: state.store.description,
    sliders: state.store.sliders,
    recommendedItems: state.store.recommendedItems,
    account_info: state.store.account_info,
    food_menus: state.store.food_menus,
    categories: state.store.categories,
    products: state.store.products,
    cart: state.cart.Items,
    is_accept_order: state.store.is_accept_order,
    tables: state.store.tables,
    translation: state.translation?.active?.data,
    all_Translation: state.translation?.languages,
    active_language_id: state.translation?.active?.id,
    isLogin: state.auth?.isLogin,
    customer_allergens: state.auth?.allergens,
    table: state?.orders?.selectedTable
});

export default connect(mapSateToProps, {
    fetchStoreItems,
    addToCart,
    setCart,
    fetchTranslation,
    fetchTable,
    fetchTableByUser,
    fetchAllTranslation
})(Store);
