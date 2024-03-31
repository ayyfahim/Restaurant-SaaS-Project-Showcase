import { ADD_TO_CART, REMOVE_FROM_CART, EMPTY_CART } from "../actions/types";

const initialState = {
    Items: []
};

export default function(state = initialState, actions) {
    switch (actions.type) {
        case ADD_TO_CART:
            let data = state.Items.find(
                items =>
                    items.itemId == actions.data.itemId &&
                    items.storeId == actions.data.storeId &&
                    items.addon == actions.data.addon
            );
            if (data) {
                data.count = data.count + actions.data.count;
                return {
                    ...state,
                    Items: state.Items.map(Item =>
                        Item.itemId == data.itemId && Item.addon == data.addon
                            ? {
                                  ...Item,
                                  count: data.count,
                                  addon: actions.data.addon,
                                  extra: actions.data.extra
                              }
                            : Item
                    )
                };
            }
            return {
                ...state,
                Items: [...state.Items, actions.data]
            };
        case REMOVE_FROM_CART:
            const itemId = actions.data;
            const addon = actions.addon;
            // console.log(addon)
            // console.log(state.Items.filter(Item => (Item.itemId != itemId && addon != Item.addon)))
            let newItems = state.Items.filter(Item => {
                if (Item.itemId == itemId) {
                    if (addon == Item.addon) return false;
                }
                return true;
            });
            // console.log(newItems)
            return {
                ...state,
                Items:
                    addon != null
                        ? newItems
                        : state.Items.filter(Item => Item.itemId !== itemId)
            };

        case EMPTY_CART:
            return {
                ...state,
                Items: []
            };

        default:
            return state;
    }
}
