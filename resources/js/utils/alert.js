import React from "react"
import { toast } from 'react-toastify';
import uuidv1 from 'uuid/v1'

export function showError(error) {
    if (error.response.status === 422) {
        return toast.error(prepareError422(error.response.data), {
            position: toast.POSITION.BOTTOM_RIGHT
        })
    }

    return toast.error(error.response && error.response.data.message, {
        position: toast.POSITION.BOTTOM_RIGHT
    });
}

export function showSuccess(text) {
    toast.success(text, {
        position: toast.POSITION.BOTTOM_RIGHT
    });
}

function prepareError422(data) {
    return <div>{
        Object.keys(data.errors).map(errorKey => {
            return <ul key={uuidv1()}>{ data.errors[errorKey].map(error => <li key={uuidv1()}>{error}</li>) }</ul>
        })
    }</div>
}