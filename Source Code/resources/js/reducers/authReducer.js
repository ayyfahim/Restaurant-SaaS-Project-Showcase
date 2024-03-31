import {
    LOGIN_CUSTOMER,
    REGISTER_CUSTOMER,
    RESTART_AUTH_RESPONSE,
    LOGOUT_CUSTOMER,
    UPDATE_CUSTOMER,
    UPDATE_CUSTOMER_ALLERGENS,
    ANONYMOUS_LOGIN_CUSTOMER
} from "../actions/types";

const initialState = {
    isLogin: false,

    userId: null,
    userName: null,
    userEmail: null,
    userPhone: null,

    allergens: [],
    cards: [],

    token: null,
    shopId: null
};

export default function(state = initialState, actions) {
    switch (actions.type) {
        case ANONYMOUS_LOGIN_CUSTOMER:
            return {
                ...state,
                isLogin: false,

                userId: actions.payload?.user?.id,

                userName: actions.payload?.user?.full_name,
                userFirstName: actions.payload?.user?.first_name,
                userLastName: actions.payload?.user?.last_name,

                userEmail: actions.payload?.user?.email,
                userPhone: actions.payload?.user?.phone,

                allergens: actions.payload?.user?.allergens,
                cards: actions.payload?.user?.cards,

                token: actions.payload?.access_token
            };
        case LOGIN_CUSTOMER:
            return {
                ...state,
                isLogin: true,

                userId: actions.payload?.user?.id,

                userName: actions.payload?.user?.full_name,
                userFirstName: actions.payload?.user?.first_name,
                userLastName: actions.payload?.user?.last_name,

                userEmail: actions.payload?.user?.email,
                userPhone: actions.payload?.user?.phone,

                allergens: actions.payload?.user?.allergens,
                cards: actions.payload?.user?.cards,

                token: actions.payload?.access_token
            };
        case LOGOUT_CUSTOMER:
            return {
                ...state,
                isLogin: false,

                userId: null,
                userName: null,
                userEmail: null,
                userPhone: null,

                token: null
            };

        case REGISTER_CUSTOMER:
            return {
                ...state,
                isLogin: false,

                userId: null,
                userName: null,
                userEmail: null,
                userPhone: null,

                token: null
            };

        case UPDATE_CUSTOMER:
            return {
                ...state,

                userName: actions.payload?.user?.full_name,
                userFirstName: actions.payload?.user?.first_name,
                userLastName: actions.payload?.user?.last_name,

                userEmail: actions.payload?.user?.email,
                userPhone: actions.payload?.user?.phone,

                allergens: actions.payload?.user?.allergens,
                cards: actions.payload?.user?.cards,

                token: actions.payload?.access_token
            };

        case UPDATE_CUSTOMER_ALLERGENS:
            return {
                ...state,
                allergens: actions.payload?.user?.allergens
            };

        case RESTART_AUTH_RESPONSE:
            return {
                ...state
            };

        default:
            return state;
    }
}
