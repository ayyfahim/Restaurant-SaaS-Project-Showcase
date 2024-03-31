import { FETCH_REFRESH } from "./types";

export const resetFetch = () => dispatch => {
    dispatch({ type: FETCH_REFRESH });
};
