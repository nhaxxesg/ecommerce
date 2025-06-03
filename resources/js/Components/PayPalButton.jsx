import React, { useEffect, useRef } from 'react';

export default function PayPalButton({ amount, onSuccess, onError }) {
    const paypalRef = useRef(null);

    useEffect(() => {
        // Elimina el botón anterior si existe
        if (paypalRef.current) {
            paypalRef.current.innerHTML = '';
        }

        // Carga el script de PayPal solo si no está cargado
        if (!window.paypal) {
            const script = document.createElement('script');
            script.src = 'https://www.paypal.com/sdk/js?client-id=AX1KEHhem6-_NEIUQEN3q-QrIv1HPFIOZkvRODv1C8hhqQOQ9eAKiwuXUfPQkXISnaxvSwZygw8k7mbC&currency=USD';
            script.async = true;
            script.onload = () => renderButton();
            document.body.appendChild(script);
        } else {
            renderButton();
        }

        function renderButton() {
            if (!window.paypal || !paypalRef.current) return;

            window.paypal.Buttons({
                style: {
                    layout: 'vertical',
                    color: 'gold',
                    shape: 'rect',
                    label: 'paypal'
                },
                createOrder: (data, actions) => {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: amount.toFixed(2),
                                currency_code: 'USD'
                            }
                        }]
                    });
                },
                onApprove: (data, actions) => {
                    return actions.order.capture().then(details => {
                        if (onSuccess) onSuccess(details);
                    });
                },
                onError: (err) => {
                    if (onError) onError(err);
                }
            }).render(paypalRef.current);
        }
        // eslint-disable-next-line
    }, [amount, onSuccess, onError]);

    return (
        <div ref={paypalRef} id="paypal-button-container" />
    );
}
