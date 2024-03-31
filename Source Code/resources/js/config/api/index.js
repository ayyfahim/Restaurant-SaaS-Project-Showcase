import domain from "./domain";
const api = {
    store: {
        Check: {
            fetch: {
                name: "check if exist",
                path: domain.url + "/api/web/store/check"
            }
        },
        StoreItems: {
            fetch: {
                name: "add new activity",
                path: domain.url + "/api/web/store/fetch"
            }
        },
        CerateOrder: {
            cerate: {
                name: "create order",
                path: domain.url + "/api/web/store/create/order"
            }
        },
        FetchOrder: {
            fetch: {
                name: "create order",
                path: domain.url + "/api/web/store/account/orders"
            }
        },
        FetchTableOrder: {
            fetch: {
                name: "create order",
                path: domain.url + "/api/web/store/account/orders/table"
            }
        },
        Table: {
            fetch: {
                name: "select table from qr table",
                path: domain.url + "/api/web/store/account/select_table"
            },
            fetchByUser: {
                name: "select table from qr table",
                path: domain.url + "/api/web/store/account/fetch_table"
            },
            leave: {
                name: "leave a table",
                path: domain.url + "/api/web/store/account/leave_table"
            }
        },
        Waiter: {
            call: {
                name: "Waiter Call",
                path: domain.url + "/api/web/store/waiter/call"
            }
        },
        Translation: {
            fetch: {
                name: "Translation",
                path: domain.url + "/api/web/store/translation/active"
            }
        },
        Payment: {
            create: {
                name: "Translation",
                path: domain.url + "/api/web/store/create/payment"
            }
        },
        Card: {
            delete: {
                name: "Delete Card",
                path: domain.url + "/api/delete-card"
            }
        },
        Checkout: {
            getPaymentMethods: {
                name: "add new activity",
                path: domain.url + "/api/getPaymentMethods"
            },
            initiatePayment: {
                name: "add new activity",
                path: domain.url + "/api/initiatePayment"
            },
            submitAdditionalDetails: {
                name: "add new activity",
                path: domain.url + "/api/submitAdditionalDetails"
            },
            limonetikCreateOrderForCard: {
                name: "add new activity",
                path: domain.url + "/api/limonetikCreateOrderForCard"
            },
            limonetikCreateOrder: {
                name: "add new activity",
                path: domain.url + "/api/limonetikCreatePayment"
            },
            limonetikGetOrder: {
                name: "add new activity",
                path: domain.url + "/api/limonetikGetOrder"
            },
            limonetikChargeOrder: {
                name: "add new activity",
                path: domain.url + "/api/limonetikChargeOrder"
            },
            limonetikChargeOrderForCard: {
                name: "add new activity",
                path: domain.url + "/api/limonetikChargeOrderForCard"
            }
        },
        All_Translation: {
            fetch: {
                name: "Translation",
                path: domain.url + "/api/web/store/translations"
            }
        }
    },

    allergen: {
        fetch: {
            name: "fetch all allergens",
            path: domain.url + "/api/web/fetch/allergens"
        },
        add: {
            name: "add allergens",
            path: domain.url + "/api/web/allergens/add"
        }
    },

    coupon: {
        check: {
            name: "check coupon",
            path: domain.url + "/api/web/check_coupon"
        }
    },

    customer: {
        auth: {
            register: {
                name: "Register Customer",
                path: domain.url + "/api/customer-register/firebase"
            },
            login: {
                name: "Login Customer",
                path: domain.url + "/api/customer-login"
            },
            loginFacebookCallbackUrl: {
                name: "Login Customer",
                path: domain.url + "/api/customer-login/facebook/callback"
            },
            loginGoogleCallbackUrl: {
                name: "Login Customer",
                path: domain.url + "/api/customer-login/google/callback"
            },
            loginGoogle: {
                name: "Login Customer using GitHub",
                path: domain.url + "/api/customer/login/google/callback"
            },
            loginFacebook: {
                name: "Login Customer using GitHub",
                path: domain.url + "/api/customer/login/facebook/callback"
            },
            logout: {
                name: "Logout Customer",
                path: domain.url + "/api/customer-logout"
            },
            update: {
                name: "Update Customer",
                path: domain.url + "/api/customer-update"
            },
            updatePassword: {
                name: "Update Password",
                path: domain.url + "/api/customer-update-password"
            },
            me: {
                name: "Me",
                path: domain.url + "/api/customer-me"
            },
            sendOtpToEmail: {
                name: "Me",
                path: domain.url + "/api/customer/send-otp-to-mail"
            },
            verifyOtpEmail: {
                name: "Me",
                path: domain.url + "/api/customer/verify-otp-mail"
            },
            checkCustomerByPhone: {
                name: "Me",
                path: domain.url + "/api/customer/checkCustomerByPhone"
            }
        }
    }
};
export default api;
