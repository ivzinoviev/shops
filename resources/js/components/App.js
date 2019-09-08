// resources/assets/js/components/App.js

import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import { Provider } from 'react-redux'
import { createStore, applyMiddleware } from 'redux'
import thunk from 'redux-thunk'
import rootReducer from './../reducers/rootReducer'
import ShopManagementContainer from "../containers/ShopManagementContainer"
import {ToastContainer} from "react-toastify"
import 'react-toastify/dist/ReactToastify.css';

import 'bootstrap/dist/css/bootstrap.min.css';

const store = createStore(rootReducer, applyMiddleware(thunk))

class App extends Component {
    render () {
        return <React.Fragment>
            <Provider store={store}>
                <ShopManagementContainer />
            </Provider>
            <ToastContainer />
        </React.Fragment>
    }
}

ReactDOM.render(<App />, document.getElementById('app'))
