import React from 'react';
import ReactDOM from 'react-dom';
import { ToastProvider, useToasts } from 'react-toast-notifications'
import NotificationSystem from 'react-notification-system';
import Header from '../../components/Header';
import SideBar from '../../components/SideBar';
import Tag from '../Containers/Tag';
import Slider from '../Containers/Slider';
import OfferTab from '../Containers/OfferTab';
import ItemCardView from '../Containers/ItemCardView';
import ItemFullView from '../Containers/ItemFullView';

import { connect } from 'react-redux';
import { fetchStoreItems } from '../../actions/storeAction'
import { addToCart, setCart } from '../../actions/cartAction'
import ROUTE from "../../config/route";
import FooterBar from '../Containers/FooterBar';
import Category from '../Containers/Category';
import domain from '../../config/api/domain';
import Customization from '../Containers/Customization';
let storeId = null
var style = {
    NotificationItem: { // Override the notification item
        DefaultStyle: { // Applied to every notification, regardless of the notification level
            margin: '10px 5px 2px 1px',
            background: "#fff",
        },
        success: {
            color: 'black'
        }
    }
}
class DetailedCategoryView extends React.Component {
    state = {
        product: {}
    }
    notificationSystem = React.createRef();
    componentWillMount() {
        // console.log("product",this.props.match.params.product_id)
        let DetailedItem = this.props.products.filter(data => data.id == this.props.match.params.product_id)
        this.setState({ product: DetailedItem })
        storeId = this.props.match.params.store_id
        localStorage.setItem('storeId', storeId)
        let cartData = JSON.parse(localStorage.getItem('cartData'))
        let data = {
            view_id: storeId
        }
        this.props.fetchStoreItems(data);
    }
    componentWillReceiveProps(nextProps) {
        if (nextProps.cart != this.props.cart) {
            console.log("next props", nextProps.cart)
            //    localStorage.setItem("cartData",JSON.stringify(nextProps.cart))
        }
    }
   addToCart = (id,isCustomizable)=>{
        if(!isCustomizable)
            this.AddToCart(id)
        else{
            document.getElementById(`#customization-${id}`).click()
        }
    }
    AddToCart = (id,addon=null,extra=null) => {
        let { translation} = this.props;
        const notification = this.notificationSystem.current;
        notification.addNotification({
            message: translation?.item_add_to_cart||"Item Added To Cart",
            level: 'success'
        });
        let data = {
            storeId: storeId,
            itemId: id,
            count: 1,
            addon:addon,
            extra:extra
        }
        this.props.addToCart(data)
    }

    render() {
        console.log("Product",)
        let { store_name, description, sliders, recommendedItems, account_info, categories, products ,translation} = this.props;
        let currency = account_info ? account_info.currency_symbol : "₹"
        let category_product = products.filter(data=> data.category_id == this.props.match.params.category_id)
        let category = categories.filter(data=> data.id == this.props.match.params.category_id)

        return (

                <div className="red-bg">

                <div class="p-3">
                    <div class="d-flex align-items-center">

                        <a class="font-weight-bold text-white text-decoration-none" onClick={() => window.history.back(-1)}><i class="icofont-rounded-left back-page"></i> {translation?.back_to_menu||"Back"} </a>
                    </div>
                    {/* <SideBar active="Home" store_id={storeId}/>*/}
                    <NotificationSystem ref={this.notificationSystem} style={style} />

                </div>
                <div class="px-3 pb-3">
                    <div class="pt-0">
                    <h2 class="font-weight-bold text-white text-center">{category[0].name}</h2>
                    </div>
                    <div class="pt-2">
                </div>
                </div>
                <div  style={{backgroundColor: "#ffffff", borderTopLeftRadius: "35px", borderTopRightRadius: "35px",  boxShadow: "0px -4px 12px -6px #9E9E9E"}}>

                    <br/><div class="title d-flex align-items-center mb-3 mt-3 px-3">
                        <h6 class="m-0"> {translation?.menu_category_items||"Items"} </h6>
                    </div>
                    <div className="pick_today px-3">
                        <div className="row pt-3">
                            {
                                 category_product ?
                                 category_product.map((data,index) =>
                                        <ItemFullView
                                            index={index}
                                            addon={data.addon_items}
                                            more={`${ROUTE.STORE.INDEX.PAGES.DETAILED.PATH}/${storeId}/product/details/${data.id}`}
                                            name={data.name} IsRecommended={data.is_recommended}
                                            IsAddToEnable={this.props.is_accept_order} AddToCart={this.AddToCart}
                                            currency={currency} price={data.price} photo={data.image_url} id={data.id}
                                            description={data.description}
                                            translation={translation}
                                            />
                                    ) : null}
                        </div>
                    </div>
                </div>
            </div>



        );
    }
}

const mapSateToProps = state => ({
    store_name: state.store.store_name,
    description: state.store.description,
    sliders: state.store.sliders,
    recommendedItems: state.store.recommendedItems,
    account_info: state.store.account_info,
    categories: state.store.categories,
    products: state.store.products,
    cart: state.cart.Items,
    is_accept_order: state.store.is_accept_order,
    translation:state.translation?.active?.data,
})
export default connect(mapSateToProps, { fetchStoreItems, addToCart, setCart })(DetailedCategoryView);


