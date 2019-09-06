import React from 'react';
import Product from "./Product"
import {Draggable, Droppable} from "react-beautiful-dnd"

export default class ProductsStorage extends React.Component {
    render() {
        return <div className="card">
                <div className="card-header">
                    <h5 className="card-title">Склад</h5>
                </div>
               <Droppable droppableId={'storage'} >
                   {(provided) =>
                   <div className="card-body" ref={provided.innerRef} {...provided.droppableProps} >
                       <p className="card-text">Список товаров на складе</p>
                       <div style={{columns: '150px'}}>
                           {this.props.storage.map(product =>
                               <Draggable draggableId={'product_' + product.id} index={product.id} key={product.id}>
                                   { (provided) =>
                                       <div ref={provided.innerRef}
                                           {...provided.draggableProps}
                                           {...provided.dragHandleProps}>
                                           <Product
                                               {...product}
                                           />
                                       </div>
                                   }
                               </Draggable>
                           )}
                       </div>
                       <div style={{ display: 'none' }}>{provided.placeholder}</div>
                   </div>}
               </Droppable>
           </div>
    }
}

ProductsStorage.defaultProps = {
    storage: []
}

// function getStorageProductsCount(storage = [], productId) {
//     const prod = storage.find(product => product.id === productId)
//     return prod ? prod.count : 0
// }
