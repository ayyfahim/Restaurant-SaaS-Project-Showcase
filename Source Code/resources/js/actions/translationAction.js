import { FETCH_TRANSLATION, FETCH_ALL_TRANSLATION } from "./types";
import api from "../config/api";
export const fetchTranslation = postData => dispatch => {
    let url = api.store.Translation.fetch.path;
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
            console.log(data);
            if (data.status == "success") {
                // console.log(data)
                dispatch({
                    type: FETCH_TRANSLATION,
                    payload: data.payload
                });
            }
        });
};
export const fetchAllTranslation = postData => dispatch => {
    let url = api.store.All_Translation.fetch.path;
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
            console.log("yaa", data);
            if (data.status == "success") {
                dispatch({
                    type: FETCH_ALL_TRANSLATION,
                    payload: data.payload
                });
            }
        });
};
