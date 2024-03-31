import { FETCH_ERROR, FETCH_REFRESH, FETCH_SUCCESS } from "../actions/types";

const initialState = {
    status: null,
    message: null,
    errors: []
};

export default function(state = initialState, actions) {
    switch (actions.type) {
        case FETCH_ERROR:
            return {
                ...state,

                status: "error",
                message: actions?.payload?.message ?? "An error occurred.",
                errors: actions?.payload?.errors ?? []
            };

        case FETCH_REFRESH:
            return state;

        case FETCH_SUCCESS:
            return {
                status: "success",
                message: actions?.payload?.message,
                errors: []
            };

        default:
            return state;
    }
}
