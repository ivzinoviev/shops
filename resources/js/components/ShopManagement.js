import React from 'react';
import Pusher from 'react-pusher';
import ProductsStorage from "./ProductsStorage"
import Shops from "./Shops"
import ShopCreate from "./ShopCreate"
import {DragDropContext} from "react-beautiful-dnd"
import RestartButton from "./RestartButton";

export default class ShopManagement extends React.Component {
    componentDidMount() {
        this.props.onInit()
    }

    render() {
        return <DragDropContext onDragEnd={this.props.handleDrop}>
            <div className="container pt-5">
            <div className="row">
                <div className="col-8">
                    <ProductsStorage
                        storage={this.props.storage}
                    />
                </div>
                <div className="col-4">
                    <RestartButton
                        handleRestart={this.props.handleRestart}
                    />
                   <ShopCreate
                        shopTypes={this.props.shopTypes}
                        shopCreate={this.props.shopCreate}
                        shopsCount={this.props.shops.length}
                    />
                </div>
            </div>
            <div className="row pt-5">
                <div className="col">
                    <Shops
                        shops={this.props.shops}
                        getDraggingItem={this.props.getDraggingItem}
                        handleShopDelete={this.props.handleShopDelete}
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
