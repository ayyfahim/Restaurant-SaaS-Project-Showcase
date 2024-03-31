import {
    SELECT_ORDER,
    PAYMENT_DONE,
    PAYMENT_ORDERS,
    ADD_TIPS,
    ADD_CARD
} from "../actions/types";

const initialState = {
    order: {},
    orders: [],
    tips: 0,
    selectedCard: null
};

export default function(state = initialState, actions) {
    switch (actions.type) {
        case ADD_CARD:
            return {
                ...state,
                selectedCard: actions.payload?.card
            };
        case PAYMENT_ORDERS:
            return {
                ...state,
                orders: actions.payload?.selectedOrders
            };
        case ADD_TIPS:
            return {
                ...state,
                tips: actions.payload?.tips
            };
        case SELECT_ORDER:
            return {
                ...state,
                order: actions.payload.selectedOrder
            };
        case PAYMENT_DONE:
            return {
                ...state,
                order: {},
                orders: [],
                tips: 0
            };

        default:
            return state;
    }
}
