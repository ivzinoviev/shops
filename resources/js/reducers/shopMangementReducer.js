import {shop_management__init} from "../actions/Actions"

const baseState = {
    products: [],
    shopTypes: [],
    store: [],
    shops: [],
}

export default function shopMangementReducer (state = baseState, action) {
    switch (action.type) {
        case shop_management__init:
            return {
                ...state,
                ...action.data
            }
    }

    return state
}
