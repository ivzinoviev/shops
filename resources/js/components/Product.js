import React from "react"

export default class Product extends React.Component {
    render() {
        return <div className="p-2 bd-highlight">
            {this.props.name}:
            &nbsp;
            <span className="badge badge-secondary">{ this.props.count }</span>
        </div>
    }
}

Product.defaultProps = {
    count: 0
}
