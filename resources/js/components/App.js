// resources/assets/js/components/App.js

import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import { Provider } from 'react-redux'
import { createStore, applyMiddleware } from 'redux'
import thunk from 'redux-thunk'
import rootReducer from './../reducers/rootReducer'
import ShopManagementContainer from "../Containers/ShopManagementContainer"

import 'bootstrap/dist/css/bootstrap.min.css';

const store = createStore(rootReducer, applyMiddleware(thunk))

class App extends Component {
    render () {
        return (
            <Provider store={store}>
                <ShopManagementContainer />
            </Provider>
    )
    }
}

ReactDOM.render(<App />, document.getElementById('app'))
