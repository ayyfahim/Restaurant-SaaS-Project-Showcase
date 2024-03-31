import {
    SELECT_ORDER,
    PAYMENT_DONE,
    PAYMENT_ORDERS,
    ADD_TIPS,
    ADD_CARD
} from "./types";
import api from "../config/api";

export const selectOrderCheckout = data => dispatch => {
    dispatch({
        type: SELECT_ORDER,
        payload: data
    });
};

export const selectOrdersForCheckout = data => dispatch => {
    dispatch({
        type: PAYMENT_ORDERS,
        payload: data
    });
};

export const addCardForCheckout = data => dispatch => {
    dispatch({
        type: ADD_CARD,
        payload: data
    });
};

export const addTipsForCheckout = data => dispatch => {
    dispatch({
        type: ADD_TIPS,
        payload: data
    });
};

export const createPayment = postData => dispatch => {
    let url = api.store.Payment.create.path;
    fetch(url, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            Authorization:
                "Bearer " + JSON.parse(localStorage.getItem("state")).auth.token
        },
        body: JSON.stringify(postData)
    })
        .then(response => response.json())
        .then(data => {
            if (data.status == "success") {
                console.log(data);
                dispatch({
                    type: PAYMENT_DONE
                });
            }
        });
};
