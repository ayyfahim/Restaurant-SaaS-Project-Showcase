import ROUTE from "../config/route";
import miscVariables from "./misc";

let returnBackToStore = () => {
    if (localStorage.getItem("storeId")) {
        setInterval(
            () =>
                (window.location.href = `${
                    ROUTE.STORE.INDEX.PAGES.DETAILED.PATH
                }/${localStorage.getItem("storeId")}`),
            miscVariables.setTimeoutTimer
        );
    }
};

export { returnBackToStore };
