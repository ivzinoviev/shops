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
                    <div style={{columns: '140px'}}>
                        { this.props.products.map(product => <Product {...product}  key={product.id} />) }
                    </div>
                </div>
            </div>
    }
}
