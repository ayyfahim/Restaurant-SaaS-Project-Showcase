import { combineReducers } from "redux";
import AuthReducer from "./authReducer";
import storeReducer from "./storeReducer";
import cartReducer from "./cartReducer";
import orderReducer from "./orderReducer";
import translationReducer from "./translationReducer";
import fetchReducer from "./fetchReducer";
import checkoutReducer from "./checkoutReducer";
import allergenReducer from "./allergenReducer";

export default combineReducers({
    auth: AuthReducer,
    store: storeReducer,
    cart: cartReducer,
    orders: orderReducer,
    translation: translationReducer,
    fetch: fetchReducer,
    checkout: checkoutReducer,
    allergens: allergenReducer
});
