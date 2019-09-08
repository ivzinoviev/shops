import React from "react"
import confirmAlert from "../utils/confirmAlert";


export default class RestartButton extends React.Component {
    render() {
        return <div className="card mb-2">
            <div className="card-body">
                <button className={"btn btn-danger btn-block"} onClick={() => {
                    confirmAlert(() => {
                        this.props.handleRestart()
                    }, {
                        title: 'Перезапуск',
                        message: 'Вы действительно хотите перезапустить?'
                    }, )
                }}>Перезапуск</button>
            </div>
        </div>
    }
}