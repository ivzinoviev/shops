import axios from "axios"
import {runtime__update, shop_management__init} from "./Actions"
const LOAD_INIT_URL = '/api/init'

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
