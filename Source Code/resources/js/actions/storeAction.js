import { FETCH_STORE_ITEMS, FETCH_ERROR, FETCH_REFRESH } from "./types";
import api from "../config/api";
import ROUTE from "../config/route";

export const fetchStoreItems = postData => dispatch => {
    let url = api.store.StoreItems.fetch.path;
    console.log(url);
    fetch(url, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json"
        },
        body: JSON.stringify(postData)
    })
        .then(response => response.json())
        .then(data => {
            console.log(`data`, data);
            if (data?.success == false) {
                window.location.href = `${ROUTE.STORE.HOME.PAGES.VIEW.PATH}`;
                return;
            }

            dispatch({
                type: FETCH_STORE_ITEMS,
                payload: data.payload
            });
        })
        .catch(error => {
            console.log(`error`, error);
            // window.location.href = `${ROUTE.STORE.HOME.PAGES.VIEW.PATH}`;
            return;
        });
};
export const callTheWaiter = postData => dispatch => {
    let url = api.store.Waiter.call.path;
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
            if (data.status == "failed") {
                dispatch({ type: FETCH_ERROR, payload: data });
            } else {
                dispatch({ type: FETCH_REFRESH, payload: data });
            }
        });
};
