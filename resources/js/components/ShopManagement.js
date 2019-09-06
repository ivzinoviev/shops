import React from 'react';
import Pusher from 'react-pusher';
import ProductsStorage from "./ProductsStorage"
import Shops from "./Shops"
import ShopCreate from "./ShopCreate"
import {DragDropContext} from "react-beautiful-dnd"

export default class ShopManagement extends React.Component {
    componentDidMount() {
        this.props.onInit()
    }

    render() {
        return <DragDropContext onDragEnd={e => {console.log("DRAG END", e)}}>
            <div className="container pt-5">
            <div className="row">
                <div className="col-9">
                    <ProductsStorage
                        // products={this.props.products}
                        storage={this.props.storage}
                    />
                </div>
                <div className="col-3">
                    <ShopCreate />
                </div>
            </div>
            <div className="row pt-5">
                <div className="col">
                    <Shops
                        shops={this.props.shops}
                        getDraggingItem={this.props.getDraggingItem}
                    />
                </div>
            </div>
            { this.props.wsChannel && <Pusher
                channel={this.props.wsChannel}
                event="App\Events\SessionTick"
                onUpdate={({data}) => {this.props.handleSessionTick(data)}}
            /> }
        </div>
        </DragDropContext>
    }
}
