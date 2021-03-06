import {connect} from "react-redux"
import ShopManagement from "../components/ShopManagement"
import {loadInitData, restart, restock, shopCreate, shopDelete, updateRuntime} from "../actions/ShopManagementActions"

const mapStateToProps = state => {
    const { wsChannel, products, shopTypes } = state.shopManagementReducer
    const {shops, storage} = state.runtimeReducer
    return {
        wsChannel,
        shopTypes,
        shops: mapShopsProducts(filterDeleted(shops), products),
        storage: mapProducts(storage, products),
        getDraggingItem: (itemId) => {
            return resolveDragId(itemId, products, shops)
        }
    }
}

const mapDispatchToProps = (dispatch, ownProps) => {
    return {
        onInit() {
            dispatch(loadInitData())
        },
        handleSessionTick: (eventData) => {
            if (eventData.doInit) {
                dispatch(loadInitData())
            }
            dispatch(updateRuntime(eventData))
        },
        handleDrop: ({draggableId, destination}) => {
            destination.droppableId !== 'storage' && dispatch(restock(getDragIdNumber(draggableId), getDragIdNumber(destination.droppableId)))
        },
        handleRestart: () => {
            dispatch(restart())
        },
        handleShopDelete: shopId => {
            dispatch(shopDelete(shopId))
        },
        shopCreate: (data, callback) => {
            dispatch(shopCreate(data, callback))
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

function mapProducts(storage = [], products) {
    return storage.map(storageProduct => {
        const product = products.find(item => storageProduct.id === item.id) || {}
        return {...product, ...storageProduct}
    })
}

function resolveDragId(itemId, products, shops) {
    if (itemId.startsWith('product_')) {
        return products.find(product => product.id === getDragIdNumber(itemId))
    }
    if (itemId.startsWith('shop_')) {
        return shops.find(shop => shop.id === getDragIdNumber(itemId))
    }
}

function getDragIdNumber(itemId) {
    const itemNumber = itemId.match(/\d+/g)
    return itemNumber ? parseInt(itemNumber[0]) : itemNumber;
}

function filterDeleted(items) {
    return items.filter(item => !item.deletedAt)
}
