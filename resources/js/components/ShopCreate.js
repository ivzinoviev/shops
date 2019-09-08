import React from "react"
import Select from "react-select";

const INIT_FORM = {
    name: '',
    shopTypeId: ''
}

export default class ShopCreate extends React.Component {
    constructor(props) {
        super(props)

        this.state = {
            ...this.state,
            form: INIT_FORM
        }

        this.onFieldChange = this.onFieldChange.bind(this)
    }

    render() {
        return <div className="card">
            <div className="card-header">
                <h5 className="card-title">Создать магазин</h5>
            </div>
            <div className="card-body">
                <div className="form-group">
                    <label htmlFor="name">Название магазина</label>
                    <input
                        value={this.state.form.name}
                        type="text"
                        className="form-control"
                        id="name"
                        placeholder="Например, 'Продукты'"
                        onChange={e => this.onFieldChange('name', event.target.value)}
                    />
                </div>
                <div className="form-group">
                    <label htmlFor="shopTypeId">Тип магазина</label>
                    <Select
                        value={this.state.form.shopTypeId}
                        id={'shopTypeId'}
                        onChange={value => this.onFieldChange('shopTypeId', value)}
                        placeholder='Выбрать...'
                        options={this.props.shopTypes.map(type => {
                            return {
                                value: type.id,
                                label: type.name
                            }
                        })}
                    />
                </div>
                { this.isShopsLimitReached() && <small className={"text-danger text-sm"}>Предельное количество магазинов достигнуто</small> }
                <button
                    className={"btn btn-success btn-block"}
                    onClick={() => {this.props.shopCreate({
                        name: this.state.form.name,
                        shopTypeId: this.state.form.shopTypeId.value
                    }, () => {this.clearForm()})}}
                    disabled={!this.isFormValid()}
                >
                    Создать
                </button>
            </div>
        </div>
    }

    isFormValid() {
        return this.state.form.name && this.state.form.shopTypeId && !this.isShopsLimitReached()
    }

    isShopsLimitReached() {
        return this.props.shopsCount >= 20
    }

    onFieldChange(field, value) {
        this.setState({
            form: {
                ...this.state.form,
                [field]: value,
            }
        })
    }

    clearForm() {
        this.setState({
            form: INIT_FORM
        })
    }
}
