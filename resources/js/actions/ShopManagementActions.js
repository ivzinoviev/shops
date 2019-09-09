import axios from "axios"
import {runtime__update, shop_management__init} from "./Actions"
import {showError, showSuccess} from "../utils/alert";
const LOAD_INIT_URL = '/api/init'
const RESTOCK_URL = '/api/restock'
const RESTART_URL = '/api/restart'
const SHOP_URL = '/api/shop'

export function loadInitData() {
    return dispatch => {
        axios.get(LOAD_INIT_URL)
            .then(({data}) => {
                dispatch(addInitData(data))
            }).catch(error => showError(error))
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
            .then(() => {
                showSuccess("Запасы магазина пополнены")
            }).catch(error => showError(error))
    }
}

export function restart() {
    return dispatch => {
        axios.get(RESTART_URL)
            .then(() => {
                showSuccess("Перезапуск выполнен")
            }).catch(error => showError(error))
    }
}

export function shopDelete(shopId) {
    return dispatch => {
        axios.delete(SHOP_URL, {
            data: {shopId}
        })
            .then(() => {
                showSuccess("Магазин удалён")
            }).catch(error => showError(error))
    }
}

export function shopCreate(data, callback) {
    return dispatch => {
        axios.post(SHOP_URL, data)
            .then(() => {
                callback && callback()
                showSuccess("Магазин создан")
            }).catch(error => showError(error))
    }
}
