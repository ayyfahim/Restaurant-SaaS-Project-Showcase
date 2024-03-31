import { CREATE_ORDER, FETCH_TABLE, LEAVE_TABLE, SELECT_ORDER } from "./types";
import api from "../config/api";

export const createOrder = postData => dispatch => {
    let url = api.store.CerateOrder.cerate.path;
    console.log(url);
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
                dispatch({
                    type: CREATE_ORDER,
                    payload: data.payload
                });
                dispatch({
                    type: SELECT_ORDER,
                    payload: data.payload
                });
            }
        });
};

export const fetchOrders = postData => dispatch => {
    let url = api.store.FetchOrder.fetch.path;
    console.log(url);
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
                dispatch({
                    type: CREATE_ORDER,
                    payload: data.payload
                });
            }
        });
};

export const fetchTableOrders = postData => dispatch => {
    let url = api.store.FetchTableOrder.fetch.path;
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
            if (data.status == "success") {
                dispatch({
                    type: CREATE_ORDER,
                    payload: data.payload
                });
            }
        });
};

export const fetchTableByUser = () => dispatch => {
    let url = api.store.Table.fetchByUser.path;
    fetch(url, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            Authorization:
                "Bearer " +
                JSON.parse(localStorage.getItem("state"))?.auth?.token
        },
        body: JSON.stringify({})
    })
        .then(response => response.json())
        .then(data => {
            if (data.status == "success") {
                dispatch({
                    type: FETCH_TABLE,
                    payload: data.payload
                });
            } else {
                leaveTable({})(dispatch);
            }
        })
        .catch(error => {
            leaveTable({})(dispatch);
        });
};

export const fetchTable = postData => dispatch => {
    let url = api.store.Table.fetch.path;
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
                dispatch({
                    type: FETCH_TABLE,
                    payload: data.payload
                });
            } else {
                leaveTable(JSON.stringify(postData))(dispatch);
            }
        })
        .catch(error => {
            leaveTable(JSON.stringify(postData))(dispatch);
        });
};

export const leaveTable = postData => dispatch => {
    let url = api.store.Table.leave.path;
    console.log("url", url);
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
                dispatch({
                    type: LEAVE_TABLE
                });
            }
        })
        .catch(error => {
            // dispatch({
            //     type: LEAVE_TABLE
            // });
        });
};
