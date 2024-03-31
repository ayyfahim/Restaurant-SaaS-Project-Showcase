import { FETCH_STORE_ITEMS } from "../actions/types";
const initialState = {
    recommendedItems: [],
    categories: [],
    products: [],
    account_info: [],
    store_name: null,
    store_logo: null,
    store_logo_wide: null,
    description: null,
    service_charge: 0,
    tax: 0,
    sliders: []
};
export default function(state = initialState, actions) {
    switch (actions.type) {
        case FETCH_STORE_ITEMS:
            // console.log("store:>", actions.payload.data);
            return {
                ...state,
                description: actions.payload.data.description,
                store_name: actions.payload.data.store_name,
                store_logo: actions.payload.data.store_logo,
                store_logo_wide: actions.payload.data.store_logo_wide,
                store_address: actions.payload.data.store_address,
                service_charge: actions.payload.data.service_charge,
                tax: actions.payload.data.tax,
                recommendedItems: actions.payload.data.recommended,
                food_menus: actions.payload.data.food_menus ?? [],
                categories: actions.payload.data.categories ?? [],
                products: actions.payload.data.products ?? [],
                account_info: actions.payload.data.account_info,
                sliders: actions.payload.data.sliders,
                tables: actions.payload.data.tables,
                is_accept_order: actions.payload.data.is_accept_order,
                pay_first: actions.payload.data.pay_first,
                addons: actions.payload.data.addons,
                addon_categories: actions.payload.data.addon_categories,
                store_latitude: actions.payload.data.store_latitude,
                store_longitude: actions.payload.data.store_longitude,
                is_location_required: actions.payload.data.is_location_required,
                order_range: actions.payload.data.order_range
                // time_restriction: actions.payload.data.addons
            };
        default:
            return state;
    }
}
