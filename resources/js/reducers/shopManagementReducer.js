import {shop_management__init} from "../actions/Actions"

const baseState = {
    products: [],
    shopTypes: [],
}

export default function shopManagementReducer (state = baseState, action) {
    switch (action.type) {
        case shop_management__init:
            return {
                ...state,
                products: action.data.products,
                shopTypes: action.data.shopTypes,
                wsChannel: action.data.wsChannel
            }
    }

    return state
}
