import axios from "axios"
import {runtime__update, shop_management__init} from "./Actions"
const LOAD_INIT_URL = '/api/init'
const RESTOCK_URL = '/api/restock'

export function loadInitData() {
    return dispatch => {
        axios.get(LOAD_INIT_URL)
            .then(({data}) => {
                dispatch(addInitData(data))
            })
    }
}

function addInitData(data) {
    return {
        type: shop_management__init,
        data
    }
}

export function updateRuntime(data) {
    return {
        type: runtime__update,
        data
    }
}

export function restock(productId, shopId) {
    return dispatch => {
        axios.post(RESTOCK_URL, {productId, shopId})
            .then(() => {}).catch(e => {
                // TODO
        })
    }
}
