import { FETCH_ALL_TRANSLATION, FETCH_TRANSLATION } from "../actions/types";
const initialState = {
    languages: [],
    active: [],
    active_language_id: null
};

export default function(state = initialState, actions) {
    switch (actions.type) {
        case FETCH_TRANSLATION:
            return {
                ...state,
                active: actions.payload.data
            };
        case FETCH_ALL_TRANSLATION:
            return {
                ...state,
                languages: actions.payload.data
            };
        default:
            return state;
    }
}
