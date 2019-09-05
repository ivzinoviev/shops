import React from 'react';
import Product from "./Product"

export default class ProductsStorage extends React.Component {
    render() {
        return <div className="card">
                <div className="card-header">
                    <h5 className="card-title">Склад</h5>
                </div>
                <div className="card-body">
                    <p className="card-text">Список товаров на складе</p>
                    <div style={{columns: '150px'}}>
                        { this.props.products.map(product => <Product
                            {...product}
                            key={product.id}
                            count={getStorageProductsCount(this.props.storage, product.id)}
                        />) }
                    </div>
                </div>
            </div>
    }
}

function getStorageProductsCount(storage = [], productId) {
    const prod = storage.find(product => product.id === productId)
    return prod ? prod.count : 0
}
