import React from "react"
import Product from "./Product"
import moment from "moment"
import {Droppable} from "react-beautiful-dnd"
import * as classnames from "classnames"

export default class Shop extends React.Component {
    render() {
        const products = this.props.products
            .filter(product => !(product.soldOutAt && moment().diff(moment(product.soldOutAt), 'seconds') > 10));

        return <Droppable droppableId={'shop_' + this.props.id}>
            {(provided, snapshot) =>
                <div className={classnames({
                        "card not-break-columns mb-3": true,
                        "bg-secondary": snapshot.isDraggingOver,
                    })} ref={provided.innerRef} {...provided.droppableProps}>
                    <div className={
                        classnames({
                            "card-header": true,
                            "bg-danger": snapshot.isDraggingOver,
                            "bg-success": snapshot.isDraggingOver && this.props.productTypes && this.props.productTypes.includes(this.props.getDraggingItem(snapshot.draggingOverWith).product_type_id) && console.log(this.props.getDraggingItem(snapshot.draggingOverWith), this.props.productTypes)
                        })
                    }>
                        <h6 className="card-title">{this.props.name}</h6>
                    </div>
                    <div className="card-body">
                        {products.map(product => <Product
                            {...product}
                            key={product.id}
                        />)}
                        {!products.length && <div className="alert alert-secondary" role="alert">Товаров нет</div>}
                    </div>
                    {console.log(snapshot)}
                    <div style={{ display: 'none' }}>{provided.placeholder}</div>
                </div>
                }
        </Droppable>
    }
}

Shop.defaultProps = {
    products: []
}
