let saveToLocalStorage = state => {
    try {
        const serialized = JSON.stringify(state);
        localStorage.setItem("state", serialized);
    } catch (e) {
        console.log(e);
    }
};
let loadFromStorage = () => {
    try {
        const serialized = localStorage.getItem("state");
        if (serialized === null) return {};
        return JSON.parse(serialized);
    } catch (e) {
        console.log(e);
        return {};
    }
};
export { saveToLocalStorage, loadFromStorage };
