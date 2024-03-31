import { createStore, applyMiddleware, compose } from "redux";
import thunk from "redux-thunk";
import rootReducer from "../reducers";
import { loadFromStorage, saveToLocalStorage } from "../helpers/localStorage";
// const initialState ={}
const middleware = [thunk];

const persistedState = loadFromStorage();
const store = createStore(
    rootReducer,
    persistedState,
    compose(
        applyMiddleware(...middleware)
        // window.__REDUX_DEVTOOLS_EXTENSION__ &&
        //     window.__REDUX_DEVTOOLS_EXTENSION__()
    )
);
store.subscribe(() => saveToLocalStorage(store.getState()));
export default store;
