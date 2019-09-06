import React from "react"
import moment from "moment"

export default class Product extends React.Component {
    render() {
        return <div className="p-2 bd-highlight">
            {this.props.name}:
            &nbsp;
            <span className={"badge " + getBageClass(this.props)} >{ this.props.count }</span>
        </div>
    }
}

Product.defaultProps = {
    count: 0
}

function getBageClass(product) {
    if (product.count === 0) {
        return "badge-danger"
    }

    if (product.lastBuyAt && moment().diff(moment(product.lastBuyAt), 'seconds') < 3) {
        return "badge-primary"
    }

    if (product.lastAddAt && moment().diff(moment(product.lastAddAt), 'seconds') < 30) {
        return "badge-success"
    }

    return "badge-secondary"
}
