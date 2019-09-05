import {combineReducers} from "redux"
import shopManagementReducer from "./shopManagementReducer"
import runtimeReducer from "./runtimeReducer"

export default combineReducers({
    shopManagementReducer,
    runtimeReducer
});
