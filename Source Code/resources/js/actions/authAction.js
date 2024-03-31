import {
    LOGIN_CUSTOMER,
    REGISTER_CUSTOMER,
    RESTART_AUTH_RESPONSE,
    LOGOUT_CUSTOMER,
    FETCH_ERROR,
    FETCH_SUCCESS,
    FETCH_REFRESH,
    UPDATE_CUSTOMER,
    FETCH_ALLERGEN,
    UPDATE_CUSTOMER_ALLERGENS,
    ANONYMOUS_LOGIN_CUSTOMER,
    REFRESH_ALLERGEN
} from "./types";
import api from "../config/api";
import miscVariables from "../helpers/misc";

export const registerCustomer = (postData, history) => dispatch => {
    let url = api.customer.auth.register.path;
    dispatch({ type: RESTART_AUTH_RESPONSE });
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
                console.log(data.status);

                dispatch({
                    type: FETCH_REFRESH
                });

                dispatch({
                    type: ANONYMOUS_LOGIN_CUSTOMER,
                    payload: data.payload
                });

                dispatch({
                    type: REFRESH_ALLERGEN
                });

                dispatch({
                    type: FETCH_ALLERGEN,
                    payload: data.payload
                });

                dispatch({
                    type: FETCH_SUCCESS,
                    payload: data
                });
            } else {
                // console.log("error:", data);

                dispatch({ type: FETCH_ERROR, payload: data });
            }
        })
        .catch(error => {
            // console.log("error:", error);

            dispatch({ type: FETCH_ERROR, payload: error });
        });
};

export const loginCustomer = (postData, history) => dispatch => {
    let url = api.customer.auth.login.path;
    dispatch({ type: RESTART_AUTH_RESPONSE });
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
                    type: FETCH_REFRESH
                });

                dispatch({
                    type: LOGIN_CUSTOMER,
                    payload: data.payload
                });

                // setTimeout(() => {
                //     history.push("/account/login");
                // }, 3000);
            } else {
                // console.log("data", data);
                dispatch({ type: FETCH_ERROR, payload: data });
            }
        })
        .catch(error => {
            // console.log("error:", error);
            dispatch({ type: FETCH_ERROR, payload: error });
        });
};

export const loginCustomerWithCallback = (
    postData,
    url,
    history
) => dispatch => {
    dispatch({ type: RESTART_AUTH_RESPONSE });
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
                    type: FETCH_REFRESH
                });

                dispatch({
                    type: LOGIN_CUSTOMER,
                    payload: data.payload
                });
            } else {
                console.log("data", data);
                dispatch({ type: FETCH_ERROR, payload: data });
            }
        })
        .catch(error => {
            console.log("error:", error);
            dispatch({ type: FETCH_ERROR, payload: error });
        });
};

export const loginCustomerUsingSocial = (postData, socialType) => dispatch => {
    var url = api.customer.auth.loginGoogle.path + postData;

    if (socialType == "facebook") {
        var url = api.customer.auth.loginFacebook.path + postData;
    } else if (socialType == "google") {
        var url = api.customer.auth.loginGoogle.path + postData;
    }

    dispatch({ type: RESTART_AUTH_RESPONSE });
    fetch(url, {
        method: "GET",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json"
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.status == "success") {
                dispatch({
                    type: FETCH_REFRESH
                });

                dispatch({
                    type: LOGIN_CUSTOMER,
                    payload: data.payload
                });
            } else {
                console.log("data", data);
                let errorData = {
                    message: "Sorry an error occured."
                };
                dispatch({ type: FETCH_ERROR, payload: errorData });
            }
        })
        .catch(error => {
            console.log("error:", error);
            let errorData = {
                message: "Sorry an error occured."
            };
            dispatch({ type: FETCH_ERROR, payload: errorData });
        });
};

export const updateCustomer = postData => dispatch => {
    let url = api.customer.auth.update.path;
    dispatch({ type: RESTART_AUTH_RESPONSE });
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
                    type: FETCH_REFRESH
                });

                dispatch({
                    type: UPDATE_CUSTOMER,
                    payload: data.payload
                });

                dispatch({
                    type: FETCH_SUCCESS,
                    payload: data
                });
            } else {
                console.log("data", data);
                dispatch({ type: FETCH_ERROR, payload: data });
            }
        })
        .catch(error => {
            console.log("error:", error);
            dispatch({ type: FETCH_ERROR, payload: error });
        });
};

export const updateCustomerPassword = (postData, history) => dispatch => {
    let url = api.customer.auth.updatePassword.path;
    dispatch({ type: RESTART_AUTH_RESPONSE });
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
                    type: FETCH_REFRESH
                });

                dispatch({
                    type: UPDATE_CUSTOMER,
                    payload: data.payload
                });

                dispatch({
                    type: FETCH_SUCCESS
                });

                setTimeout(() => {
                    history.replace({
                        pathname: "/account/login"
                    });
                }, 10000);
            } else {
                console.log("data", data);
                dispatch({ type: FETCH_ERROR, payload: data });
            }
        })
        .catch(error => {
            console.log("error:", error);
            dispatch({ type: FETCH_ERROR, payload: error });
        });
};

export const sendOtpToEmail = (postData, history) => dispatch => {
    let url = api.customer.auth.sendOtpToEmail.path;
    dispatch({ type: RESTART_AUTH_RESPONSE });
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
                    type: FETCH_REFRESH
                });

                dispatch({
                    type: FETCH_SUCCESS,
                    payload: {
                        message: "otp_sent_to_email"
                    }
                });

                return;
            } else {
                console.log("data", data);
                dispatch({ type: FETCH_ERROR, payload: data });
            }
        })
        .catch(error => {
            console.log("error:", error);
            dispatch({ type: FETCH_ERROR, payload: error });
        });
};

export const verifyOtpEmail = (postData, history) => dispatch => {
    let url = api.customer.auth.verifyOtpEmail.path;
    dispatch({ type: RESTART_AUTH_RESPONSE });
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
                    type: FETCH_REFRESH
                });

                dispatch({
                    type: LOGIN_CUSTOMER,
                    payload: data.payload
                });

                return;
            } else {
                console.log("data", data);
                dispatch({ type: FETCH_ERROR, payload: data });
            }
        })
        .catch(error => {
            console.log("error:", error);
            dispatch({ type: FETCH_ERROR, payload: error });
        });
};

export const logoutCustomer = () => dispatch => {
    let url = api.customer.auth.logout.path;
    dispatch({ type: RESTART_AUTH_RESPONSE });
    fetch(url, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            Authorization:
                "Bearer " + JSON.parse(localStorage.getItem("state")).auth.token
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.status == "success") {
                dispatch({
                    type: FETCH_REFRESH
                });

                dispatch({
                    type: LOGOUT_CUSTOMER,
                    payload: data.payload
                });
            } else {
                console.log("data", data);
                dispatch({ type: FETCH_ERROR, payload: data });
            }
        })
        .catch(error => {
            console.log("error:", error);
            dispatch({ type: FETCH_ERROR, payload: error });
        });
};

export const refreshUser = () => dispatch => {
    let url = api.customer.auth.me.path;
    // dispatch({ type: RESTART_AUTH_RESPONSE });
    fetch(url, {
        method: "GET",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            Authorization:
                "Bearer " + JSON.parse(localStorage.getItem("state")).auth.token
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.status == "success") {
                dispatch({
                    type: FETCH_REFRESH
                });

                dispatch({
                    type: UPDATE_CUSTOMER,
                    payload: data.payload
                });

                dispatch({
                    type: FETCH_SUCCESS
                });
            } else {
                console.log("data", data);
                dispatch({
                    type: FETCH_REFRESH
                });
                dispatch({ type: FETCH_ERROR, payload: data });
                dispatch({
                    type: LOGOUT_CUSTOMER,
                    payload: data.payload
                });
            }
        })
        .catch(error => {
            console.log("error:", error);
            dispatch({
                type: FETCH_REFRESH
            });
            dispatch({ type: FETCH_ERROR, payload: error });
            dispatch({
                type: LOGOUT_CUSTOMER,
                payload: data.payload
            });
        });
};

export const fetchAllergens = () => dispatch => {
    let url = api.allergen.fetch.path;
    fetch(url, {
        method: "GET",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            Authorization:
                "Bearer " + JSON.parse(localStorage.getItem("state")).auth.token
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.status == "success") {
                dispatch({
                    type: FETCH_ALLERGEN,
                    payload: data.payload
                });
                dispatch({
                    type: UPDATE_CUSTOMER_ALLERGENS,
                    payload: data.payload
                });
            } else {
                console.log("data", data);
                dispatch({ type: FETCH_ERROR, payload: data });
            }
        })
        .catch(error => {
            console.log("error:", error);
            dispatch({ type: FETCH_ERROR, payload: error });
        });
};

export const addAllergens = postData => dispatch => {
    let url = api.allergen.add.path;
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
                    type: FETCH_ALLERGEN,
                    payload: data.payload
                });
                dispatch({
                    type: UPDATE_CUSTOMER_ALLERGENS,
                    payload: data.payload
                });
            } else {
                console.log("data", data);
                dispatch({ type: FETCH_ERROR, payload: data });
            }
        })
        .catch(error => {
            console.log("error:", error);
            dispatch({ type: FETCH_ERROR, payload: error });
        });
};
