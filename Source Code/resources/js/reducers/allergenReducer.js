import { FETCH_ALLERGEN, REFRESH_ALLERGEN } from "../actions/types";

const initialState = {
    allergens: []
};

export default function(state = initialState, actions) {
    switch (actions.type) {
        case FETCH_ALLERGEN:
            return {
                ...state,
                allergens: actions?.payload?.allergens
            };

        case REFRESH_ALLERGEN:
            return state;

        default:
            return state;
    }
}
