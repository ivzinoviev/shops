import {runtime__update, shop_management__init} from "../actions/Actions"

const baseState = {
    shops: [],
    storage: []
}

export default function runtimeReducer (state = baseState, action) {
    switch (action.type) {
        case shop_management__init:
            return {
                ...state,
                ...action.data.runtime
            }
        case runtime__update:
            return {
                ...state,
                shops: mergeShopsList(state.shops, action.data.shops),
                storage: mergeProductsList(state.storage, action.data.storage)
            }
    }
    return state;
}

function mergeProductsList(before, updated) {
    return [
        ...before.map(beforeItem => {
            const newValue = updated.find(updatedItem => updatedItem.id === beforeItem.id)
            return newValue ? newValue : beforeItem
        }), // TODO: implement delete attribute via filter
        ...updated.filter(updatedItem => !before.some(beforeItem => beforeItem.id === updatedItem.id))
    ]
}

function mergeShopsList(before, updated) {
    return [
        ...before.map(beforeItem => {
            const updatedShop = updated.find(updatedItem => updatedItem.id === beforeItem.id)
            if (updatedShop) {
                before.products = mergeProductsList(before.products, updated.products)
            }

            return before
        }),
        ...updated.filter(updatedItem => !before.some(beforeItem => beforeItem.id === updatedItem.id))
    ]
}
