import {connect} from "react-redux"
import ShopManagement from "../components/ShopManagement"
import {loadInitData, updateRuntime} from "../actions/ShopManagementActions"

const mapStateToProps = state => {
    const { wsChannel, products } = state.shopManagementReducer
    const {shops, storage} = state.runtimeReducer
    return {
        wsChannel,
        shops: mapShopsProducts(shops, products),
        storage: mapProducts(storage, products),
        getDraggingItem: (itemId) => { // TODO!!!
            return {
                product_type_id: 1
            }
        }
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

function mapShopsProducts(shops, products) {
    return shops.map(shop => {
        shop.products = mapProducts(shop.products, products)
       return shop
    })
}

function mapProducts(storage, products) {
    return storage.map(storageProduct => {
        const product = products.find(item => storageProduct.id === item.id) || {}
        return {...product, ...storageProduct}
    })
}
