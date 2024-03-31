import {
    CREATE_ORDER,
    FETCH_ORDERS,
    FETCH_TABLE,
    LEAVE_TABLE,
    SELECT_ORDER
} from "../actions/types";

const initialState = {
    order: {},
    Orders: [],
    selectedTable: {},
    selectedTableOrders: []
};

export default function(state = initialState, actions) {
    switch (actions.type) {
        case CREATE_ORDER:
            return {
                ...state,
                Orders: actions?.payload?.user_orders,
                selectedTableOrders: actions?.payload?.table_orders
            };
        case SELECT_ORDER:
            return {
                ...state,
                order: actions?.payload?.select_order
            };
        case FETCH_TABLE:
            return {
                ...state,
                selectedTable: actions?.payload?.data
            };
        case LEAVE_TABLE:
            return {
                ...state,
                selectedTable: {}
            };
        default:
            return state;
    }
}
