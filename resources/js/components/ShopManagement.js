import React from 'react';
import Pusher from 'react-pusher';
import ProductsStorage from "./ProductsStorage"

export default class ShopManagement extends React.Component {
    componentDidMount() {
        this.props.onInit()
    }

    render() {
        return <div className="container pt-5">
            <div className="row">
                <div className="col-9">
                    <ProductsStorage
                        products={this.props.products}
                        storage={this.props.storage}
                    />
                </div>
                <div className="col-3">
                    <div className="card">
                        <div className="card-header">
                            <h5 className="card-title">Создать магазин</h5>
                        </div>
                        <div className="card-body">

                        </div>
                    </div>
                </div>
            </div>
            <div className="row pt-5">
                <div className="col">
                    <div className="card">
                        <div className="card-header">
                            <h5 className="card-title">Список магазинов</h5>
                        </div>
                        <div className="card-body">

                        </div>
                    </div>
                </div>
            </div>
            { this.props.wsChannel && <Pusher
                channel={this.props.wsChannel}
                event="App\Events\SessionTick"
                onUpdate={({data}) => {this.props.handleSessionTick(data)}}
            /> }
        </div>
    }
}
