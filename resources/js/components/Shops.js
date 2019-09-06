import React from "react"
import Shop from "./Shop"

export default class Shops extends React.Component {
    render() {
        return <div className="card">
            <div className="card-header">
                <h4 className="card-title">Список магазинов</h4>
            </div>
            <div className="card-body">
                <div className="col-12 pb-3">
                    <p className="card-text mb-1">Список магазинов и их товаров</p>
                    <span className="badge badge-primary mr-2">Куплен</span>
                    <span className="badge badge-success mr-2">Добавлен</span>
                    <span className="badge badge-danger">Закончился</span>
                </div>
                <div className={"col-12"} style={{'columns': '200px auto'}}>
                { this.props.shops.map(shop => <Shop
                    {...shop}
                    key={shop.id}
                    getDraggingItem={this.props.getDraggingItem}
                />) }
                </div>
            </div>
        </div>
    }
}
