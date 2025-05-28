import React, { useEffect, useRef } from 'react';

export default function PayPalButton() {
    const paypalRef = useRef(null);

    useEffect(() => {
        const loadPayPalScript = () => {
            const script = document.createElement('script');
            script.src = 'https://www.paypal.com/sdk/js?client-id=AX1KEHhem6-_NEIUQEN3q-QrIv1HPFIOZkvRODv1C8hhqQOQ9eAKiwuXUfPQkXISnaxvSwZygw8k7mbC&currency=USD';
            script.async = true;
            script.onload = () => renderButton();
            document.body.appendChild(script);
        };

        const renderButton = () => {
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
                                value: '230.00',
                                currency_code: 'USD'
                            }
                        }]
                    });
                },
                onApprove: (data, actions) => {
                    return actions.order.capture().then(details => {
                        alert(`Transaction completed by ${details.payer.name.given_name}`);
                    });
                }
            }).render(paypalRef.current);
        };

        loadPayPalScript();
    }, []);

    return (
        <div ref={paypalRef} id="paypal-button-container" />
    );
}
