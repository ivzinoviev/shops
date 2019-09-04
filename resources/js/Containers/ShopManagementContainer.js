import {connect} from "react-redux"
import ShopManagement from "../components/ShopManagement"
import {loadInitData} from "../actions/ShopManagementActions"

const mapStateToProps = state => {
    const { webSocketUrl, products } = state.shopMangementReducer
    return {
        webSocketUrl,
        products
    }
}

const mapDispatchToProps = (dispatch, ownProps) => {
    return {
        onInit() {
            dispatch(loadInitData())
        },
        handleWebSocket: (socketData) => {
            console.log(socketData)
        }
    }
}

const ShopManagementContainer = connect(
    mapStateToProps,
    mapDispatchToProps
)(ShopManagement)

export default ShopManagementContainer
