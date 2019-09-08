import React from "react"
import confirmAlert from "../utils/confirmAlert";
import Octicon from "react-component-octicons";

export default class Shop extends React.Component {
    render() {
        return <div className={"float-right"}>
            <button
                className={"btn btn-sm btn-link text-danger"}
                onClick={() => {
                    confirmAlert(() => {
                        this.props.handleShopDelete(this.props.shopId)
                    }, {
                        title: 'Удаление магазина',
                        message: 'Вы действительно хотите удалить магазин? Товары будут возвращены на склад.'
                    })
                }}
            >
                <Octicon name="x"/>
            </button>
        </div>
    }
}

