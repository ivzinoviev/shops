import React from "react";
import 'react-confirm-alert/src/react-confirm-alert.css';
import { confirmAlert } from 'react-confirm-alert';

export default function(onYes, options = {}) {
    const baseOptions = {
        title: 'Подтверждение действия',
        message: 'Вы действительно хотите это сделать?',
        buttons: [
            {
                label: 'Да',
                onClick: () => {onYes()}
            },
            {
                label: 'Нет',
                onClick: () => {}
            }
        ],
        childrenElement: () => <div />,
        closeOnEscape: true,
        closeOnClickOutside: true,
    };

    return confirmAlert({...baseOptions, ...options});
}