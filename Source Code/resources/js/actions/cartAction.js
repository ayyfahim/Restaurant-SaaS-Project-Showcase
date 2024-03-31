import { ADD_TO_CART, REMOVE_FROM_CART, EMPTY_CART } from "./types";

export const addToCart = Item => dispatch => {
    dispatch({
        type: ADD_TO_CART,
        data: Item
    });
};

export const setCart = Item => dispatch => {
    dispatch({
        type: ADD_TO_CART,
        data: Item
    });
};

export const removeFromCart = (Id, addon) => dispatch => {
    dispatch({
        type: REMOVE_FROM_CART,
        data: Id,
        addon: addon
    });
};

export const removeAllFromCart = Id => dispatch => {
    dispatch({
        type: EMPTY_CART,
        data: []
    });
};
