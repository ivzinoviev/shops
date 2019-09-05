import {connect} from "react-redux"
import ShopManagement from "../components/ShopManagement"
import {loadInitData, updateRuntime} from "../actions/ShopManagementActions"

const mapStateToProps = state => {
    const { wsChannel, products } = state.shopManagementReducer
    const {shops, storage} = state.runtimeReducer
    return {
        wsChannel,
        products,
        shops,
        storage
    }
}

const mapDispatchToProps = (dispatch, ownProps) => {
    return {
        onInit() {
            dispatch(loadInitData())
        },
        handleSessionTick: (eventData) => {
            dispatch(updateRuntime(eventData))
        }
    }
}

const ShopManagementContainer = connect(
    mapStateToProps,
    mapDispatchToProps
)(ShopManagement)

export default ShopManagementContainer
