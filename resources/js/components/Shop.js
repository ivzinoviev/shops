import React from "react"
import Product from "./Product"
import moment from "moment"
import {Droppable} from "react-beautiful-dnd"
import * as classnames from "classnames"
import ShopDeleteButton from "./ShopDeleteButton";

export default class Shop extends React.Component {
    render() {
        const products = this.props.products
            .filter(product => !(product.soldOutAt && moment().diff(moment(product.soldOutAt), 'seconds') > 10));

        return <Droppable droppableId={'shop_' + this.props.id}>
            {(provided, snapshot) => {
                const draggedValid = snapshot.isDraggingOver && this.props.productTypes && this.props.productTypes.includes(this.props.getDraggingItem(snapshot.draggingOverWith).product_type_id)
                return <div className={classnames({
                        "card not-break-columns mb-3": true,
                        "bg-light": snapshot.isDraggingOver,
                    })} ref={provided.innerRef} {...provided.droppableProps}>
                    <div className={
                        classnames({
                            "card-header": true,
                            "bg-success": draggedValid,
                            "bg-danger": snapshot.isDraggingOver && !draggedValid,
                        })
                    }>
                        <h6 className="card-title float-left pt-1">{this.props.name}</h6>
                        <ShopDeleteButton
                            handleShopDelete={this.props.handleShopDelete}
                            shopId={this.props.id}
                        />
                    </div>
                    <div className="card-body">
                        {products.map(product => <Product
                            {...product}
                            key={product.id}
                        />)}
                        {!products.length && <div className="alert alert-secondary" role="alert">Товаров нет</div>}
                    </div>
                    <div style={{ display: 'none' }}>{provided.placeholder}</div>
                </div>
                }}
        </Droppable>
    }
}

Shop.defaultProps = {
    products: []
}
