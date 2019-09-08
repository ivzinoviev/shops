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
                shops: action.data.shops ? mergeShopsList(state.shops, action.data.shops) : state.shops,
                storage: action.data.storage ? mergeProductsList(state.storage, action.data.storage) : state.storage
            }
    }
    return state;
}

function mergeProductsList(before, updated) {
    return [
        ...before.map(beforeItem => {
            const newValue = updated.find(updatedItem => updatedItem.id === beforeItem.id)
            return newValue ? newValue : beforeItem
        }),
        ...updated.filter(updatedItem => !before.some(beforeItem => beforeItem.id === updatedItem.id))
    ]
}

function mergeShopsList(before, updated) {
    return [
        ...before.map(beforeItem => {
            const updatedShop = updated.find(updatedItem => updatedItem.id === beforeItem.id)
            if (updatedShop) {
                return mergeShops(beforeItem, updatedShop)
            }

            return beforeItem
        }),
        ...updated.filter(updatedItem => !before.some(beforeItem => beforeItem.id === updatedItem.id))
    ]
}

function mergeShops(before, {products, ...updated}) {
    before.products = mergeProductsList(before.products, products)
    return {...before, ...updated}
}